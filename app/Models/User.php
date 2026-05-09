<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'middle_initial',
        'email',
        'password',
        'plain_password',
        'division',
        'department',
        'position',
        'branch',
        'status',
        'is_super_admin',
        'is_branch_account',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_initial} {$this->last_name}");
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_super_admin' => 'boolean',
        'is_branch_account' => 'boolean',
        'status' => 'boolean',
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function divisions()
    {
        return $this->hasMany(UserDivision::class);
    }

    /**
     * Override delete to modify unique columns so they can be reused by new users.
     */
    public function delete()
    {
        // Prevent modifying it multiple times if soft deleted again
        if (!$this->trashed()) {
            $this->employee_number = $this->employee_number . '_del_' . $this->id;
            $this->email = $this->email . '_del_' . $this->id;
            $this->save();
        }

        return parent::delete();
    }
    /**
     * Get the display name (Active Nurse or Branch Account Name).
     */
    public function getDisplayNameAttribute()
    {
        if (session()->has('active_nurse_id')) {
            $nurse = User::find(session('active_nurse_id'));
            if ($nurse) {
                return $nurse->name;
            }
        }
        return $this->name;
    }

    /**
     * Get the display position (Active Nurse Position or Branch Account Position).
     */
    public function getDisplayPositionAttribute()
    {
        if (session()->has('active_nurse_id')) {
            $nurse = User::find(session('active_nurse_id'));
            if ($nurse) {
                return $nurse->position;
            }
        }
        return $this->position;
    }
}
