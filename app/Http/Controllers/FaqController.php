<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class FaqController extends Controller
{
    public function index(Request $request)
    {

        if ($request->wantsJson()) {

            $faqs = Faq::latest()->get();
            return $this->sendSuccess(200, FaqResource::collection($faqs), "FAQs fetched successfully");
        }

        return view('faqs.index');
    }


    public function getFaqs(Request $request)
    {
        if ($request->ajax()) {
            $data = Faq::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('faqs.show', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                        . '<a href="' . route('faqs.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action=" ' . route('faqs.destroy', $row->id) . '" method="POST" class="d-inline">'
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
        return view('faqs.faq-form');
    }

    public function store(FaqRequest $request)
    {
        try {

            $faq = Faq::create($request->validated());

            return $request->wantsJson()
                ? $this->sendSuccess(201, new FaqResource($faq), "FAQ created successfully")
                : redirect()->route('faqs.index')->with('success', 'FAQ created successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, Faq $faq)
    {
        if ($request->wantsJson()) {
            return $this->sendSuccess(200, new FaqResource($faq), "FAQ fetched successfully");
        }

        $viewMode = true;
        return view('faqs.faq-form', compact('faq', 'viewMode'));
    }

    public function edit(Faq $faq)
    {
        return view('faqs.faq-form', compact('faq'));
    }

    public function update(FaqRequest $request, Faq $faq)
    {
        try {

            $faq->update($request->validated());

            return $request->wantsJson()
                ? $this->sendSuccess(200, new FaqResource($faq), "FAQ updated successfully")
                : redirect()->route('faqs.index')->with('success', 'FAQ updated successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function destroy(Request $request, Faq $faq)
    {
        try {

            $faq->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "FAQ deleted successfully")
                : redirect()->route('faqs.index')->with('success', 'FAQ deleted successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }
}
