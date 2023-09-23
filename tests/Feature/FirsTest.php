<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FirsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_credit_debit_store(): void
    {
        $this->post('api/credit');
        $user = User::find(1);
        $this->actingAs($user);


        $response = $this->post('/api/credit', [
            'date' => '2023-09-23',
            'client_id' => 1,
            'author_id' => 1,
            'store_id' => 1,
            'summa' => 100.00,
            'description' => "dg",
            'type' => 'credit'
        ]);
        $response->assertStatus(200);
    }
}
