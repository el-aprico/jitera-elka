<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $successCode = 200;
    protected $errorCode = 422;
    protected $createdCode = 201;

    public function assertJsonFragmentNull($response, $keys)
    {
        $response->assertJsonFragment(array_fill_keys($keys, null));
    }

    public function assertResponseSuccess($response)
    {
        // Assert response status code is 200
        // $this->assertSame($this->successCode, $response->getStatusCode());
        $response->assertStatus($this->successCode);

        // Assert if response return with correct response
        /*
        $this->assertEquals('success', $response['status']);
        $this->assertEquals($this->successCode, $response['status_code']);
        */
        $response->assertJson([
            'status' => 'success',
            'status_code' => $this->successCode
        ]);
    }

    public function assertResponseStructureMessage($response)
    {
        // assert response json structure
        $response->assertJsonStructure([
            'status',
            'status_code',
            'message'
        ]);
    }
}
