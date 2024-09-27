<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Contracts\CanDonate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements CanDonate
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'phone',
        'dail_code',
        'avatar',
        'email_verified_at',
        'phone_verified_at',
        'account_verified_at',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'phone_verified_at' => 'datetime',
            'account_verified_at' => 'datetime',
            'password' => 'hashed'
        ];
    }

    /**
     * Get the name of the crowdfunding's donator
     * @return string
     */
    public function getName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get all the donations this donator has contributed
     *
     * @return MorphMany
     */
    public function donations(): MorphMany
    {
        return $this->morphMany(Donation::class, 'donator');
    }

    /**
     * Get all the crowdfunding campaign created by this user
     * NB: It can be created for another user to receive donations.
     * @return MorphMany
     */
    public function campaignsCreated(): MorphMany
    {
        return $this->morphMany(DonationCampaign::class, 'creator');
    }

    /**
     * Get all crowdfunding campaigns belonging to this user
     *
     * @return MorphMany
     */
    public function campaignsOwned(): MorphMany
    {
        return $this->morphMany(DonationCampaign::class, 'receiver');
    }
}
