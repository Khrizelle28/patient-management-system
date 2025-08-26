    @extends('admin.index')

    @section('content')

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">New Patient Account</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('patient.store') }}">
                        @csrf
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
                                    <input class="form-control" id="inputMiddleName" name="middle_name" type="text" value="{{ old('middle_name') }}" placeholder="Enter your middle name" />
                                    <label for="inputMiddleName">Middle name</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control @error('last_name') is-invalid @enderror" id="inputLastName" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter your last name" />
                                    <label for="inputLastName">Last name <span style="color: red">*</span></label>
                                    @error('last_name')
                                        <small class="invalid-feedback">Please enter a Last name.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control @error('age') is-invalid @enderror"
                                        id="inputAge" type="text" name="age" value="{{ old('age') }}"
                                        placeholder="Enter your age" />
                                    <label for="inputAge">Age <span style="color: red">*</span></label>
                                    @error('age')
                                        <div class="invalid-feedback">
                                            Please input age.
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">

                                     <input class="form-control  @error('province') is-invalid @enderror" id="inputProvince" type="text" value="{{ old('province') }}" name="province" placeholder="Enter your province" />
                                    <label for="inputProvince">Province<span style="color: red">*</span></label>
                                    @error('province')
                                        <small class="invalid-feedback">Please input province.</small>
                                    @enderror


                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control  @error('city_municipality') is-invalid @enderror" id="inputCityMunicipality" type="text" value="{{ old('city_municipality') }}" name="city_municipality" placeholder="Enter your city/municipality" />
                                    <label for="inputCityMunicipality">City/Municipality<span style="color: red">*</span></label>
                                    @error('city_municipality')
                                        <small class="invalid-feedback">Please input city/municipality.</small>
                                    @enderror

                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control @error('barangay') is-invalid @enderror" id="inputBarangay" type="text" name="barangay" value="{{ old('barangay') }}" placeholder="Enter your barangay" />
                                    <label for="inputBarangay">Barangay<span style="color: red">*</span></label>
                                    @error('barangay')
                                        <small class="invalid-feedback">Please input barangay</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control @error('street') is-invalid @enderror" id="inputStreet" type="text" value="{{ old('street') }}" name="street" placeholder="Enter your street" />
                                    <label for="inputStreet">Street</label>
                                    @error('street')
                                        <small class="invalid-feedback">Please input street</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control  @error('occupation') is-invalid @enderror" id="inputOccupation" type="text" value="{{ old('occupation') }}" name="occupation" placeholder="Enter your occupation" />
                                    <label for="inputOccupation">Occupation<span style="color: red">*</span></label>
                                    @error('occupation')
                                        <small class="invalid-feedback">Please input occupation.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control  @error('contact_no') is-invalid @enderror" id="inputContactNo" type="text" value="{{ old('contact_no') }}" name="contact_no" placeholder="Enter your contact no" />
                                    <label for="inputContactNo">Contact No.<span style="color: red">*</span></label>
                                    @error('contact_no')
                                        <small class="invalid-feedback">Please input contact no.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control  @error('birthday') is-invalid @enderror" id="inputBirthday" type="date" value="{{ old('birthday') }}" name="birthday" placeholder="Enter your city/municipality" />
                                    <label for="inputBirthday">Birthday<span style="color: red">*</span></label>
                                    @error('birthday')
                                        <small class="invalid-feedback">Please input birthday.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control  @error('birthplace') is-invalid @enderror" id="inputBirthplace" type="text" value="{{ old('birthplace') }}" name="birthplace" placeholder="Enter your birthplace" />
                                    <label for="inputBirthplace">Birthplace<span style="color: red">*</span></label>
                                    @error('birthplace')
                                        <small class="invalid-feedback">Please input Birthplace.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <select name="civil_status" class="form-control  @error('civil_status') is-invalid @enderror" id="selectCivilStatus" >
                                        <option value selected disabled>Please select Civil Status </option>
                                        <option value="SINGLE" {{ old('civil_status') ==  'SINGLE' ? 'selected' : '' }}>Single</option>
                                        <option value="MARRIED" {{ old('civil_status') ==  'MARRIED' ? 'selected' : '' }} >Married</option>
                                        <option value="ANNULLED" {{ old('civil_status') ==  'ANNULLED' ? 'selected' : '' }} >Annulled</option>
                                        <option value="SEPARATED" {{ old('civil_status') ==  'SEPARATED' ? 'selected' : '' }}>Separated</option>
                                        <option value="WIDOWED" {{ old('civil_status') ==  'WIDOWED' ? 'selected' : '' }}>Widowed</option>
                                </select>
                                    <label for="selectCivilStatus">Civil Status<span style="color: red">*</span></label>
                                    @error('civil_status')
                                        <small class="invalid-feedback">Please Select Civil Status.</small>
                                    @enderror
                                </div>
                            </div>
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
