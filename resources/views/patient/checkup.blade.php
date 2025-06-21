@extends('admin.index')

@section('content')
    <div class="row justify-content-center mb-4">
        <div class="col-lg-7">
            <div class="card mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Add Checkup</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('patient.diagnosis', ['id' => $patient->id]) }}">
                        @csrf
                        @method('POST')
                        <div class="row mb-3">
                            <div class="col-md-4">
                               <label><strong>Full Name</strong></label>
                                <p>{{ $patient->full_name }}</p>
                            </div>
                            <div class="col-md-4">
                                <label><strong>Age</strong></label>
                                <p>{{ $patient->age }}</p>
                            </div>
                            <div class="col-md-4">
                                <label><strong>Civil Status</strong></label>
                                <p>{{ $patient->civil_status }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-8">
                               <label><strong>Full Address</strong></label>
                                <p>{{ $patient->full_address }}</p>
                            </div>
                            <div class="col-md-4">
                                <label><strong>Occupation</strong></label>
                                <p>{{ $patient->occupation }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                               <label><strong>Contact No.</strong></label>
                                <p>{{ $patient->full_address }}</p>
                            </div>
                            <div class="col-md-4">
                                <label><strong>Birthdate</strong></label>
                                <p>{{ $patient->occupation }}</p>
                            </div>
                            <div class="col-md-4">
                                <label><strong>Birthplace</strong></label>
                                <p >{{ $patient->occupation }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <h4 class="font-weight-light my-4">Checkup Details</h4>
                            <div class="col-md-12 mb-3">
                                <div class="form-floating mb-3 mb-md-0">
                                    <select name="doctor_id" id="selectDoctor" class="form-select @error('doctor_id') is-invalid @enderror">
                                        <option value selected disabled>Please select doctor</option>
                                        @foreach ($doctors as $key => $doctor)
                                            <option value="{{ $doctor->id }}" {{ auth()->user()->id == $doctor->id ? 'selected' : "" }}>{{ $doctor->full_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="selectDoctor">Doctors</label>
                                    @error('doctor_id')
                                        <small class="invalid-feedback">Please select doctors.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <textarea class="form-control @error('ob_score') is-invalid @enderror" id="inputOBScore" name="ob_score" style="height: 100px; resize: none;" placeholder="Enter your first name">{{ old('ob_score') }}</textarea>
                                    <label for="inputOBScore">OB Score</label>
                                    @error('ob_score')
                                        <small class="invalid-feedback">Please enter a OB Score.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control @error('gravida') is-invalid @enderror" id="inputGravida" name="gravida" type="text" value="{{ old('gravida') }}" placeholder="Enter your gravida" />
                                    <label for="inputGravida">Gravida</label>
                                    @error('gravida')
                                        <small class="invalid-feedback">Please enter a Gravida.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control @error('para') is-invalid @enderror" id="inputPara" name="para" type="text" value="{{ old('para') }}" placeholder="Enter your para" />
                                    <label for="inputPara">Para</label>
                                    @error('para')
                                        <small class="invalid-feedback">Please enter a Para.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input class="form-control @error('last_menstrual_period') is-invalid @enderror" id="inputLmp" name="last_menstrual_period" type="date" value="{{ old('last_menstrual_period') }}" placeholder="Enter your LMP" />
                                    <label for="inputLmp">LMP</label>
                                    @error('last_menstrual_period')
                                        <small class="invalid-feedback">Please enter LMP.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input class="form-control @error('blood_pressure') is-invalid @enderror" id="inputBp" name="blood_pressure" type="text" value="{{ old('blood_pressure') }}" placeholder="Enter your BP" />
                                    <label for="inputBp">BP</label>
                                    @error('blood_pressure')
                                        <small class="invalid-feedback">Please enter BP.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input class="form-control @error('weight') is-invalid @enderror" id="inputWt" name="weight" type="text" value="{{ old('weight') }}" placeholder="Enter your Weight" />
                                    <label for="inputWt">WT</label>
                                    @error('weight')
                                        <small class="invalid-feedback">Please enter Weight.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label><strong>Type</strong></label>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input type @error('type') is-invalid @enderror" type="radio" name="type" value="non-pregnant" id="radioNonPregnant" {{ old('type') == 'non-pregnant' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexRadioDefault1">
                                      Non pregnant
                                    </strong></label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input type @error('type') is-invalid @enderror" type="radio" name="type" value="pregnant" id="radioPregnant" {{ old('type') == 'pregnant' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexRadioDefault2">
                                      Pregnant
                                    </strong></label>
                                    @error('type')
                                        <small class="invalid-feedback">Please select type.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 pregnant-details" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input class="form-control @error('age_of_gestation') is-invalid @enderror" id="inputAog" name="age_of_gestation" type="text" value="{{ old('age_of_gestation') }}" placeholder="Enter your AOG" />
                                    <label for="inputAog">AOG</label>
                                    @error('age_of_gestation')
                                        <small class="invalid-feedback">Please enter AOG.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input class="form-control @error('fundal_height') is-invalid @enderror" id="inputFh" name="fundal_height" type="text" value="{{ old('fundal_height') }}" placeholder="Enter your FH" />
                                    <label for="inputFh">FH</label>
                                    @error('fundal_height')
                                        <small class="invalid-feedback">Please enter FH.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input class="form-control @error('fetal_heart_tone') is-invalid @enderror" id="inputFht" name="fetal_heart_tone" type="text" value="{{ old('fetal_heart_tone') }}" placeholder="Enter your FHT" />
                                    <label for="inputFht">FHT</label>
                                    @error('fetal_heart_tone')
                                        <small class="invalid-feedback">Please enter FHT.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input class="form-control @error('estimated_date_confinement') is-invalid @enderror" id="inputEdc" name="estimated_date_confinement" type="date" value="{{ old('estimated_date_confinement') }}" placeholder="Enter your EDC" />
                                    <label for="inputEdc">EDC</label>
                                    @error('estimated_date_confinement')
                                        <small class="invalid-feedback">Please enter EDC.</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <h5>FM_Hx</h5>
                                    <div class="row row-family_histories">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" name="family_histories[]" value="asthma" id="checkbox1" {{ in_array('asthma', old('family_histories', [])) ? 'checked' : '' }}>
                                              <label class="form-check-label" for="checkbox1">Asthma</label>
                                            </div>
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" name="family_histories[]" value="hpn" id="checkbox2" {{ in_array('hpn', old('family_histories', [])) ? 'checked' : '' }}>
                                              <label class="form-check-label" for="checkbox2">HPN</label>
                                            </div>
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" name="family_histories[]" value="diabetes" id="checkbox3" {{ in_array('diabetes', old('family_histories', [])) ? 'checked' : '' }}>
                                              <label class="form-check-label" for="checkbox3">Diabetes</label>
                                            </div>
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" name="family_histories[]" value="heart_disease" id="checkbox4" {{ in_array('heart_disease', old('family_histories', [])) ? 'checked' : '' }}>
                                              <label class="form-check-label" for="checkbox4">Heart Disease</label>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" name="family_histories[]" value="allergy" id="checkbox5" {{ in_array('allergy', old('family_histories', [])) ? 'checked' : '' }}>
                                              <label class="form-check-label" for="checkbox5">Allergy</label>
                                            </div>
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" name="family_histories[]" value="thyroid_problem_goiter" id="checkbox6" {{ in_array('thyroid_problem_goiter', old('family_histories', [])) ? 'checked' : '' }}>
                                              <label class="form-check-label" for="checkbox6">Thyroid Problem/Goiter</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input chkbox_others" type="checkbox" name="family_histories[]" value="others" id="checkbox6" {{ in_array('others', old('family_histories', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="checkbox6">Others, Specify</label>
                                              </div>
                                          </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3 other_row">
                                <div class="form-floating mb-3 mb-md-0">
                                    <textarea class="form-control @error('family_histories_other') is-invalid @enderror" id="inputFamilyHostories" name="family_histories_other" style="height: 100px; resize: none;" placeholder="Enter your first name">{{ old('family_histories_other') }}</textarea>
                                    <label for="inputFamilyHostories">Other Family Histories</label>
                                    @error('family_histories_other')
                                        <small class="invalid-feedback">Please input other Family Histories.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <textarea class="form-control @error('txtarea_remarks') is-invalid @enderror" id="inputTxtareaRemarks" value="{{ old('txtarea_remarks') }}" name="txtarea_remarks" style="height: 100px; resize: none;" type="text" placeholder="Enter your first name">{{ old('txtarea_remarks') ?? '' }}</textarea>
                                    <label for="inputTxtareaRemarks">Remarks</label>
                                    @error('txtarea_remarks')
                                        <small class="invalid-feedback">Please enter a Remarks.</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="toComeBack" name="to_come_back" {{ old('to_come_back') == true ? 'checked' : '' }}>
                            <label class="form-check-label" for="toComeBack">To Come Back</label>

                        </div>

                        <!-- Return Date Field -->
                        <div class="mb-3" id="returnDateField" style="display: none;">
                            <label for="returnDate" class="form-label">Expected Return Date</label>
                            <input type="datetime-local" class="form-control @error('return_date') is-invalid @enderror" id="returnDate" name="return_date" value="{{ old('return_date') }}">
                            @error('return_date')
                                <small class="invalid-feedback">Please enter return date.</small>
                            @enderror
                        </div>

                        <!-- Reason Textarea -->
                        <div class="mb-3" id="reasonField" style="display: none;">
                            <label for="noReturnReason" class="form-label">Reason for Not Coming Back</label>
                            <textarea class="form-control @error('no_return_reason') is-invalid @enderror" id="noReturnReason" name="no_return_reason" rows="3" style="resize: none;">{{ old('no_return_reason') ?? '' }}</textarea>
                            @error('no_return_reason')
                                <small class="invalid-feedback">Please enter no return reason.</small>
                            @enderror
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
@push('scripts')
    <script src="{{ asset('js/diagnosis.js') }}"></script>
@endpush
