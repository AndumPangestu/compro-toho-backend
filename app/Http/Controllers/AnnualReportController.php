<?php

namespace App\Http\Controllers;

use App\Models\AnnualReport;
use App\Http\Requests\AnnualReportRequest;
use App\Http\Resources\AnnualReportResource;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AnnualReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $reports = AnnualReport::latest()->get();
            return $this->sendSuccess(200, AnnualReportResource::collection($reports), "Annual reports fetched successfully");
        }

        return view('annual_reports.index');
    }

    public function getReports(Request $request)
    {
        if ($request->ajax()) {
            $data = AnnualReport::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('file', function ($row) {
                    $url = $row->getFirstMediaUrl("annual_reports");
                    return '<a href="' . $url . '" class="btn btn-success" target="_blank" rel="noopener noreferrer">View</a>';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('annual-reports.show', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                        . '<a href="' . route('annual-reports.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action="' . route('annual-reports.destroy', $row->id) . '" method="POST" class="d-inline">'
                        . csrf_field()
                        . method_field("DELETE")
                        . '<button type="submit" class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '">Delete</button>'
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
        return view('annual_reports.annual-report-form');
    }

    public function store(AnnualReportRequest $request)
    {
        try {
            $report = AnnualReport::create($request->validated());

            if ($request->hasFile('file')) {

                $fileName = str_replace(' ', '-', $request->title);
                $report->addMedia($request->file('file'))
                    ->usingFileName($fileName . '.' . $request->file('file')->getClientOriginalExtension())
                    ->toMediaCollection('annual_reports');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(201, new AnnualReportResource($report), "Annual report created successfully")
                : redirect()->route('annual-reports.index')->with('success', 'Annual report created successfully');
        } catch (\Exception $e) {
            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, AnnualReport $report)
    {
        if ($request->wantsJson()) {
            return $this->sendSuccess(200, new AnnualReportResource($report), "Annual report fetched successfully");
        }

        $viewMode = true;
        return view('annual_reports.annual-report-form', compact('report', 'viewMode'));
    }

    public function edit(AnnualReport $report)
    {

        return view('annual_reports.annual-report-form', compact('report'));
    }

    public function update(AnnualReportRequest $request, AnnualReport $report)
    {
        try {
            $report->update($request->validated());

            if ($request->hasFile('file')) {
                $report->clearMediaCollection('annual_reports');
                $fileName = str_replace(' ', '-', $request->title);
                $report->addMedia($request->file('file'))
                    ->usingFileName($fileName . '.' . $request->file('file')->getClientOriginalExtension())
                    ->toMediaCollection('annual_reports');
            }
            return $request->wantsJson()
                ? $this->sendSuccess(200, new AnnualReportResource($report), "Annual report updated successfully")
                : redirect()->route('annual-reports.index')->with('success', 'Annual report updated successfully');
        } catch (\Exception $e) {
            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, AnnualReport $report)
    {
        try {
            $report->clearMediaCollection('annual_reports');
            $report->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Annual report deleted successfully")
                : redirect()->route('annual-reports.index')->with('success', 'Annual report deleted successfully');
        } catch (\Exception $e) {
            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->route('annual-reports.index')->with('error', $e->getMessage());
        }
    }

    public function getAnnualReport()
    {
        $report = AnnualReport::where('year', date('Y'))->first();

        if (!$report) {
            $report = AnnualReport::latest()->first();
        }
        return $this->sendSuccess(200, new AnnualReportResource($report), "Annual report fetched successfully");
    }
}
