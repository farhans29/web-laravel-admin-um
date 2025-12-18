<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'phone_number',
        'username',
        'email',
        'password',
        'is_admin',
        'property_id',
        'role_id',
        'status',
        'created_by',
        'updated_by',
        'profile_picture',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class);
    // }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'idrec');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }

    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }
}
