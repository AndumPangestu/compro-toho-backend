<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\BannerResource;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class BannerController extends Controller
{
    public function index(Request $request)
    {

        if ($request->wantsJson()) {
            $banners = Banner::latest()->get();
            return $this->sendSuccess(200, BannerResource::collection($banners), "Banners fetched successfully");
        }

        return view('banners.index');
    }


    public function getBanners(Request $request)
    {
        if ($request->ajax()) {
            $data = Banner::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('image', function ($row) {
                    $imageUrl = $row->getFirstMediaUrl('banners') ?: asset('default-image.jpg');
                    return '<img src="' . $imageUrl . '" width="50" height="50" class="rounded">';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('banners.show', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                        . '<a href="' . route('banners.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action=" ' . route('banners.destroy', $row->id) . '" method="POST" class="d-inline">'
                        . csrf_field()
                        . method_field("DELETE")
                        . '<button type="submit" class="btn btn-sm btn-danger btn-delete" data-id="{{ $row->id }}">Delete</button>'
                        . '</form>';
                })
                ->rawColumns(['image', 'action'])
                ->escapeColumns([])
                ->make(true);
        }


        return abort(403);
    }


    public function create()
    {
        $articles = Article::all();
        return view('banners.banner-form', compact('articles'));
    }

    public function store(BannerRequest $request)
    {
        try {

            $request->validate([
                'image' => 'required',
            ]);

            $banner = Banner::create($request->validated());

            if ($request->hasFile('image')) {
                $banner->addMedia($request->file('image'))->toMediaCollection('banners');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(201, new BannerResource($banner), "Banner created successfully")
                : redirect()->route('banners.index')->with('success', 'Banner created successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, Banner $banner)
    {
        if ($request->wantsJson()) {
            return $this->sendSuccess(200, new BannerResource($banner), "Banner fetched successfully");
        }

        $viewMode = true;
        return view('banners.banner-form', compact('banner', 'viewMode'));
    }

    public function edit(Banner $banner)
    {
        $articles = Article::all();
        return view('banners.banner-form', compact('banner', 'articles'));
    }

    public function update(BannerRequest $request, Banner $banner)
    {

        try {

            $banner->update($request->validated());

            if ($request->hasFile('image')) {
                $banner->clearMediaCollection('banners');
                $banner->addMedia($request->file('image'))->toMediaCollection('banners');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(200, new BannerResource($banner), "Banner updated successfully")
                : redirect()->route('banners.index')->with('success', 'Banner updated successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, Banner $banner)
    {

        try {

            $banner->clearMediaCollection('banners');
            $banner->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Banner deleted successfully")
                : redirect()->route('banners.index')->with('success', 'Banner deleted successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->route('banners.index')->with('error', $e->getMessage());
        }
    }
}
