@extends('admin.index')

@section('content')
<div class="card mt-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Employee Accounts
    </div>
    <div class="card-body">
        <a class="btn btn-primary" href="{{ route('admin.create') }}">New Admin</a>
        <table id="datatablesSimple" class="tableAdmin">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>License No.</th>
                    <th>PTR No.</th>
                    <th>Email</th>
                    <th>Start date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>License No.</th>
                    <th>PTR No.</th>
                    <th>Email</th>
                    <th>Start date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td>{{ $admin->full_name }}</td>
                        <td>{{ $admin->license_no ?? 'N/A' }}</td>
                        <td>{{ $admin->ptr_no ?? 'N/A' }}</td>
                        <td>{{ $admin->email }}</td>
                        <td class="test123213">{{ implode(', ', $admin->role) }}</td>
                        <td class="test123213">{{ $admin->status }}</td>
                        <td style="test123213">
                            <a class="btn btn-primary" href="{{ route('admin.edit', ['id' => $admin->id]) }}">Edit</a>
                            <a class="btn btn-primary" href="{{ route('admin.create') }}">Deactivate</a>
                            <a class="btn btn-primary" href="{{ route('admin.create') }}">Delete</a>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table?
@endsection