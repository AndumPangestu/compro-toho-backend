<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Donation;
use Illuminate\Http\Request;
use Carbon\Carbon;


class MainController extends Controller
{
    public function index()

    {
        $totalUsers = User::where('role', 'user')->count();
        $totalDonations = Donation::sum('collected_amount');

        $activeCampaigns = Donation::whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now())
            ->count();

        $totalDonationThisMonth = Donation::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('collected_amount');

        $donationTrends = Donation::selectRaw('MONTH(created_at) as month, SUM(collected_amount) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();;

        $categoryDonations = Donation::join('donation_categories', 'donations.category_id', '=', 'donation_categories.id')
            ->selectRaw('donation_categories.name as category, SUM(donations.collected_amount) as total')
            ->groupBy('donation_categories.name')
            ->pluck('total', 'category')
            ->take(5)
            ->toArray();

        $topDonationsThisMonth = Donation::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->selectRaw('title, SUM(collected_amount) as total')
            ->groupBy('title')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalUsers',
            'totalDonations',
            'activeCampaigns',
            'totalDonationThisMonth',
            'donationTrends',
            'categoryDonations',
            'topDonationsThisMonth'
        ));
    }
}
