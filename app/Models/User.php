<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute()
    {
        $name_keys = [
            'first_name',
            'middle_name',
            'last_name',
            'name_ext',
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

                $name[] = $name_val;
            }
        }

        return implode(' ', $name);
    }

    public function getRoleAttribute()
    {
        return $this->roles->pluck('name')->toArray();
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
