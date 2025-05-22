@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<h1 class="mb-4">Notifications</h1>
<hr>
<div class="card shadow mb-4 p-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h5>Daftar Notifikasi</h5>
            <button class="btn btn-sm btn-primary" id="markAllAsRead">Tandai Semua Dibaca</button>
        </div>
        <hr>

        <ul class="list-group">
            @foreach (Auth::user()->notifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-center
                        {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                <div>
                    <p class="mb-1">{{ $notification->data['message'] }}</p>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    <br>
                    <span
                        class="badge
                            {{ $notification->data['transaction_status'] == 'success' ? 'bg-success' : 'bg-warning' }}">
                        {{ ucfirst($notification->data['transaction_status']) }}
                    </span>
                </div>
                <div>
                    <a href="{{ route('transactions.show', $notification->data['transaction_id']) }}"
                        class="btn btn-sm btn-info">
                        Lihat Transaksi
                    </a>
                    @if (is_null($notification->read_at))
                    <button class="btn btn-sm btn-success mark-as-read" data-id="{{ $notification->id }}">
                        Tandai Dibaca
                    </button>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
    document.querySelectorAll('.mark-as-read').forEach(button => {
        button.addEventListener('click', function () {
            let id = this.getAttribute('data-id');
            fetch(`/notifications/${id}/mark-as-read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('li').classList.remove('bg-light');
                        this.remove();
                    }
                });
        });
    });

    document.getElementById('markAllAsRead').addEventListener('click', function () {
        fetch('/notifications/mark-all-as-read', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll('.list-group-item.bg-light').forEach(item => {
                        item.classList.remove('bg-light');
                        item.querySelector('.mark-as-read')?.remove();
                    });
                }
            });
    });
</script>
@endsection
