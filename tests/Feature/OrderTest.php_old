<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Response;

class OrderTest extends TestCase
{
    /**
     * Test readAllOrdersSummary json structure
     *
     * @return json
     */
    public function test_readAllOrdersSummary() {
        $this->withSession(['adminLoggedIn' => true])
            ->json('post', 'api/readAllOrdersSummary')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'message' => 'Orders Summary'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'length',
                    'orders' => [
                        '*' => [
                            'orderId',
                            'totalSale',
                            'destinationAdr',
                            'destinationArea',
                            'deliveryDeadline'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Test readOneOrder json structure
     *
     * @return json
     */
    public function test_readOneOrder() {
        $postContent = array('orderNr' => 416);
        $postContent = json_encode($postContent);
        $this->withSession(['adminLoggedIn' => true])
            ->json('post', 'api/readOneOrder', [
                'postContent' => $postContent
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true, 
                'message' => 'Read Order 416'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'orderId',
                    'totalSale',
                    'destinationAdr',
                    'destinationArea',
                    'deliveryDeadline',
                    'dateCreated',
                    'numItemsSold',
                    'status',
                    'customerId',
                    'datePaid',
                    'dateCompleted',
                    'theHelperIs'
                ]
            ]);
    }
}