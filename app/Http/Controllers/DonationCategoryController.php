<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationCategoryRequest;
use App\Http\Resources\DonationCategoryResource;
use App\Models\DonationCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class DonationCategoryController extends Controller
{
    public function index(Request $request)
    {

        if ($request->wantsJson()) {

            $categories = DonationCategory::latest()->get();
            return $this->sendSuccess(200, DonationCategoryResource::collection($categories), "Categories fetched successfully");
        }

        return view('donation_categories.index');
    }

    public function getDonationCategories(Request $request)
    {
        if ($request->ajax()) {
            $data = DonationCategory::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('donation-categories.show', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                        . '<a href="' . route('donation-categories.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action=" ' . route('donation-categories.destroy', $row->id) . '" method="POST" class="d-inline">'
                        . csrf_field()
                        . method_field("DELETE")
                        . '<button type="submit" class="btn btn-sm btn-danger btn-delete" data-id="{{ $row->id }}">Delete</button>'
                        . '</form>';
                })
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        abort(403);
    }
    public function create()
    {
        return view('donation_categories.donation-category-form');
    }

    public function store(DonationCategoryRequest $request)
    {
        try {

            $category = DonationCategory::create($request->validated());

            return $request->wantsJson()
                ? $this->sendSuccess(201, new DonationCategoryResource($category), "Category created successfully")
                : redirect()->route('donation-categories.index')->with('success', 'Category created successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, $e->getMessage())
                : redirect()->route('donation-categories.index')->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, DonationCategory $category)
    {
        if ($request->wantsJson()) {
            return $this->sendSuccess(200, new DonationCategoryResource($category), "Category fetched successfully");
        }

        $viewMode = true;
        return view('donation_categories.donation-category-form', compact('category', 'viewMode'));
    }

    public function edit(DonationCategory $category)
    {
        return view('donation_categories.donation-category-form', compact('category'));
    }

    public function update(DonationCategoryRequest $request, DonationCategory $category)
    {
        try {

            $category->update($request->validated());

            return $request->wantsJson()
                ? $this->sendSuccess(200, new DonationCategoryResource($category), "Category updated successfully")
                : redirect()->route('donation-categories.index')->with('success', 'Category updated successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, $e->getMessage())
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, DonationCategory $category)
    {
        try {

            $category->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Category deleted successfully")
                : redirect()->route('donation-categories.index')->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, $e->getMessage())
                : redirect()->back()->with('error', $e->getMessage());
        }
    }
}
