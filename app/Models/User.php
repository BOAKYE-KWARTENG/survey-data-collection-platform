<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\HasMany;



#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    use HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['admin', 'supervisor', 'enumerator', 'qa_officer']);
    }

    public function households(): HasMany
    {
        return $this->hasMany(Household::class, 'registered_by');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(SurveySubmission::class, 'enumerator_id');
    }


    public function qaAssignments(): HasMany
    {
        return $this->hasMany(QaAssignment::class, 'qa_officer_id');
    }

    public function qaReviews(): HasMany
    {
        return $this->hasMany(QaReview::class, 'qa_officer_id');
    }

}
