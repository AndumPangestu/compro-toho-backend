<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestimonialRequest;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;


class TestimonialController extends Controller
{
    public function index(Request $request)
    {

        if ($request->wantsJson()) {
            $testimonials = Testimonial::latest()->get();
            return $this->sendSuccess(200, TestimonialResource::collection($testimonials), "Testimonials fetched successfully");
        }

        return view('testimonials.index');
    }

    public function getTestimonials(Request $request)
    {
        if ($request->ajax()) {
            $data = Testimonial::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('testimonials.show', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                        . '<a href="' . route('testimonials.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action=" ' . route('testimonials.destroy', $row->id) . '" method="POST" class="d-inline">'
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
        return view('testimonials.testimonial-form');
    }

    public function store(TestimonialRequest $request)
    {

        try {

            $testimonial = Testimonial::create($request->validated());

            if ($request->hasFile('image')) {
                $testimonial->addMedia($request->file('image'))->toMediaCollection('testimonials');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(201, new TestimonialResource($testimonial), "Testimonial created successfully")
                : redirect()->route('testimonials.index')->with('success', 'Testimonial created successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, Testimonial $testimonial)
    {
        if ($request->wantsJson()) {
            return $this->sendSuccess(200, new TestimonialResource($testimonial), "Testimonial fetched successfully");
        }

        $viewMode = true;
        return view('testimonials.testimonial-form', compact('testimonial', 'viewMode'));
    }

    public function edit(Testimonial $testimonial)
    {
        return view('testimonials.testimonial-form', compact('testimonial'));
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial)
    {
        try {


            $testimonial->update($request->validated());

            if ($request->hasFile('image')) {
                $testimonial->clearMediaCollection('images');
                $testimonial->addMedia($request->file('image'))->toMediaCollection('images');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(200, new TestimonialResource($testimonial), "Testimonial updated successfully")
                : redirect()->route('testimonials.index')->with('success', 'Testimonial updated successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, Testimonial $testimonial)
    {
        try {

            $testimonial->clearMediaCollection('images');
            $testimonial->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Testimonial deleted successfully")
                : redirect()->route('testimonials.index')->with('success', 'Testimonial deleted successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }
}
