@extends('admin.index')

@section('content')
<div class="card mt-4">
    <div class="card-header">
        <i class="fa-solid fa-users"></i>
        Employee Accounts
    </div>
    <div class="card-body">
        <a class="btn btn-primary" href="{{ route('admin.create') }}">New Employee</a>
        <table id="datatablesSimple" class="tableAdmin">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>License No.</th>
                    <th>PTR No.</th>
                    <th>Email</th>
                    <th>Position</th>
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
                    <th>Position</th>
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
                        <td>{{ implode(', ', $admin->role) }}</td>
                        <td>{{ $admin->status }}</td>
                        <td>
                            {{-- <a class="btn btn-primary" href="{{ route('admin.edit', ['id' => $admin->id]) }}">Edit</a>  --}}
                            {{-- <a class="btn btn-primary" href="{{ route('admin.create') }}">Deactivate</a> --}}
                            {{-- <a class="btn btn-primary" href="{{ route('admin.create') }}">Delete</a> --}}
                            <div class="kebab-menu">
                                <div class="kebab-icon">⋮</div>
                                    <div class="menu-options">
                                        <a href="{{ route('admin.edit', ['id' => $admin->id]) }}">Edit</a>
                                        <a href="#">Deactivate</a>
                                        @if(in_array('Doctor', $admin->role))
                                            <a href="{{ route('admin.schedule.edit', ['id' => $admin->id]) }}">Update Schedule</a>
                                        @endif
                                </div>
                            </div>


                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table?
@endsection
