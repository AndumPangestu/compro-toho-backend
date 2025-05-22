@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <div class="row">
        <x-card title="Total Users" value="{{ number_format($totalUsers) }}" icon="fas fa-users" color="primary" />
        <x-card title="Total Donations" value="Rp {{ number_format($totalDonations) }}" icon="fas fa-dollar-sign"
            color="success" />
        <x-card title="Active Campaigns" value="{{ number_format($activeCampaigns) }}" icon="fas fa-hand-holding-heart"
            color="warning" />
        <x-card title="Donations This Month" value="Rp {{ number_format($totalDonationThisMonth) }}" icon="fas fa-wallet"
            color="danger" />
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Donation Trends</h6>
                </div>
                <div class="card-body">
                    <canvas id="donationChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Total Donations by Category</h6>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Donation This Month</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($topDonationsThisMonth as $donation)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $donation->title }}
                                <span class="badge badge-success">Rp {{ number_format($donation->total) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Donation Trends Chart
            var donationTrendsCtx = document.getElementById('donationChart').getContext('2d');
            var donationTrendsChart = new Chart(donationTrendsCtx, {
                type: 'line',
                data: {
                    labels: @json(array_keys($donationTrends ?? [])),
                    datasets: [{
                        label: 'Total Donations',
                        data: @json(array_values($donationTrends ?? [])),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Donations by Category Chart
            var categoryChartCtx = document.getElementById('categoryChart').getContext('2d');
            var categoryChart = new Chart(categoryChartCtx, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($categoryDonations ?? [])),
                    datasets: [{
                        data: @json(array_values($categoryDonations ?? [])),
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>

@endsection
