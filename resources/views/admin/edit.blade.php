@extends('admin.index')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Create Admin Account</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <select name="role" id="selectRole" class="form-control @error('role') is-invalid @enderror">
                                        <option value selected disabled>Please select role</option>
                                        @foreach ($role_datas as $key => $role_data)
                                            <option value="{{ $role_data['name'] }}" {{ in_array($role_data['name'], $user->getRoleNames()->toArray()) ? 'selected' : '' }}>{{ $role_data['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <label for="selectRole">Role</label>
                                    @error('role')
                                        <small class="invalid-feedback">Please select a role.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control @error('first_name') is-invalid @enderror" id="inputFirstName" value="{{ $user->first_name }}" name="first_name" type="text" placeholder="Enter your first name" />
                                    <label for="inputFirstName">First name</label>
                                    @error('first_name')
                                        <small class="invalid-feedback">Please enter a First name.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control" id="inputMiddleName" name="middle_name" type="text" value="{{ $user->middle_name }}" placeholder="Enter your middle name" />
                                    <label for="inputMiddleName">Middle name</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control @error('last_name') is-invalid @enderror" id="inputLastName" type="text" value="{{ $user->last_name }}" name="last_name" placeholder="Enter your last name" />
                                    <label for="inputLastName">Last name</label>
                                    @error('last_name')
                                        <small class="invalid-feedback">Please enter a Last name.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control" id="inputSuffix" type="text" name="suffix" placeholder="Enter your suffix" value="{{ $user->suffix }}" />
                                    <label for="inputSuffix">Suffix</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control @error('license_no') is-invalid @enderror" id="inputLicenseNo" type="text" name="license_no" value="{{ $user->license_no }}" placeholder="Enter your license number" />
                                    <label for="inputLicenseNo">License No</label>
                                    @error('license_no')
                                        <small class="invalid-feedback">Please enter a License No.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control @error('ptr_no') is-invalid @enderror" id="inputPtrNo" type="text" name="ptr_no" value="{{ $user->ptr_no }}" placeholder="Enter your PTR number" />
                                    <label for="inputPtrNo">PTR No.</label>
                                    @error('ptr_no')
                                        <small class="invalid-feedback">Please enter a PTR No.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control  @error('email') is-invalid @enderror" id="inputEmail" type="text" name="email" value="{{ $user->email }}" placeholder="name@example.com" />
                            <label for="inputEmail">Email address</label>
                            @error('email')
                                <small class="invalid-feedback">Please enter a email.</small>
                            @enderror
                        </div>
                        <div class="mt-4 mb-0">
                            <div class="d-grid"><button class="btn btn-primary btn-block" type="submit">Edit Account</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection