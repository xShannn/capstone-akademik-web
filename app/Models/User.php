<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
    ];

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

    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya user dengan role 'admin' yang bisa akses
        return $this->role === 'admin';

        // Atau kalau kolom role nya berbeda, sesuaikan:
        // return $this->is_admin === true;
        // return $this->user_type === 'admin';
    }

    public function username()
    {
        return 'username'; // Ini yang penting!
    }

    // * Cari user by username (untuk authentication)
    //  */
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    // Relationships

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }
    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    // Relationship untuk orang tua (bisa memiliki banyak anak)
    public function parentAccount()
    {
        return $this->hasMany(Student::class, 'parent_user_id');
    }

    public function getStudentDataAttribute()
    {
        return $this->student;
    }

    // Method untuk reset ke password default
    public function resetToDefaultPassword()
    {
        $this->password = 'password123';
        $this->save();

        return $this;
    }
}
