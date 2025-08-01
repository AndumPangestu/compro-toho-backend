<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    public function index(Request $request)
    {

        if ($request->wantsJson()) {

            $services = Service::latest()->get();
            return $this->sendSuccess(200, ServiceResource::collection($services), "services fetched successfully");
        }

        return view('services.index');
    }


    public function getServices(Request $request)
    {
        if ($request->ajax()) {
            $data = Service::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $imageUrl = $row->getFirstMediaUrl('services') ?: asset('default-image.jpg');
                    return '<img src="' . $imageUrl . '" width="50" height="50" class="rounded">';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('services.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action=" ' . route('services.destroy', $row->id) . '" method="POST" class="d-inline">'
                        . csrf_field()
                        . method_field("DELETE")
                        . '<button type="submit" class="btn btn-sm btn-danger btn-delete" data-id="{{ $row->id }}">Delete</button>'
                        . '</form>';
                })
                ->rawColumns(['action', 'image'])
                ->escapeColumns([])
                ->make(true);
        }


        return abort(403);
    }

    public function create()
    {
        return view('services.form');
    }

    public function store(ServiceRequest $request)
    {
        try {

            $request->validate([
                'image' => 'required',
            ]);

            $service = Service::create($request->validated());

            if ($request->hasFile('image')) {
                $service->addMedia($request->file('image'))->toMediaCollection('services');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(201, $service, "Service created successfully")
                : redirect()->route('services.index')->with('success', 'Service created successfully');
        } catch (\Exception $e) {

            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, Service $service)
    {
        if ($request->wantsJson()) {
            return $this->sendSuccess(200, new ServiceResource($service), "Service fetched successfully");
        }

        $viewMode = true;
        return view('services.form', compact('service', 'viewMode'));
    }

    public function edit(Service $service)
    {
        return view('services.form', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        try {

            $service->update($request->validated());

            if ($request->hasFile('image')) {
                $service->clearMediaCollection('services');
                $service->addMedia($request->file('image'))->toMediaCollection('services');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(200, $service, "Service updated successfully")
                : redirect()->route('services.index')->with('success', 'Service updated successfully');
        } catch (\Exception $e) {
            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, Service $service)
    {

        try {
            $service->clearMediaCollection('services');
            $service->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Service deleted successfully")
                : redirect()->route('services.index')->with('success', 'Service deleted successfully');
        } catch (\Exception $e) {
            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }
}
