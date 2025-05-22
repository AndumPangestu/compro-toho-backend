<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleCategoryRequest;
use App\Http\Resources\ArticleCategoryResource;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ArticleCategoryController extends Controller
{
    public function index(Request $request)
    {

        if ($request->wantsJson()) {

            $categories = ArticleCategory::latest()->get();
            return $this->sendSuccess(200, ArticleCategoryResource::collection($categories), "Categories fetched successfully");
        }

        return view('article_categories.index');
    }


    public function getArticleCategories(Request $request)
    {
        if ($request->ajax()) {
            $data = ArticleCategory::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('article-categories.show', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                        . '<a href="' . route('article-categories.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action=" ' . route('article-categories.destroy', $row->id) . '" method="POST" class="d-inline">'
                        . csrf_field()
                        . method_field("DELETE")
                        . '<button type="submit" class="btn btn-sm btn-danger btn-delete" data-id="{{ $row->id }}">Delete</button>'
                        . '</form>';
                })
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }


        return abort(403);
    }

    public function create()
    {
        return view('article_categories.article-category-form');
    }

    public function store(ArticleCategoryRequest $request)
    {
        try {

            $category = ArticleCategory::create($request->validated());

            return $request->wantsJson()
                ? $this->sendSuccess(201, $category, "Article Category created successfully")
                : redirect()->route('article-categories.index')->with('success', 'Category created successfully');
        } catch (\Exception $e) {

            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, ArticleCategory $category)
    {
        if ($request->wantsJson()) {
            return $this->sendSuccess(200, new ArticleCategoryResource($category), "Article Category fetched successfully");
        }

        $viewMode = true;
        return view('article_categories.article-category-form', compact('category', 'viewMode'));
    }

    public function edit(ArticleCategory $category)
    {
        return view('article_categories.article-category-form', compact('category'));
    }

    public function update(ArticleCategoryRequest $request, ArticleCategory $category)
    {
        try {

            $category->update($request->validated());

            return $request->wantsJson()
                ? $this->sendSuccess(200, $category, "Category updated successfully")
                : redirect()->route('article-categories.index')->with('success', 'Article Category updated successfully');
        } catch (\Exception $e) {
            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, ArticleCategory $category)
    {

        try {

            $category->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Article Category deleted successfully")
                : redirect()->route('article-categories.index')->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }
}
