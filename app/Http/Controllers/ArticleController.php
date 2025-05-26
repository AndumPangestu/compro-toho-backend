<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Article;
use App\Models\Donation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ArticleCategory;
use App\Models\EmailSubscriber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\ArticleNotification;
use App\Http\Requests\ArticleFilterRequest;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\ArticleDetailResource;

class ArticleController extends Controller
{
    public function indexUser(ArticleFilterRequest $request)
    {


        $query = Article::latest();

        // Filter berdasarkan request
        $query->when($request->filled('put_on_highlight'), fn($q) => $q->where('put_on_highlight', $request->put_on_highlight))
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('donation_id'), fn($q) => $q->where('donation_id', $request->donation_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });



        if ($request->filled('type')) {
            $query->where('type', $request->type);
        } else {
            $query->whereNot('type', 'infographics');
        }



        $paginate = $request->input('paginate');
        $limit = $request->input('limit');

        if ($paginate) {
            $articles = $query->paginate($request->input('per_page', 10));
            $pagination = [
                'total' => $articles->total(),
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'from' => $articles->firstItem(),
                'to' => $articles->lastItem(),
            ];
        } else {
            $articles = $limit ? $query->limit($limit)->get() : $query->get();
            $pagination = null;
        }

        return $this->sendSuccess(200, ArticleResource::collection($articles), "Articles fetched successfully", $pagination);
    }

    public function indexAdmin(Request $request)
    {
        return view('articles.index');
    }

    public function getArticles(Request $request)
    {
        if ($request->ajax()) {
            $data = Article::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($article) {
                    return $article->category ? $article->category->name : '-';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('articles.show', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                        . '<a href="' . route('articles.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action=" ' . route('articles.destroy', $row->id) . '" method="POST" class="d-inline">'
                        . csrf_field()
                        . method_field("DELETE")
                        . '<button type="submit" class="btn btn-sm btn-danger btn-delete" data-id="{{ $row->id }}">Delete</button>'
                        . '</form>';
                })
                ->rawColumns(['image', 'action'])
                ->escapeColumns([])
                ->make(true);
        }

        abort(403);
    }
    public function create()
    {
        $categories = ArticleCategory::all();
        $donations = Donation::all();
        return view('articles.article-form', compact('categories', 'donations'));
    }

    public function store(ArticleRequest $request)
    {
        try {

            $request->validate([
                'image' => 'required|mimes:jpeg,png,jpg,gif|max:4096',
            ]);

            $article = Article::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'type' => $request->type,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'put_on_highlight' => $request->put_on_highlight
            ]);

            if ($request->tags) {
                $tagNames = explode(',', $request->tags);
                $tagIds = [];
                foreach ($tagNames as $name) {
                    $tag = Tag::firstOrCreate(['name' => trim($name)]);
                    $tagIds[] = $tag->id;
                }
                $article->tags()->sync($tagIds);
            }

            if ($request->hasFile('image')) {
                $article->addMedia($request->file('image'))->toMediaCollection('articles');
            }

            if ($request->filled('content')) {

                $content = $request->content;
                $dom = new \DomDocument();
                libxml_use_internal_errors(true);
                $dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                libxml_clear_errors();

                $imageFile = $dom->getElementsByTagName('img');


                foreach ($imageFile as $index => $image) {
                    $data = $image->getAttribute('src');

                    if (Str::startsWith($data, 'data:image')) {
                        list($type, $data) = explode(';', $data);
                        list(, $data) = explode(',', $data);
                        $imageData = base64_decode($data);

                        // Simpan sebagai file sementara
                        $tempFilePath = tempnam(sys_get_temp_dir(), 'article_img');
                        file_put_contents($tempFilePath, $imageData);

                        // Simpan ke Spatie Media Library
                        $media = $article->addMedia($tempFilePath)
                            ->usingFileName(time() . $index . '.png')
                            ->toMediaCollection('articles_content');

                        // Ganti URL di Summernote agar sesuai denga    n media yang tersimpan
                        $image->setAttribute('src', $media->getUrl());
                    }
                }

                $article->content = $dom->saveHTML();
                $article->save();
            }


            if ($request->filled('share_via_email') && $article->type != 'infographics') {
                $users = EmailSubscriber::all();
                Notification::send($users, new ArticleNotification($article));
            }

            return $request->wantsJson()
                ? $this->sendSuccess(201, new ArticleResource($article), "Article created successfully")
                : redirect()->route('articles.index')->with('success', 'Article created successfully');
        } catch (\Exception $e) {
            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, Article $article)
    {

        $article->tags = DB::table('tags')
            ->join('article_tag', 'tags.id', '=', 'article_tag.tag_id')
            ->where('article_tag.article_id', $article->id)
            ->select('tags.*') // Pilih kolom dari tabel tags
            ->get();
        if ($request->wantsJson()) {
            return  $this->sendSuccess(200, new ArticleDetailResource($article), "Article fetched successfully");
        }

        $viewMode = true;
        return view('articles.article-form', compact('article', 'viewMode'));
    }


    public function edit(Article $article)
    {
        $article->tags = DB::table('tags')
            ->join('article_tag', 'tags.id', '=', 'article_tag.tag_id')
            ->where('article_tag.article_id', $article->id)
            ->select('tags.*') // Pilih kolom dari tabel tags
            ->get();
        $categories = ArticleCategory::all();
        $donations = Donation::all();
        return view('articles.article-form', compact('article', 'categories', 'donations'));
    }

    public function update(ArticleRequest $request, Article $article)
    {
        try {
            $article->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'type' => $request->type,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'put_on_highlight' => $request->put_on_highlight,
            ]);


            if ($request->tags) {
                $tagNames = explode(',', $request->tags);
                $tagIds = [];
                foreach ($tagNames as $name) {
                    $tag = Tag::firstOrCreate(['name' => trim($name)]);
                    $tagIds[] = $tag->id;
                }
                $article->tags()->sync($tagIds);
            }

            if ($request->hasFile('image')) {
                $article->clearMediaCollection('articles');
                $article->addMedia($request->file('image'))->toMediaCollection('articles');
            }


            if ($request->filled('content')) {



                $dom = new \DomDocument();
                libxml_use_internal_errors(true);
                $dom->loadHtml($request->content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                libxml_clear_errors();

                $imageFile = $dom->getElementsByTagName('img');
                $newMediaUrls = []; // Simpan daftar gambar baru

                foreach ($imageFile as $index => $image) {
                    $data = $image->getAttribute('src');

                    // Jika gambar adalah base64 (gambar baru yang diunggah)
                    if (Str::startsWith($data, 'data:image')) {
                        list($type, $data) = explode(';', $data);
                        list(, $data) = explode(',', $data);
                        $imageData = base64_decode($data);

                        // Simpan sebagai file sementara
                        $tempFilePath = tempnam(sys_get_temp_dir(), 'article_img');
                        file_put_contents($tempFilePath, $imageData);

                        // Simpan ke Media Library
                        $media = $article->addMedia($tempFilePath)
                            ->usingFileName(time() . $index . '.png')
                            ->toMediaCollection('articles_content');

                        // Ganti URL di Summernote agar sesuai dengan media yang tersimpan
                        $image->setAttribute('src', $media->getUrl());

                        // Simpan URL gambar baru
                        $newMediaUrls[] = $media->getUrl();
                    } else {
                        // Jika gambar sudah ada, tambahkan ke daftar gambar baru
                        $newMediaUrls[] = $data;
                    }
                }

                // Hapus gambar lama yang tidak digunakan lagi
                foreach ($article->getMedia('articles_content') as $media) {
                    if (!in_array($media->getUrl(), $newMediaUrls)) {
                        $media->delete();
                    }
                }

                $article->content = $dom->saveHTML();
                $article->save();
            }


            return $request->wantsJson()
                ? $this->sendSuccess(200, new ArticleResource($article), "Article updated successfully")
                : redirect()->route('articles.index')->with('success', 'Article updated successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, Article $article)
    {
        try {

            $article->clearMediaCollection('articles');
            $article->clearMediaCollection('articles_content');
            $article->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Article deleted successfully")
                : redirect()->route('articles.index')->with('success', 'Article deleted successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }
}
