@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<h1>User Management</h1>
<hr>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add User
            </a>
        </div>
        <div class="table-responsive">
            <table id="userTable" class="table table-hover w-100 p-3">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            lengthMenu: [5, 10, 25, 50],
            pagingType: "simple_numbers",
            ajax: {
                url: "{{ route('users.data') }}",
                data: function (d) {
                    d.role = '{{ $role }}';
                }
            },
            columns: [
                { data: 'id', name: 'id', orderable: true, searchable: false },
                { data: 'name', name: 'name', orderable: true },
                { data: 'email', name: 'email', orderable: true },
                { data: 'phone', name: 'phone', orderable: true, searchable: false },
                { data: 'role', name: 'role', orderable: true, searchable: false },
                { data: 'created_at', name: 'created_at', orderable: true, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[5, 'desc']]
        });
    });
</script>

<script>
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var userId = $(this).data('id');
        var form = $(this).closest('form');

        Swal.fire({
            title: 'Are you sure you want to delete this user?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
