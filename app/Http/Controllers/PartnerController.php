<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerRequest;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class PartnerController extends Controller
{
    public function index(Request $request)
    {

        if ($request->wantsJson()) {
            $partners = Partner::latest()->get();
            return $this->sendSuccess(200, PartnerResource::collection($partners), "Partners fetched successfully");
        }

        return view('partners.index');
    }


    public function getPartners(Request $request)
    {
        if ($request->ajax()) {
            $data = Partner::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('image', function ($row) {
                    $imageUrl = $row->getFirstMediaUrl('partners') ?: asset('default-image.jpg');
                    return '<img src="' . $imageUrl . '" width="50" height="50" class="rounded">';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('partners.show', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                        . '<a href="' . route('partners.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action=" ' . route('partners.destroy', $row->id) . '" method="POST" class="d-inline">'
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
        return view('partners.partner-form');
    }

    public function store(PartnerRequest $request)
    {
        try {

            $request->validate([
                'image' => 'required',
            ]);

            $partner = Partner::create($request->validated());

            if ($request->hasFile('image')) {
                $partner->addMedia($request->file('image'))->toMediaCollection('partners');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(201, new PartnerResource($partner), "Partner created successfully")
                : redirect()->route('partners.index')->with('success', 'Partner created successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, Partner $partner)
    {
        if ($request->wantsJson()) {
            return new PartnerResource($partner);
        }

        $viewMode = true;
        return view('partners.partner-form', compact('partner', 'viewMode'));
    }

    public function edit(Partner $partner)
    {
        return view('partners.partner-form', compact('partner'));
    }

    public function update(PartnerRequest $request, Partner $partner)
    {
        try {

            $partner->update($request->validated());

            if ($request->hasFile('image')) {
                $partner->clearMediaCollection('partners');
                $partner->addMedia($request->file('image'))->toMediaCollection('partners');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(200, new PartnerResource($partner), "Partner updated successfully")
                : redirect()->route('partners.index')->with('success', 'Partner updated successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, Partner $partner)
    {
        try {
            $partner->clearMediaCollection('partners');
            $partner->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Partner deleted successfully")
                : redirect()->route('partners.index')->with('success', 'Partner deleted successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }
}
