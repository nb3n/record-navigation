<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Saade\FilamentFacehash\Concerns\HasFacehashAvatar;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    use HasFacehashAvatar;

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'role' => UserRole::class,
        ];
    }

    /**
     * Posts authored by this user.
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class)
            ->withPivot(['role', 'is_primary'])
            ->withTimestamps();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        $allowedEmails = [
            'demo@rnd.com',
        ];

        return in_array($this->email, $allowedEmails);
    }
}
