<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfficeLocationRequest;
use App\Models\OfficeLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OfficeLocationController extends Controller
{
    public function index(Request $request)
    {

        if ($request->wantsJson()) {

            $officeLocations = OfficeLocation::latest()->get();
            return $this->sendSuccess(200, OfficeLocation::collection($officeLocations), "Office Location fetched successfully");
        }

        return view('office-locations.index');
    }


    public function getOfficeLocations(Request $request)
    {
        if ($request->ajax()) {
            $data = OfficeLocation::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('icon', function ($row) {
                    if ($row->icon()) {
                        return '<img src="' . $row->icon() . '" alt="Image" class="img-fluid" style="width: 100px; height: 100px;">';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('office-locations.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action=" ' . route('office-locations.destroy', $row->id) . '" method="POST" class="d-inline">'
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
        return view('office-locations.form');
    }

    public function store(OfficeLocationRequest $request)
    {
        try {

            $officeLocation = OfficeLocation::create($request->validated());
            if ($request->hasFile('icon')) {
                $icon = $request->file('icon');
                $iconName = time() . '.' . $icon->getClientOriginalExtension();
                $icon->move(public_path('uploads/office-locations'), $iconName);
                $officeLocation->icon = $iconName;
                $officeLocation->save();
            }
            return $request->wantsJson()
                ? $this->sendSuccess(201, $officeLocation, "Office Location created successfully")
                : redirect()->route('office-locations.index')->with('success', 'Office Location created successfully');
        } catch (\Exception $e) {

            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }

    // public function show(Request $request, $id)
    // {
    //     $officeLocation = OfficeLocation::findOrFail($id);
    //     if ($request->wantsJson()) {
    //         return $this->sendSuccess(200, new OfficeLocation($id), "Office Location fetched successfully");
    //     }
    //     $viewMode = true;
    //     return view('office-locations.form', compact('service', 'viewMode'));
    // }

    public function edit($id)
    {
        $officeLocation = OfficeLocation::findOrFail($id);
        return view('office-locations.form', compact('officeLocation'));
    }

    public function update(OfficeLocationRequest $request, $id)
    {
        try {
            $officeLocation = OfficeLocation::findOrFail($id);
            $officeLocation->update($request->validated());
            if ($request->hasFile('icon')) {
                if ($officeLocation->icon) {
                    unlink(public_path('uploads/office-locations/' . $officeLocation->icon));
                }
                $icon = $request->file('icon');
                $iconName = time() . '.' . $icon->getClientOriginalExtension();
                $icon->move(public_path('uploads/office-locations'), $iconName);
                $officeLocation->icon = $iconName;
                $officeLocation->save();
            }

            return $request->wantsJson()
                ? $this->sendSuccess(200, $officeLocation, "Office Location updated successfully")
                : redirect()->route('office-locations.index')->with('success', 'Office Location updated successfully');
        } catch (\Exception $e) {
            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $officeLocation = OfficeLocation::findOrFail($id);
            // if ($officeLocation->icon) {
            //     unlink(public_path('uploads/office-locations/' . $officeLocation->icon));
            // }
            $officeLocation->delete();
            // dd($request->wantsJson());
            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Office Location deleted successfully")
                : redirect()->route('office-locations.index')->with('success', 'Office Location deleted successfully');
        } catch (\Exception $e) {
            // dd($e->getMessage());

            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }
}
