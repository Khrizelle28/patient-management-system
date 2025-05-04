@extends('admin.index')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Add Checkup</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('patient.update', ['id' => $patient->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-md-4">
                               <label>Full Name</label>
                                <h4>{{ $patient->full_name }}</h4>
                            </div>
                            <div class="col-md-4">
                                <label>Age</label>
                                <h4>{{ $patient->age }}</h4>
                            </div>
                            <div class="col-md-4">
                                <label>Civil Status</label>
                                <h4>{{ $patient->civil_status }}</h4>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-8">
                               <label>Full Address</label>
                                <h4>{{ $patient->full_address }}</h4>
                            </div>
                            <div class="col-md-4">
                                <label>Occupation</label>
                                <h4>{{ $patient->occupation }}</h4>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection