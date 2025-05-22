<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\ReportResource;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $report = Report::first();

        if ($request->wantsJson()) {

            if (!$report) {
                return $this->sendError(200, null, "Report not found");
            }

            return $this->sendSuccess(200, new ReportResource($report), "Report created successfully");
        }

        return view('reports.index', compact('report'));
    }

    public function store(ReportRequest $request)
    {
        $report = Report::create($request->validated());

        return redirect()->route('reports.index')
            ->with('success',  ' Report created successfully.');
    }

    public function update(ReportRequest $request, Report $report)
    {
        $report->update($request->validated());

        return redirect()->route('reports.index')
            ->with('success', ' Report updated successfully.');
    }
}
