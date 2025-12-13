# Appointment Reminder Email

This email template is used to send appointment reminders to patients.

## Usage Example

```php
use App\Mail\AppointmentReminderMail;
use Illuminate\Support\Facades\Mail;

// Prepare the data
$reminderData = [
    'patient_name' => 'John Doe',
    'doctor_name' => 'Dr. Jane Smith',
    'appointment_date' => 'Monday, December 15, 2025',
    'appointment_time' => '10:00 AM',
    'service_type' => 'General Checkup',
    'confirm_url' => route('appointments.confirm', ['id' => $appointmentId]),
    'reschedule_url' => route('appointments.reschedule', ['id' => $appointmentId]),
    'clinic_phone' => '(032) 123-4567',
    'clinic_email' => 'info@tejeromedical.com',
    'clinic_address' => 'Tejero St, Cebu City, Philippines',
];

// Send the email
Mail::to($patient->email)->send(new AppointmentReminderMail($reminderData));
```

## Required Data Fields

- `patient_name` - Full name of the patient
- `doctor_name` - Name of the assigned doctor
- `appointment_date` - Formatted date string
- `appointment_time` - Formatted time string
- `service_type` - Type of service (e.g., Consultation, Prenatal Checkup, etc.)

## Optional Data Fields

- `confirm_url` - URL to confirm appointment (defaults to '#')
- `reschedule_url` - URL to reschedule appointment (defaults to '#')
- `clinic_phone` - Clinic contact phone (defaults to '(123) 456-7890')
- `clinic_email` - Clinic contact email (defaults to 'info@tejeromedical.com')
- `clinic_address` - Clinic address (defaults to 'Tejero Medical and Maternity Clinic')

## Features

- Modern gradient design with purple theme
- Responsive layout for mobile devices
- Interactive appointment details card
- Action buttons for confirming and rescheduling
- Contact information section
- Professional footer with clinic branding

## Automated Sending

You can set up a scheduled task to send reminder emails automatically:

```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule): void
{
    // Send reminder emails 24 hours before appointments
    $schedule->call(function () {
        $tomorrow = now()->addDay()->format('Y-m-d');

        $appointments = Appointment::where('appointment_date', $tomorrow)
            ->where('status', 'scheduled')
            ->with(['patient', 'doctor'])
            ->get();

        foreach ($appointments as $appointment) {
            // Prepare and send reminder email
            // ... implementation here
        }
    })->daily();
}
```