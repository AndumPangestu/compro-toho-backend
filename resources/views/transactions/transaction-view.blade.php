@extends('layouts.app')

@section('title', 'Transaction Detail')

@section('content')
    <h1 class="mb-4">Transaction Detail</h1>
    <hr>
    <div class="card shadow mb-4 p-4">
        <div class="card-body">
            <h5 class="text-primary font-weight-bold">Transaction ID: {{ $transaction->id }}</h5>

            <div class="mt-3 p-3 bg-light rounded">
                <h5>Donation</h5>
                <p><a href="{{ route('donations.show', $transaction->donation_id) }}"
                        class="text-decoration-none">{{ $transaction->donation->title ?? 'N/A' }}</a></p>
                <h5>User</h5>
                @if ($transaction->user_id)
                    <p>
                        <a href="{{ route('users.show', $transaction->user_id) }}" class="text-decoration-none">
                            {{ $transaction->user->name ?? 'Anonymous' }}
                        </a><br>
                        <small>{{ $transaction->user->email ?? '-' }}</small>
                    </p>
                @elseif ($transaction->anonymous_donor_id)
                    <p>
                        <span class="fw-bold">{{ $transaction->anonymousDonor->name ?? 'Anonymous' }}</span><br>
                        <small>{{ $transaction->anonymousDonor->email ?? '-' }}</small>
                    </p>
                @else
                    <p>Anonymous</p>
                @endif



            </div>

            <div class="mt-3 p-3 bg-light rounded">
                <h5>Amount</h5>
                <p class="text-success font-weight-bold">Rp {{ number_format($transaction->amount, 2, ',', '.') }}</p>
            </div>

            <div class="mt-3">
                <h5>Transaction Status</h5>
                <span
                    class="p-2 badge
                @if ($transaction->transaction_status == 'success' || $transaction->transaction_status == 'capture') bg-success
                @elseif($transaction->transaction_status == 'pending') bg-warning
                @else bg-danger @endif">
                    {{ ucfirst($transaction->transaction_status) }}
                </span>
            </div>

            <div class="mt-4 text-muted">
                <p><small>Created at: {{ $transaction->created_at }}</small></p>
                <p><small>Updated at: {{ $transaction->updated_at }}</small></p>
            </div>

            <a href="{{ route('transactions.index') }}" class="btn btn-secondary mt-3">Back</a>
        </div>
    </div>
@endsection
