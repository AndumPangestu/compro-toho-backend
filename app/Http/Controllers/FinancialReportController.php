<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MonthlyReport;
use App\Models\FinancialReport;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\FinancialReportRequest;
use App\Http\Resources\FinancialReportResource;
use App\Http\Resources\MonthlyReportByCategoryResource;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $reports = FinancialReport::latest()->get();
            return $this->sendSuccess(200, FinancialReportResource::collection($reports), "Financial reports fetched successfully");
        }

        return view('financial_reports.index');
    }

    public function getReports(Request $request)
    {
        if ($request->ajax()) {
            $data = FinancialReport::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('file', function ($row) {
                    $url = $row->getFirstMediaUrl("financial_reports");
                    return '<a href="' . $url . '" class="btn btn-success" target="_blank" rel="noopener noreferrer">View</a>';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('financial-reports.show', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                        . '<a href="' . route('financial-reports.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action="' . route('financial-reports.destroy', $row->id) . '" method="POST" class="d-inline">'
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
        return view('financial_reports.financial-report-form');
    }

    public function store(FinancialReportRequest $request)
    {
        try {
            $report = FinancialReport::create($request->validated());

            if ($request->hasFile('file')) {

                $fileName = str_replace(' ', '-', $request->title);
                $report->addMedia($request->file('file'))
                    ->usingFileName($fileName . '.' . $request->file('file')->getClientOriginalExtension())
                    ->toMediaCollection('financial_reports');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(201, new FinancialReportResource($report), "Financial report created successfully")
                : redirect()->route('financial-reports.index')->with('success', 'Financial report created successfully');
        } catch (\Exception $e) {
            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, FinancialReport $report)
    {
        if ($request->wantsJson()) {
            return $this->sendSuccess(200, new FinancialReportResource($report), "Financial report fetched successfully");
        }

        $viewMode = true;
        return view('financial_reports.financial-report-form', compact('report', 'viewMode'));
    }

    public function edit(FinancialReport $report)
    {

        return view('financial_reports.financial-report-form', compact('report'));
    }

    public function update(FinancialReportRequest $request, FinancialReport $report)
    {
        try {
            $report->update($request->validated());

            if ($request->hasFile('file')) {
                $report->clearMediaCollection('financial_reports');
                $fileName = str_replace(' ', '-', $request->title);
                $report->addMedia($request->file('file'))
                    ->usingFileName($fileName . '.' . $request->file('file')->getClientOriginalExtension())
                    ->toMediaCollection('financial_reports');
            }
            return $request->wantsJson()
                ? $this->sendSuccess(200, new FinancialReportResource($report), "Financial report updated successfully")
                : redirect()->route('financial-reports.index')->with('success', 'Financial report updated successfully');
        } catch (\Exception $e) {
            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, FinancialReport $report)
    {
        try {
            $report->clearMediaCollection('financial_reports');
            $report->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Financial report deleted successfully")
                : redirect()->route('financial-reports.index')->with('success', 'Financial report deleted successfully');
        } catch (\Exception $e) {
            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->route('financial-reports.index')->with('error', $e->getMessage());
        }
    }

    public function getFinancialReport()
    {
        try {
            $report = FinancialReport::where('year', date('Y'))->first();
            if (!$report) {
                $report = FinancialReport::orderBy('year', 'desc')->first();
            }


            if ($report) {
                $monthlyReport = MonthlyReport::selectRaw('category_id, SUM(total_expenses) as total_expenses')
                    ->whereYear('created_at', $report->year)
                    ->groupBy('category_id')
                    ->orderBy('total_expenses', 'desc')
                    ->get();
            } else {
                $monthlyReport = collect();
            }

            $data['financial_reports'] = $report ? new FinancialReportResource($report) : null;
            $data['reports_by_category'] = MonthlyReportByCategoryResource::collection($monthlyReport);

            return $this->sendSuccess(200, $data, "Financial report fetched successfully");
        } catch (\Exception $e) {
            return $this->sendError(500, null, "Internal server error");
        }
    }
}
