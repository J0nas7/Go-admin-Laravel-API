<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Response;

class AuthTest extends TestCase
{
    /**
     * Test adminLoggedInTest
     *
     * @return json
     */
    public function test_adminLoggedInTest() {
        $this->json('get', 'api/adminLoggedInTest')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'success' => false,
                'message' => "Is NOT logged in",
                'data' => false
            ]);
    }

    /**
     * Test adminLogout
     *
     * @return json
     */
    public function test_adminLogout() {
        $this->json('get', 'api/adminLogout')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'success' => true,
                'message' => 'Is logged out',
                'data'    => true
            ]);
    }

    /* Admin Login Tests */

    /**
     * Test empty adminLogin
     *
     * @return json
     */
    public function test_adminLoginEmpty() {
        $this->json('post', 'api/adminLogin')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'success' => false,
                'message' => "Empty request",
                'data'    => false
            ]);
    }

    /**
     * Test adminLogin attempt fail
     *
     * @return json
     */
    public function test_adminLoginAttemptFailed() {
        $this->json('post', 'api/adminLogin', [
            'username' => 'Nameuser',
            'password' => 'Wordpass'
        ])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'success' => false,
                'message' => "Login Attempt Failed",
                'data'    => false
            ]);
    }
}