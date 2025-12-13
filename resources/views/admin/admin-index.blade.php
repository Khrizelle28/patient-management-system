@extends('admin.index')

@section('content')
<div class="card mt-4">
    <div class="card-header">
        <i class="fa-solid fa-users"></i>
        Employee Accounts
    </div>
    <div class="card-body">
        <a class="btn btn-primary"
           href="{{ route('admin.create') }}">New Employee</a>
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
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.edit', ['id' => $admin->id]) }}"
                                   class="btn btn-sm btn-primary"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="Edit Employee">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if(in_array('Doctor', $admin->role))
                                    <a href="{{ route('admin.schedule.edit', ['id' => $admin->id]) }}"
                                       class="btn btn-sm btn-info"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="Update Schedule">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </a>
                                @endif
                                <form action="{{ route('admin.deactivate', ['id' => $admin->id]) }}"
                                      method="POST"
                                      style="display: inline;"
                                      onsubmit="return confirm('Are you sure you want to {{ $admin->status === 'Active' ? 'deactivate' : 'activate' }} this employee?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                            class="btn btn-sm {{ $admin->status === 'Active' ? 'btn-warning' : 'btn-success' }}"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="{{ $admin->status === 'Active' ? 'Deactivate' : 'Activate' }} Employee">
                                        <i class="fa-solid {{ $admin->status === 'Active' ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
