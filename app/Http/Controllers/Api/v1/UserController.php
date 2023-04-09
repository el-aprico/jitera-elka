<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Requests\FollowUserRequest;
use App\Models\User;
use App\Models\Following;

class UserController extends ApiController
{

    /**
     * Display all users if $userId is empty and display selected user if $userId is not empty.
     * Return user data without company and address data.
     *
     * @param mixed $userId
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     * 
     */
    public function index($userId = null, User $user)
    {
        if (is_null($userId)) {
            $response = $user->get();
        } else {
            $response = $user->getByUserId($userId);
        }
        return $this->respondData($response);
    }

    /**
     * Display all users if $userId is empty and display selected user if $userId is not empty.
     * Return user data with company and address data.
     *
     * @param mixed $userId
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function details($userId = null, User $user)
    {
        if (is_null($userId)) {
            $response = $user->getDetail();
        } else {
            $response = $user->getDetailByUserId($userId);
        }
        return $this->respondData($response);
    }

    /**
     * Displays a list of followed users.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showFollowing($userId = 0, Following $following)
    {
        $response = $following->getFollowedByUserId($userId);
        return $this->respondData($response);
    }

    /**
     * Display a list of followers based on the given user id and follower name.
     *
     * @param int $userId The id of the user whose followers will be displayed
     * @param \App\Models\Following $following The instance of Following model
     * @return \Illuminate\Http\JsonResponse
     */
    public function showFollowers($userId = 0, $followerName = null, Following $following)
    {
        if (is_null($followerName)) {
            $response = $following->getFollowedByFollowingUserId($userId, $followerName);
        } else {
            $response = $following->getFollowedByFollowingUserIdAndName($userId, $followerName);
        }
        return $this->respondData($response);
    }
}