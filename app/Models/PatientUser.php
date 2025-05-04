<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PatientUser extends User
{
    use HasApiTokens; 

    protected $guarded = ['id'];

    public function getFullNameAttribute()
    {
        $name_keys = [
            'first_name',
            'middle_name',
            'last_name',
            'name_ext'
        ];

        $name = [];
        foreach ($name_keys as $key) {
            $name_val = trim($this->{$key} ?? null);
            if ($name_val !== null && $name_val !== '' && strlen($name_val) !== 0) {

                if ($key === 'middle_name' && (str_contains($name_val, '.') === false || (str_contains($name_val, '.') && strlen($name_val) !== 0))) {
                    try {
                        $name_val_arr = explode(' ', $name_val);
                        foreach ($name_val_arr as $i => $val) {
                            $name_val_arr[$i] = $val[0] ?? '';
                        }

                        $name_val = implode('', $name_val_arr);

                        if ($name_val == null) {
                            continue;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }

                    $name_val = "{$name_val}.";
                }

                $name[]   = $name_val;
            }
        }

        return implode(' ', $name);
    }

    public function getFullAddressAttribute(): string{
        $house_street = "";

        if ($this->street != null) {
            $house_street .= $this->street . ", ";
        }

        return $house_street . "$this->barangay, $this->province, $this->city_municipality";
    }
}
