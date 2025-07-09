<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TeamController extends Controller
{
    public function index(Request $request)
    {

        if ($request->wantsJson()) {

            $teams = Team::orderBy('position_number', 'ASC')->get();
            return $this->sendSuccess(200, TeamResource::collection($teams), "Teams fetched successfully");
        }

        return view('teams.index');
    }


    public function getTeams(Request $request)
    {
        if ($request->ajax()) {
            $data = Team::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $imageUrl = $row->getFirstMediaUrl('teams') ?: asset('default-image.jpg');
                    return '<img src="' . $imageUrl . '" width="50" height="50" class="rounded">';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('teams.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action=" ' . route('teams.destroy', $row->id) . '" method="POST" class="d-inline">'
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
        return view('teams.form');
    }

    public function store(TeamRequest $request)
    {
        try {

            $team = Team::create($request->validated());

            if ($request->hasFile('image')) {
                $team->addMedia($request->file('image'))->toMediaCollection('teams');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(201, $team, "Team created successfully")
                : redirect()->route('teams.index')->with('success', 'Category created successfully');
        } catch (\Exception $e) {

            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, Team $team)
    {
        if ($request->wantsJson()) {
            return $this->sendSuccess(200, new TeamResource($team), "Team fetched successfully");
        }

        $viewMode = true;
        return view('teams.form', compact('team', 'viewMode'));
    }

    public function edit(Team $team)
    {
        return view('teams.form', compact('team'));
    }

    public function update(TeamRequest $request, Team $team)
    {
        try {

            $team->update($request->validated());

            if ($request->hasFile('image')) {
                $team->clearMediaCollection('teams');
                $team->addMedia($request->file('image'))->toMediaCollection('teams');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(200, $team, "Team updated successfully")
                : redirect()->route('teams.index')->with('success', 'Team updated successfully');
        } catch (\Exception $e) {
            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, Team $team)
    {

        try {
            $team->clearMediaCollection('teams');
            $team->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Team deleted successfully")
                : redirect()->route('teams.index')->with('success', 'Team deleted successfully');
        } catch (\Exception $e) {
            request()->wantsJson()
                ? $this->sendError(500, null, "Internal server error") : redirect()->back()->with('error', $e->getMessage());
        }
    }
}
