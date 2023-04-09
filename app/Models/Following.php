<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Following extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'followings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'following_user_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'creted_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * The custom attributes that should be added for serialization.
     *
     * @var array
     */
    protected $appends = ['following_at'];

    /**
     * Get the date when to follow.
     * The following_at attribute is taken from the created_at column
     *
     * @return string
     */
    public function getFollowingAtAttribute()
    {
        return $this->created_at->toDateTimeString();
    }

    /**
     * Get the user who is being followed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function followedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who is being followed with address and company attribute.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function followedUserDetails()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->with('address', 'company');
    }

    /**
     * Get the user who is following.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function followingUser()
    {
        return $this->belongsTo(User::class, 'following_user_id');
    }

    /**
     * Get the user who is following with address and company attribute.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function followingUserDetails()
    {
        return $this->belongsTo(User::class, 'following_user_id')
            ->with('address', 'company');
    }

    /**
     * Get all users followed by user_id.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function followUser($userId = 0, $followingUserId = 0)
    {
        return $this->create([
            'user_id' => $userId,
            'following_user_id' => $followingUserId
        ]);
    }


    /**
     * Get all users followed by user_id.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function unfollowUser($userId = 0, $followingUserId = 0)
    {
        return $this->where([
            'user_id' => $userId,
            'following_user_id' => $followingUserId
        ])->delete();
    }

    /**
     * Get all users followed by user_id.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getFollowedByUserId($userId = 0)
    {
        $following = $this->with('followingUser')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($data) {
                $resp = $data->followingUser;
                $address = $data->followingUser;
                $resp['id'] = $data->id;
                $resp['user_id'] = $data->following_user_id;
                $resp['following_at'] = $data->following_at;
                return $resp;
            });
        return $following;
    }

    /**
     * Get all users following by user_id.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getFollowedByFollowingUserId($userId = 0)
    {
        $following = $this->where('following_user_id', $userId);

        return $this->getFollowingResponse($following->get());
    }

    /**
     * Get all users following by user_id and name.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getFollowedByFollowingUserIdAndName($userId = 0, $followerName = null)
    {
        $following = $this->with('followedUser')
            ->where('following_user_id', $userId)
            ->whereHas('followedUser', function($query) use ($followerName) {
                $query->where('name', 'LIKE', '%' . $followerName . '%');
            });

        return $this->getFollowingResponse($following->get());
    }


    /**
     * Returns the response for getFollowedByFollowingUserId and getFollowedByFollowingUserIdAndName
     *
     * @param \Illuminate\Database\Eloquent\Collection $following
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getFollowingResponse($following)
    {
        return $following->map(function ($data) {
            $resp = $data->followedUser;
            $resp['id'] = $data->id;
            $resp['user_id'] = $data->user_id;
            $resp['following_at'] = $data->following_at;
            return $resp;
        });
    }
}