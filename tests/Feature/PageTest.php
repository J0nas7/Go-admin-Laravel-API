<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Response;

class PageTest extends TestCase
{
    /**
     * Test basicPageInfo json response
     *
     * @return json
     */
    public function test_basicPageInfo() {
        $this->withSession(['adminLoggedIn' => true])
            ->json('get', 'api/basicPageInfo')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ])
            ->assertExactJson([
                "success" => true,
                "message" => "Basic Page Info",
                "data" => [
                    "blogdescription" => "Det er dyrt at handle i byen. Få en anden til det.",
                    "blogname" => "Go@"
                ]
            ]);
    }

    /**
     * Test getMenuLocation json response
     * 
     * @param termId string "Support-Min-menu"
     * @return json
     */
    public function test_getMenuLocation() {
        $this->withSession(['adminLoggedIn' => true])
            ->json('get', 'api/getMenuLocation/Support-Min-menu')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'label',
                        'link'
                    ]
                ]
            ])
            ->assertExactJson([
                "success" => true,
                "message" => "Get Menu Location by Slug: Support-Min-menu",
                "data" => [
                    ["label" => "Overblik","link" => "/"],
                    ["label" => "Support-beskeder","link" => "#"],
                    ["label" => "Igangværende opgaver","link" => "/cur-orders"],
                    ["label" => "Kunder","link" => "/users"],
                    ["label" => "Alle opgaver","link" => "#"],
                    ["label" => "Indstillinger","link" => "/my-settings"],
                    ["label" => "Log ud","link" => "/logout"]
                ]
            ]);
    }
}