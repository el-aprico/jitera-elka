<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'website'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * Get the address associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->hasOne(Address::class);
    }

    /**
     * Get the company associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    // Relasi dengan tabel following
    public function following()
    {
        return $this->belongsToMany(User::class, 'user_following', 'user_id', 'following_id');
    }

    /**
     * Get the follower user associated with the follower.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follower', 'following_id', 'user_id');
    }

    /**
     * Get all users with company and address.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getDetail()
    {
        $users = $this->with('address', 'company')->get();
        return $users;
    }

    /**
     * Get selected user without company and address.
     *
     * @param int $userId
     * @return \App\Models\User|null
     */
    public function getByUserId($userId = 0)
    {
        $user = $this->find($userId);
        return $user;
    }

    /**
     * Get selected user with company and address.
     *
     * @param int $userId
     * @return \App\Models\User|null
     */
    public function getDetailByUserId($userId)
    {
        $user = $this->with('address', 'company')->find($userId);
        return $user;
    }

    /**
     * Add user to the list of users being followed.
     *
     * @param int $userFollowId
     * @return bool
     */
    public function addFollow($userFollowId)
    {
        // Implement logic to add user to the list of users being followed
        // ...

        return $success;
    }



}
