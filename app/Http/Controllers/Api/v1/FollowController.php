<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Requests\FollowUserRequest;
use App\Http\Requests\UnfollowUserRequest;
use App\Http\Requests\RemoveFollowerRequest;
use App\Models\User;
use App\Models\Following;

class FollowController extends ApiController
{
    /**
     * Store a newly created resource in storage.
     * 
     * @param \App\Http\Requests\FollowUserRequest $request
     * @param \App\Models\Following $followingModel
     * @return \Illuminate\Http\JsonResponse
    */
    public function index(FollowUserRequest $request, Following $following)
    {
        try {
            $following->followUser($request->user_id, $request->following_user_id);
            return $this->respondCreated(__('following.success_follow_user'));
        } catch (\Exception $e) {
            // handle exception here
            return $this->setStatusCode(500)->respondWithError($e->getMessage());
        }
    }

    /**
     * Unfollow a user.
     *
     * @param int $userId
     * @param int $userFollowId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFollowing(UnfollowUserRequest $request, Following $following)
    {
        try {
            $following->unfollowUser($request->user_id, $request->following_user_id);
            return $this->respondMessage(__('following.success_unfollow_user'));
        } catch (\Exception $e) {
            // handle exception here
            return $this->setStatusCode(500)->respondWithError($e->getMessage());
        }
    }

    /**
     * Remove a follower.
     *
     * @param int $userId
     * @param int $userFollowId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFollower(RemoveFollowerRequest $request, Following $following)
    {
        try {
            $following->unfollowUser($request->following_user_id, $request->user_id);
            return $this->respondMessage(__('following.success_delete_follower'));
        } catch (\Exception $e) {
            // handle exception here
            return $this->setStatusCode(500)->respondWithError($e->getMessage());
        }
    }
}