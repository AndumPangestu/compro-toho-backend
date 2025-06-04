<?php

namespace App\Http\Controllers;

use App\Models\MetaData;
use Illuminate\Http\Request;

class MetaDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $teams = MetaData::latest()->get();
            return $this->sendSuccess(200, MetaData::collection($teams), "Teams fetched successfully");
        }

        if ($request->routeIs('profile.indonesia.index')) {
        } else if ($request->routeIs('profile.japan.index')) {
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MetaData $metaData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MetaData $metaData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MetaData $metaData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MetaData $metaData)
    {
        //
    }
}
