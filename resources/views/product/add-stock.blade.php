@extends('admin.index')

@section('content')
    <div class="row justify-content-center mb-4">
        <div class="col-lg-7">
            <div class="card mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Add Stock</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('product.update-stock', ['id' => $product->id]) }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                               <label><strong>Product Name</strong></label>
                                <p>{{ $product->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Description</strong></label>
                                <p>{{ $product->description }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                               <label><strong>Price</strong></label>
                                <p>{{ $product->price }}</p>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Stock</strong></label>
                                <p>{{ $product->stock }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input class="form-control @error('stock') is-invalid @enderror" id="inputStock" name="stock" type="number" value="{{ old('stock') }}" placeholder="Enter your stock" />
                                    <label for="inputStock">Stock</label>
                                    @error('stock')
                                        <small class="invalid-feedback">Please enter stock.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 mb-0">
                            <div class="d-grid"><button class="btn btn-primary btn-block" type="submit">Submit</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection