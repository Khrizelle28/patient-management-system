@extends('admin.index')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Create Admin Account</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <select name="role" id="selectRole" class="form-control @error('role') is-invalid @enderror">
                                        <option value selected disabled>Please select role </option>
                                        @foreach ($role_datas as $key => $role_data)
                                            <option value="{{ $role_data['name'] }}">{{ $role_data['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <label for="selectRole">Role <span style="color: red">*</span></label>
                                    @error('role')
                                        <small class="invalid-feedback">Please select a role. </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control @error('first_name') is-invalid @enderror" id="inputFirstName" value="{{ old('first_name') }}" name="first_name" type="text" placeholder="Enter your first name" />
                                    <label for="inputFirstName">First name <span style="color: red">*</span></label>
                                    @error('first_name')
                                        <small class="invalid-feedback">Please enter a First name.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control" id="inputMiddleName" value="{{ old('middle_name') }}"name="middle_name" type="text" placeholder="Enter your middle name " />
                                    {{-- <input class="form-control" id="inputMiddleName" name="middle_name" type="text" placeholder="Enter your middle name" /> --}}
                                    <label for="inputMiddleName">Middle name</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control @error('last_name') is-invalid @enderror" id="inputLastName" value="{{ old('last_name') }}" type="text" name="last_name" placeholder="Enter your last name" />
                                    <label for="inputLastName">Last name <span style="color: red">*</span></label>
                                    @error('last_name')
                                        <small class="invalid-feedback">Please enter a Last name.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control" id="inputSuffix" value="{{ old('suffix') }}" type="text" name="suffix" placeholder="Enter your suffix" />
                                    <label for="inputSuffix">Suffix</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control @error('license_no') is-invalid @enderror" id="inputLicenseNo" value="{{ old('license_no') }}" type="text" name="license_no" placeholder="Enter your license number" />
                                    <label for="inputLicenseNo">License No <span style="color: red">*</span></label>
                                    @error('license_no')
                                        <small class="invalid-feedback">Please enter a License No.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control @error('ptr_no') is-invalid @enderror" id="inputPtrNo" type="text" name="ptr_no" placeholder="Enter your PTR number" />
                                    <label for="inputPtrNo">PTR No. <span style="color: red">*</span></label>
                                    @error('ptr_no')
                                        <small class="invalid-feedback">Please enter a PTR No.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control  @error('email') is-invalid @enderror" id="inputEmail" type="email" value="{{ old('email') }}" name="email" placeholder="" />
                                    <label for="inputEmail">Email address <span style="color: red">*</span></label>
                                    @error('email')
                                        <small class="invalid-feedback">Please enter a email.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <select name="sex" id="selectSex" class="form-control @error('sex') is-invalid @enderror">
                                        <option value selected disabled>Please select sex </option>
                                        <option value="MALE">Male</option>
                                        <option value="FEMALE">Female</option>
                                    </select>
                                    <label for="selectSex">Sex <span style="color: red">*</span></label>
                                    @error('sex')
                                        <small class="invalid-feedback">Please select sex. </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 schedule--container">
                            <h3>Schedule</h3>
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input day-toggle" type="checkbox" id="mondaySwitch" value="monday" name="schedule[monday]" data-day="monday">
                                        <label class="form-check-label" for="mondaySwitch">Monday</label>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" class="form-control monday-field" name="schedule[monday][time_in]" disabled>
                                    </div>
                                    <div>
                                        <label class="form-label">End Time</label>
                                        <input type="time" class="form-control monday-field" name="schedule[monday][time_out]" disabled>
                                    </div>
                                </div>
                            </div>
                            <!-- Tuesday -->
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input day-toggle" type="checkbox" id="tuesdaySwitch" name="schedule[tuesday]" data-day="tuesday">
                                    <label class="form-check-label" for="tuesdaySwitch">Tuesday</label>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" class="form-control tuesday-field" name="schedule[tuesday][time_in]" disabled>
                                </div>
                                <div>
                                    <label class="form-label">End Time</label>
                                    <input type="time" class="form-control tuesday-field" name="schedule[tuesday][time_out]" disabled>
                                </div>
                                </div>
                            </div>

                            <!-- Wednesday -->
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input day-toggle" type="checkbox" id="wednesdaySwitch" name="schedule[wednesday]" data-day="wednesday">
                                    <label class="form-check-label" for="wednesdaySwitch">Wednesday</label>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" class="form-control wednesday-field" name="schedule[wednesday][time_in]" disabled>
                                </div>
                                <div>
                                    <label class="form-label">End Time</label>
                                    <input type="time" class="form-control wednesday-field" name="schedule[wednesday][time_out]" disabled>
                                </div>
                                </div>
                            </div>

                            <!-- Thursday -->
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input day-toggle" type="checkbox" id="thursdaySwitch" name="schedule[thursday]" data-day="thursday">
                                    <label class="form-check-label" for="thursdaySwitch">Thursday</label>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" class="form-control thursday-field" name="schedule[thursday][time_in]" disabled>
                                </div>
                                <div>
                                    <label class="form-label">End Time</label>
                                    <input type="time" class="form-control thursday-field" name="schedule[thursday][time_out]" disabled>
                                </div>
                                </div>
                            </div>

                            <!-- Friday -->
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input day-toggle" type="checkbox" id="fridaySwitch" name="schedule[friday]" data-day="friday">
                                    <label class="form-check-label" for="fridaySwitch">Friday</label>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" class="form-control friday-field" name="schedule[friday][time_in]" disabled>
                                </div>
                                <div>
                                    <label class="form-label">End Time</label>
                                    <input type="time" class="form-control friday-field" name="schedule[friday][time_out]" disabled>
                                </div>
                                </div>
                            </div>

                            <!-- Saturday -->
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input day-toggle" type="checkbox" id="saturdaySwitch" name="schedule[saturday]" data-day="saturday">
                                    <label class="form-check-label" for="saturdaySwitch">Saturday</label>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" class="form-control saturday-field" name="schedule[saturday][time_in]" disabled>
                                </div>
                                <div>
                                    <label class="form-label">End Time</label>
                                    <input type="time" class="form-control saturday-field" name="schedule[saturday][time_out]" disabled>
                                </div>
                                </div>
                            </div>
                        </div>
                        <img id="imagePreview" width="200" src="{{ asset('image/profile-pic.png') }}" height="200" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; margin-top: 10px; margin-bottom: 10px; border-radius: 5px;">
                        <div class="form-floating mb-3">
                            <input type="file" name="profile_pic" id="imageInput" class="form-control @error('profile_pic') is-invalid @enderror" accept="image/*" style="">
                            <label for="imageInput">Profile Picture</label>
                            @error('profile_pic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-4 mb-0">
                            <div class="d-grid"><button class="btn btn-primary btn-block" type="submit">Create Account</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
