@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <div class="row">
        <x-card title="Total Services" value="{{ number_format($totalServices) }}" icon="fas fa-briefcase" color="primary" />
        <x-card title="Total Articles" value="{{ number_format($totalArticles) }}" icon="fas fa-newspaper" color="success" />
        <x-card title="Total Teams" value="{{ number_format($totalTeams) }}" icon="fas fa-user-group" color="warning" />
        <x-card title="Total Partners" value="{{ number_format($totalPartners) }}" icon="fas fa-handshake-simple"
            color="danger" />
    </div>

@endsection
