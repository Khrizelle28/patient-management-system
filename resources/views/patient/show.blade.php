@extends('admin.index')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Patient Details</h3></div>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="card mt-4">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Diagnosis Details</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('patient.diagnosis', ['id' => $patient->id]) }}">
                        @csrf
                        @method('POST')
                        @php
                            $lastDiagnosis = $patient->diagnosis->last();
                            $doctor        = $lastDiagnosis->doctor;
                        @endphp
                        <div class="row mb-3">
                            <div class="col-md-12">
                               <label><strong>Doctor</strong></label>
                                <p>{{ $doctor->full_name }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                               <label><strong>OB Score</strong></label>
                                <p>{{ $lastDiagnosis->ob_score }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                               <label><strong>Gravida</strong></label>
                                <p>{{ $lastDiagnosis->gravida }}</p>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Para</strong></label>
                                <p>{{ $lastDiagnosis->para }}</p>
                            </div>
                            <div class="col-md-6">
                                <label><strong>LMP</strong></label>
                                <p>{{ $lastDiagnosis->last_menstrual_period }}</p>
                            </div>
                            <div class="col-md-6">
                                <label><strong>BP</strong></label>
                                <p>{{ $lastDiagnosis->blood_pressure }}</p>
                            </div>
                            <div class="col-md-6">
                                <label><strong>WT</strong></label>
                                <p>{{ $lastDiagnosis->weight }}</p>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Type</strong></label>
                                <p>{{ $lastDiagnosis->type }}</p>
                            </div>
                        </div>
                    </form>
                    <table id="datatablesSimple" class="tablePatient">
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th>AOG</th>
                                <th>FH</th>
                                <th>FHT</th>
                                <th colspan="2">Remarks</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>DATE</th>
                                <th>AOG</th>
                                <th>FH</th>
                                <th>FHT</th>
                                <th colspan="2">Remarks</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @forelse($patient->diagnosis as $diagnosis)
                            <td>{{ $diagnosis->created_at }}</td>
                            <td>{{ $diagnosis->age_of_gestation }}</td>
                            <td>{{ $diagnosis->fundal_height }}</td>
                            <td>{{ $diagnosis->fetal_heart_tone }}</td>
                            <td colspan="2">{{ implode(', ', json_decode($diagnosis->remarks)) }}</td>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection