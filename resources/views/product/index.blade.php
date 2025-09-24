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
                        <th>Expiration Date</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Expiration Date</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    {{-- {{dd($products)}} --}}
                    @forelse($products ?? [] as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->price ?? 'N/A' }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ Carbon\Carbon::parse($product->expiration_date)->format('F d, Y') }}</td>
                            <td><img src="{{ $product->image }}" height="50" width="50" alt=""></td>
                            <td>
                                <div class="kebab-menu">
                                    <div class="kebab-icon">⋮</div>
                                        <div class="menu-options">
                                            {{-- <a href="{{ route('product.show', ['id' => $product->id]) }}">View</a> --}}
                                            <a href="{{ route('product.add-stock', ['id' => $product->id]) }}">Add Stock</a>
                                            {{-- <a href="{{ route('product.edit', ['id' => $product->id]) }}">Edit</a> --}}
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