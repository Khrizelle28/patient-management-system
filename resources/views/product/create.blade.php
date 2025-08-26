@extends('admin.index')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">New Product</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control @error('name') is-invalid @enderror" id="inputProductName" value="{{ old('name') }}" name="name" type="text" placeholder="Enter your product name" />
                                    <label for="inputPRoductName">Product name <span style="color: red">*</span></label>
                                    @error('name')
                                        <small class="invalid-feedback">Please enter product name.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">                        
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="inputDescription" name="description" style="height: 100px; resize: none;" placeholder="Enter your description">{{ old('description') }}</textarea>
                                    <label for="inputDescription">Description</label>
                                    @error('description')
                                        <small class="invalid-feedback">Please enter description.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control @error('price') is-invalid @enderror" id="inputPrice" type="text" name="price" value="{{ old('price') }}" placeholder="Enter your price" />
                                    <label for="inputPrice">Price <span style="color: red">*</span></label>
                                    @error('price')
                                        <small class="invalid-feedback">Please enter price.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control @error('stock') is-invalid @enderror"
                                        id="inputStock" type="number" name="stock" value="{{ old('stock') }}"
                                        placeholder="Enter your stock" />
                                    <label for="inputStock">Stock <span style="color: red">*</span></label>
                                    @error('stock')
                                        <div class="invalid-feedback">
                                            Please input stock.
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <img id="imagePreview" width="200" src="{{ asset('image/profile-pic.png') }}" height="200" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; margin-top: 10px; margin-bottom: 10px; border-radius: 5px;">
                        <div class="form-floating mb-3">
                            <input type="file" name="image" id="imageInput" class="form-control @error('image') is-invalid @enderror" accept="image/*" style="">
                            <label for="imageInput">Image</label>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-4 mb-0">
                            <div class="d-grid"><button class="btn btn-primary btn-block" type="submit">Create Product</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection