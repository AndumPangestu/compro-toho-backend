<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\Donation;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Team;
use Illuminate\Http\Request;
use Carbon\Carbon;


class MainController extends Controller
{
    public function index()
    {
        $totalArticles = Article::count();
        $totalTeams  = Team::count();
        $totalPartners = Partner::count();
        $totalServices = Service::count();
        return view('dashboard', compact(
            'totalServices',
            'totalArticles',
            'totalTeams',
            'totalPartners'
        ));
    }
}
