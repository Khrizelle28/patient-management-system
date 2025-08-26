@extends('admin.index')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <i class="fa-solid fa-book"></i>
            Product Records
        </div>
        <div class="card-body">
            <a class="btn btn-primary" href="{{ route('product.create') }}">New Product</a>
            <table id="datatablesSimple" class="tableProduct">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Image</th>
                    </tr>
                </tfoot>
                <tbody>
                    @forelse($products ?? [] as $patient)
                        <tr onclick="window.location='{{ route('patient.checkup', ['id' => $patient->id]) }}'" style="cursor:pointer;">
                            <td>{{ $patient->full_name }}</td>
                            <td>{{ $patient->age ?? 'N/A' }}</td>
                            <td>{{ $patient->civil_status ?? 'N/A' }}</td>
                            <td>{{ $patient->full_address }}</td>
                            <td>{{ $patient->occupation }}</td>
                            <td>{{ $patient->contact_no }}</td>
                            <td>{{ $patient->birthday }}</td>
                            <td>
                                <div class="kebab-menu">
                                    <div class="kebab-icon">⋮</div>
                                        <div class="menu-options">
                                            <a href="{{ route('patient.show', ['id' => $patient->id]) }}">View</a>
                                            <a href="{{ route('patient.checkup', ['id' => $patient->id]) }}">Add Checkup</a>
                                            <a href="{{ route('patient.edit', ['id' => $patient->id]) }}">Edit</a>
                                            <a href="{{ route('admin.create') }}">Deactivate</a>
                                            <a href="{{ route('admin.create') }}">Delete</a>
                                    </div>
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