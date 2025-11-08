@extends('admin.index')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Update Doctor Schedule - {{ $user->full_name }}</h3></div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.schedule.update', ['id' => $user->id]) }}">
                        @csrf
                        @method('PUT')

                        <h3>Schedule</h3>
                        <!-- Row 1: Monday & Tuesday -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input day-toggle" type="checkbox" id="mondaySwitch" value="monday" name="schedule[monday]" data-day="monday" {{ isset($schedules['monday']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="mondaySwitch">Monday</label>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" class="form-control monday-field" name="schedule[monday][time_in]" value="{{ $schedules['monday']->start_time ?? '' }}" {{ isset($schedules['monday']) ? '' : 'disabled' }}>
                                    </div>
                                    <div>
                                        <label class="form-label">End Time</label>
                                        <input type="time" class="form-control monday-field" name="schedule[monday][time_out]" value="{{ $schedules['monday']->end_time ?? '' }}" {{ isset($schedules['monday']) ? '' : 'disabled' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input day-toggle" type="checkbox" id="tuesdaySwitch" name="schedule[tuesday]" data-day="tuesday" {{ isset($schedules['tuesday']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tuesdaySwitch">Tuesday</label>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" class="form-control tuesday-field" name="schedule[tuesday][time_in]" value="{{ $schedules['tuesday']->start_time ?? '' }}" {{ isset($schedules['tuesday']) ? '' : 'disabled' }}>
                                    </div>
                                    <div>
                                        <label class="form-label">End Time</label>
                                        <input type="time" class="form-control tuesday-field" name="schedule[tuesday][time_out]" value="{{ $schedules['tuesday']->end_time ?? '' }}" {{ isset($schedules['tuesday']) ? '' : 'disabled' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Wednesday & Thursday -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input day-toggle" type="checkbox" id="wednesdaySwitch" name="schedule[wednesday]" data-day="wednesday" {{ isset($schedules['wednesday']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="wednesdaySwitch">Wednesday</label>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" class="form-control wednesday-field" name="schedule[wednesday][time_in]" value="{{ $schedules['wednesday']->start_time ?? '' }}" {{ isset($schedules['wednesday']) ? '' : 'disabled' }}>
                                    </div>
                                    <div>
                                        <label class="form-label">End Time</label>
                                        <input type="time" class="form-control wednesday-field" name="schedule[wednesday][time_out]" value="{{ $schedules['wednesday']->end_time ?? '' }}" {{ isset($schedules['wednesday']) ? '' : 'disabled' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input day-toggle" type="checkbox" id="thursdaySwitch" name="schedule[thursday]" data-day="thursday" {{ isset($schedules['thursday']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="thursdaySwitch">Thursday</label>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" class="form-control thursday-field" name="schedule[thursday][time_in]" value="{{ $schedules['thursday']->start_time ?? '' }}" {{ isset($schedules['thursday']) ? '' : 'disabled' }}>
                                    </div>
                                    <div>
                                        <label class="form-label">End Time</label>
                                        <input type="time" class="form-control thursday-field" name="schedule[thursday][time_out]" value="{{ $schedules['thursday']->end_time ?? '' }}" {{ isset($schedules['thursday']) ? '' : 'disabled' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: Friday & Saturday -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input day-toggle" type="checkbox" id="fridaySwitch" name="schedule[friday]" data-day="friday" {{ isset($schedules['friday']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fridaySwitch">Friday</label>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" class="form-control friday-field" name="schedule[friday][time_in]" value="{{ $schedules['friday']->start_time ?? '' }}" {{ isset($schedules['friday']) ? '' : 'disabled' }}>
                                    </div>
                                    <div>
                                        <label class="form-label">End Time</label>
                                        <input type="time" class="form-control friday-field" name="schedule[friday][time_out]" value="{{ $schedules['friday']->end_time ?? '' }}" {{ isset($schedules['friday']) ? '' : 'disabled' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input day-toggle" type="checkbox" id="saturdaySwitch" name="schedule[saturday]" data-day="saturday" {{ isset($schedules['saturday']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="saturdaySwitch">Saturday</label>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" class="form-control saturday-field" name="schedule[saturday][time_in]" value="{{ $schedules['saturday']->start_time ?? '' }}" {{ isset($schedules['saturday']) ? '' : 'disabled' }}>
                                    </div>
                                    <div>
                                        <label class="form-label">End Time</label>
                                        <input type="time" class="form-control saturday-field" name="schedule[saturday][time_out]" value="{{ $schedules['saturday']->end_time ?? '' }}" {{ isset($schedules['saturday']) ? '' : 'disabled' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 mb-0">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="submit">Update Schedule</button>
                                <a href="{{ route('admin.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Schedule toggle functionality
            document.querySelectorAll('.day-toggle').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const day = this.getAttribute('data-day');
                    const fields = document.querySelectorAll('.' + day + '-field');

                    fields.forEach(function(field) {
                        field.disabled = !checkbox.checked;
                        if (!checkbox.checked) {
                            field.value = '';
                        }
                    });
                });
            });
        });
    </script>
    @endpush
@endsection
