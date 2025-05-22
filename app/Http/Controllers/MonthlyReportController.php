<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyReport;
use App\Http\Requests\MonthlyReportRequest;
use App\Http\Resources\MonthlyReportResource;
use App\Models\DonationCategory;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class MonthlyReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $reports = MonthlyReport::latest()->get();
            return $this->sendSuccess(200, MonthlyReportResource::collection($reports), "Monthly reports fetched successfully");
        }

        return view('monthly_reports.index');
    }

    public function getReports(Request $request)
    {
        if ($request->ajax()) {
            $data = MonthlyReport::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($donation) {
                    return $donation->category ? $donation->category->name : '-';
                })
                ->addColumn('file', function ($row) {
                    $url = $row->getFirstMediaUrl("monthly_reports");
                    return '<a href="' . $url . '" class="btn btn-success" target="_blank" rel="noopener noreferrer">View</a>';
                })
                ->editColumn('total_expenses', function ($row) {
                    return number_format($row->total_expenses, 0, ',', '.');
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('monthly-reports.show', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                        . '<a href="' . route('monthly-reports.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action="' . route('monthly-reports.destroy', $row->id) . '" method="POST" class="d-inline">'
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
        $categories = DonationCategory::all();
        return view('monthly_reports.monthly-report-form', compact('categories'));
    }

    public function store(MonthlyReportRequest $request)
    {
        try {
            $report = MonthlyReport::create($request->validated());

            if ($request->hasFile('file')) {

                $fileName = str_replace(' ', '-', $request->title);
                $report->addMedia($request->file('file'))
                    ->usingFileName($fileName . '.' . $request->file('file')->getClientOriginalExtension())
                    ->toMediaCollection('monthly_reports');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(201, new MonthlyReportResource($report), "Monthly report created successfully")
                : redirect()->route('monthly-reports.index')->with('success', 'Monthly report created successfully');
        } catch (\Exception $e) {
            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, MonthlyReport $report)
    {
        if ($request->wantsJson()) {
            return $this->sendSuccess(200, new MonthlyReportResource($report), "Monthly report fetched successfully");
        }

        $viewMode = true;
        return view('monthly_reports.monthly-report-form', compact('report', 'viewMode'));
    }

    public function edit(MonthlyReport $report)
    {
        $categories = DonationCategory::all();
        return view('monthly_reports.monthly-report-form', compact('report', 'categories'));
    }

    public function update(MonthlyReportRequest $request, MonthlyReport $report)
    {
        try {
            $report->update($request->validated());

            if ($request->hasFile('file')) {
                $report->clearMediaCollection('monthly_reports');
                $fileName = str_replace(' ', '-', $request->title);
                $report->addMedia($request->file('file'))
                    ->usingFileName($fileName . '.' . $request->file('file')->getClientOriginalExtension())
                    ->toMediaCollection('monthly_reports');
            }
            return $request->wantsJson()
                ? $this->sendSuccess(200, new MonthlyReportResource($report), "Monthly report updated successfully")
                : redirect()->route('monthly-reports.index')->with('success', 'Monthly report updated successfully');
        } catch (\Exception $e) {
            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, MonthlyReport $report)
    {
        try {
            $report->clearMediaCollection('monthly_reports');
            $report->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Monthly report deleted successfully")
                : redirect()->route('monthly-reports.index')->with('success', 'Monthly report deleted successfully');
        } catch (\Exception $e) {
            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->route('monthly-reports.index')->with('error', $e->getMessage());
        }
    }

    public function getMonthlyReports()
    {
        $report = MonthlyReport::selectRaw('month, SUM(total_expenses) as total_expenses')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderByRaw("FIELD(month, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->get();

        if (!$report) {
            return $this->sendSuccess(200, null, "No report found");
        }
        return $this->sendSuccess(200, MonthlyReportResource::collection($report), "Monthly report fetched successfully");
    }
}
