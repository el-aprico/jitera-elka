<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Address;
use App\Models\Following;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test the call API with a empty user ID parameter
     *
     * Create 5 users in the database and call the API endpoint.
     * Assert the JSON response structure.
     * Assert response success.
     * Assert response contain attribute data with array value.
     * Assert array has all key.
     *
     * @return void
     */
    public function testIndexMethodWithEmptyUserIdParam()
    {
        $countCreated = 5;
        // Create 5 users in database
        User::factory()->count($countCreated)->create();

        // Call the route
        $response = $this->get(route('users.index'));

        // Assert the json response structure
        /*
        $this->assertTrue(isset($response['status']));
        $this->assertTrue(isset($response['status_code']));
        $this->assertTrue(isset($response['data']['id']));
        ....
        */
        $response->assertJsonStructure([
            'status',
            'status_code',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'website'
                ]
            ]
        ]);

        $this->assertResponseSuccess($response);
   
        // $this->assertNotEmpty($data);
        $response->assertJson([
            'data' => []
        ]);
    
        /*
        Assert that the data response has more than one user data.
        The 'User::factory()->count($countCreated)->create();' code should generate more than one user data.
        */
        
        $data = $response->json('data');
        for ($i = 0; $i < $countCreated; $i++) {
            $this->assertArrayHasKey($i, $data);
        }
    }

    /**
     * Test the call API with a non-empty user ID parameter
     *
     * Create single user in the database and call the API endpoint.
     * Assert the JSON response structure.
     * Assert response success
     *
     * @return void
     */
    public function testIndexMethodWithNonEmptyUserIdParam()
    {
        // Create a user in database
        $user = User::factory()->create();

        // Call the route
        $response = $this->get(route('user.index', [
            'userId' => $user->id
        ]));

        // Assert the json response structure
        $response->assertJsonStructure([
            'status',
            'status_code',
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'website'
            ]
        ]);
        
        $this->assertResponseSuccess($response);
    }

    /**
     * 
     * Test the call API with a non-empty user ID parameter to get detail
     *
     * Create single user in the database with address and company and call the API endpoint.
     * Assert the JSON response structure
     * Assert response success
     *
     * @return void
     */
    public function testDetailsMethodWithEmptyUserIdParam()
    {
        $user = User::factory()->withAddressAndCompany()->create();

        // Call details method with empty userId parameter
        $response = $this->get(route('users.details'));

        // Assert the response json structure
        $response->assertJsonStructure([
            'status',
            'status_code',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'website',
                    'address' => [
                        'id',
                        'street',
                        'suite',
                        'city',
                        'zipcode',
                        'latitude',
                        'longitude'
                    ],
                    'company' => [
                        'id',
                        'name',
                        'catch_phrase',
                        'bs'
                    ]
                ]
            ]
        ]);

        $this->assertResponseSuccess($response);
    }

    /**
     * 
     * Test the call API with a null address and company
     *
     * Create multiple users in the database without address and company and call the API endpoint.
     * Assert the JSON response structure
     * Assert response success
     * Assert response null from address and company
     *
     * @return void
     */
    public function testDetailsMethodWithEmptyUserIdParamNullAddressAndCompany()
    {
        $user = User::factory()->count(3)->create();

        // Call details method with empty userId parameter
        $response = $this->get(route('users.details'));

        // Assert the response json structure
        $response->assertJsonStructure([
            'status',
            'status_code',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'website',
                    'address',
                    'company'
                ]
            ]
        ]);

        $this->assertResponseSuccess($response);

        // assert response json attribute address and company is null
        /*
        $this->assertNull($response['data']['address']);
        $this->assertNull($response['data']['company']);
        */
        $this->assertJsonFragmentNull($response, ['address', 'company']);
    }

    /**
     * 
     * Test the call API with address and company
     *
     * Create single user in the database with address and company and call the API endpoint.
     * Assert the JSON response structure
     * Assert response success
     *
     * @return void
     */
    public function testDetailsMethodWithUserIdParam()
    {
        $user = User::factory()->withAddressAndCompany()->create();

        // Call the route
        $response = $this->get(route('user.details', [
            'userId' => $user->id
        ]));

        // assert response json structure
        $response->assertJsonStructure([
            'status',
            'status_code',
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'website',
                'address' => [
                    'id',
                    'street',
                    'suite',
                    'city',
                    'zipcode',
                    'latitude',
                    'longitude'
                ],
                'company' => [
                    'id',
                    'name',
                    'catch_phrase',
                    'bs'
                ]
            ]
        ]);

        $this->assertResponseSuccess($response);
    }

    /**
     * 
     * Test the call API with a null address and company
     *
     * Create single user in the database without address and company and call the API endpoint.
     * Assert the JSON response structure
     * Assert response success
     * Assert response null from address and company
     *
     * @return void
     */
    public function testDetailsMethodWithUserIdParamNullAddressAndCompany()
    {
        $user = User::factory()->create();

        // Call the route
        $response = $this->get(route('user.details', [
            'userId' => $user->id
        ]));

        // assert response json structure
        $response->assertJsonStructure([
            'status',
            'status_code',
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'website',
                'address',
                'company'
            ]
        ]);

        $this->assertResponseSuccess($response);

        // assert response json attribute address and company is null
        /*
        $this->assertNull($response['data']['address']);
        $this->assertNull($response['data']['company']);
        */
        $this->assertJsonFragmentNull($response, ['address', 'company']);
    }

     /** @test */
    public function testGetFollowedByFollowingUserIdAndName()
    {
        // create 5 users
        $user1 = User::factory()->create(['name' => 'Jean-Claude Van Damme']);
        $user2 = User::factory()->create(['name' => 'Brown Pajono Smith']);
        $user3 = User::factory()->create(['name' => 'John Cena']);
        $user4 = User::factory()->create(['name' => 'Mathias Leajhonmoon']);
        $user5 = User::factory()->create(['name' => 'Wicky Mojohnny Moleano']);

        // create followings
        Following::factory()->createMany([
            ['user_id' => $user2->id, 'following_user_id' => $user1->id],
            ['user_id' => $user3->id, 'following_user_id' => $user1->id],
            ['user_id' => $user5->id, 'following_user_id' => $user1->id]
        ]);
    
        // test with a follower name that exists
        $response = $this->get(route('user.follower_by_name', [$user1->id]));
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Brown Pajono Smith'])
            ->assertJsonFragment(['name' => 'John Cena'])
            ->assertJsonFragment(['name' => 'Wicky Mojohnny Moleano'])
            ->assertJsonMissing(['name' => 'Mathias Leajhonmoon']);
 
        // test with a follower name that doesn't exist
        $response = $this->get(route('user.follower_by_name', [$user1->id, 'john']));
 
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'John Cena'])
            ->assertJsonFragment(['name' => 'Wicky Mojohnny Moleano'])
            ->assertJsonMissing(['name' => 'Brown Pajono Smith'])
            ->assertJsonMissing(['name' => 'Mathias Leajhonmoon']);
    }
}