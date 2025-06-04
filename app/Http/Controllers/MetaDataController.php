<?php

namespace App\Http\Controllers;

use App\Models\MetaData;
use Illuminate\Http\Request;
use App\Http\Requests\JapanProfileRequest;
use App\Http\Requests\IndonesiaProfileRequest;
use App\Http\Resources\IndonesiaProfileResource;
use App\Http\Resources\JapanProfileResource;

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

        if ($request->routeIs('profiles.indonesia.index')) {

            $profile = MetaData::where('type', 'indonesia_profile')->first();

            if ($profile) {

                $profileData = json_decode($profile->data);

                return view('profiles.indonesia.form', compact('profile', 'profileData'));
            }

            return view('profiles.indonesia.form');
        } else if ($request->routeIs('profiles.japan.index')) {

            $profile = MetaData::where('type', 'japan_profile')->first();

            if ($profile) {

                $profileData = json_decode($profile->data);

                return view('profiles.japan.form', compact('profile', 'profileData'));
            }

            return view('profiles.japan.form');
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeIndonesiaProfile(IndonesiaProfileRequest $request)
    {
        $profile = [
            'name' => $request->name,
            'description' => $request->description
        ];

        $createdProfile = MetaData::updateOrCreate(
            ['type' => 'indonesia_profile'],
            ['data' => json_encode($profile, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)]
        );

        if ($request->hasFile('image')) {
            $createdProfile->clearMediaCollection('indonesia_profile');
            $createdProfile->addMedia($request->file('image'))->toMediaCollection('indonesia_profile');
        }


        return redirect()->back()->with('success', 'Profile updated successfully');
    }


    public function storeJapanProfile(JapanProfileRequest $request)
    {
        $profile = $request->validated();
        unset($profile['image']);
        unset($profile['image_url']);


        $createdProfile = MetaData::updateOrCreate(
            ['type' => 'japan_profile'],
            ['data' => json_encode($profile, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)]
        );

        if ($request->hasFile('image')) {
            $createdProfile->clearMediaCollection('japan_profiles');
            foreach ($request->file('image') as $image) {
                $createdProfile->addMedia($image)->toMediaCollection('japan_profiles');
            }
        }


        return redirect()->back()->with('success', 'Profile updated successfully');
    }


    public function getProfile($type)
    {
        $profileType = $type . "_profile"; // gunakan titik (.) untuk string concat
        $profile = MetaData::where('type', $profileType)->first();

        if (!$profile || !$profile->data) {
            return $this->sendError(404, "Profile not found");
        }

        $decodedData = json_decode($profile->data);

        // Tambahan validasi untuk memastikan data bisa didecode
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->sendError(500, "Invalid JSON in profile data");
        }

        if ($type === "indonesia") {
            $decodedData->image_url = $profile->getFirstMediaUrl('indonesia_profile');
        } else {
            $decodedData->image_urls = $profile->getMedia('japan_profiles')->map(function ($media) {
                return $media->getUrl();
            });
        }

        $data = $type === "indonesia"
            ? new IndonesiaProfileResource($decodedData)
            : new JapanProfileResource($decodedData);

        return $this->sendSuccess(200, $data, "Profile fetched successfully");
    }





    /**
     * Display the specified resource.
     */
    // public function show(MetaData $metaData)
    // {
    //     //
    // }


}
