<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialMediaRequest;
use App\Http\Resources\SocialMediaResource;
use App\Models\SocialMedia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SocialMediaController extends Controller
{
    public function index(Request $request)
    {

        if ($request->wantsJson()) {
            $socialMedias = SocialMedia::latest()->get();
            return $this->sendSuccess(200, SocialMediaResource::collection($socialMedias), "Social Media fetched successfully");
        }

        return view('social-media.index');
    }


    public function getSocialMedia(Request $request)
    {
        if ($request->ajax()) {
            $data = SocialMedia::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $imageUrl = $row->getFirstMediaUrl('social_media') ?: asset('default-image.jpg');
                    return '<img src="' . $imageUrl . '" width="50" height="50" class="rounded">';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('social-media.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action=" ' . route('social-media.destroy', $row->id) . '" method="POST" class="d-inline">'
                        . csrf_field()
                        . method_field("DELETE")
                        . '<button type="submit" class="btn btn-sm btn-danger btn-delete" data-id="{{ $row->id }}">Delete</button>'
                        . '</form>';
                })
                ->rawColumns(['action', 'icon'])
                ->escapeColumns([])
                ->make(true);
        }


        return abort(403);
    }

    public function create()
    {
        return view('social-media.form');
    }

    public function store(SocialMediaRequest $request)
    {
        try {

            $request->validate([
                'image' => 'required',
            ]);

            $socialMedia = SocialMedia::create($request->validated());

            if ($request->hasFile('image')) {
                $socialMedia->addMedia($request->file('image'))->toMediaCollection('social_media');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(201, $socialMedia, "Social Media created successfully")
                : redirect()->route('social-media.index')->with('success', 'Social Media created successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());
            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        $socialMedia = SocialMedia::findOrFail($id);
        if ($request->wantsJson()) {
            return $this->sendSuccess(200, new socialMedia($id), "Social Media fetched successfully");
        }
        $viewMode = true;
        return view('social-media.form', compact('service', 'viewMode'));
    }

    public function edit($id)
    {
        $socialMedia = SocialMedia::findOrFail($id);
        return view('social-media.form', compact('socialMedia'));
    }

    public function update(SocialMediaRequest $request, $id)
    {
        try {
            $socialMedia = SocialMedia::findOrFail($id);
            $socialMedia->update($request->validated());

            if ($request->hasFile('image')) {
                $socialMedia->clearMediaCollection('social_media');
                $socialMedia->addMedia($request->file('image'))->toMediaCollection('social_media');
            }
            return $request->wantsJson()
                ? $this->sendSuccess(200, $socialMedia, "Social Media updated successfully")
                : redirect()->route('social-media.index')->with('success', 'Social Media updated successfully');
        } catch (\Exception $e) {
            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $socialMedia = SocialMedia::findOrFail($id);

            $socialMedia->clearMediaCollection('social_media');
            $socialMedia->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Social Media deleted successfully")
                : redirect()->route('social-media.index')->with('success', 'Social Media deleted successfully');
        } catch (\Exception $e) {

            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }
}
