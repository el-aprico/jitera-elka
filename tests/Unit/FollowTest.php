<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Address;
use App\Models\Following;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FollowTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test the call API to test follow another user
     *
     * Create 2 user, one is user and user to follow
     * Assert response created.
     * Assert response stucture contain attribute 'message'.
     * Assert response specific message.
     * Assert database has user_id and following_user_id.
     *
     * @return void
     */
    public function testUserCanFollowAnotherUser()
    {
        $user = User::factory()->create();
        $followingUser = User::factory()->create();

        $response = $this->postJson(route('follow.follow'), [
            'user_id' => $user->id,
            'following_user_id' => $followingUser->id,
        ]);
        

        $response->assertStatus($this->createdCode);
        $this->assertResponseStructureMessage($response);
    
        $response->assertJson([
            'message' => __('following.success_follow_user')
        ]);

        $this->assertDatabaseHas('followings', [
            'user_id' => $user->id,
            'following_user_id' => $followingUser->id,
        ]);
    }

    /**
     * Test the call API to test follow without send user id
     *
     * Assert response error.
     * Assert response has specific errors message.
     * 
     * @return void
     */
    public function testUserFollowWithEmptyParam()
    {

        // No param sent
        $response = $this->postJson(route('follow.follow'), []);

        $response->assertStatus($this->errorCode);
        $response->assertJson([
            'status' => 'error',
            'status_code' => $this->errorCode,
            'errors' => [
                'user_id' => [
                    __('validation.follow_user.user_id.required')
                ],
                'following_user_id' => [
                    __('validation.follow_user.following_user_id.required')
                ]
            ]
        ]);
    }

    /**
     * Test the call API to test user follow himself
     *
     * Create 1 user, send user to follow himself
     * Assert response error.
     * Assert response has specific errors message.
     * Assert database has no created new data.
     *
     * @return void
     */
    public function testUserCannotFollowHiself()
    {
        $user = User::factory()->create();

        // If user_id and following_user_id have same value, show error following_user_id.different
        $response = $this->postJson(route('follow.follow'), [
            'user_id' => $user->id,
            'following_user_id' => $user->id,
        ]);

        $response->assertStatus($this->errorCode);
    
        $response->assertJson([
            'status' => 'error',
            'status_code' => $this->errorCode,
            'errors' => [
                'following_user_id' => [
                    __('validation.follow_user.following_user_id.different')
                ]
            ]
        ]);

        $this->assertDatabaseMissing('followings', [
            'user_id' => $user->id,
            'following_user_id' => $user->id,
        ]);
    }

    /**
     * Test the call API to test unfollow data
     *
     * Create 2 user, one is user and user to follow
     * Call API to unfollow the followed user.
     * Assert response success.
     * Assert response structure message.
     * Assert response has specific message.
     * 
     * @return void
     */
    public function testUserUnfollow()
    {
        $user = User::factory()->create();
        $followingUser = User::factory()->create();

        $following = new Following;
        $following->followUser($user->id, $followingUser->id);
    
        $response = $this->delete(route('follow.unfollow', [
            'userId' => $user->id,
            'followingUserId' => $followingUser->id,
        ]));
    
        $this->assertResponseSuccess($response);

        $this->assertResponseStructureMessage($response);

        $response->assertJson([
            'message' => __('following.success_unfollow_user')
        ]);
    }

    /**
     * Test the call API to delete follower
     *
     * Create 2 user, one is user and user to follow
     * Call API to unfollow the delete follower.
     * Assert response success.
     * Assert response structure message.
     * Assert response has specific message.
     * 
     * @return void
     */
    public function testUserDeleteFollower()
    {
        $followerUser = User::factory()->create();
        $followedUser = User::factory()->create();

        $following = new Following;
        $following->followUser($followerUser->id, $followedUser->id);
    
        $response = $this->delete(route('follower.delete', [
            'userId' => $followedUser->id,
            'followingUserId' => $followerUser->id,
        ]));
    
        $this->assertResponseSuccess($response);
    
        $this->assertResponseStructureMessage($response);

        $response->assertJson([
            'message' => __('following.success_delete_follower')
        ]);
    }
}