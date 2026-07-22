<?php

namespace Tests\Feature;

use App\Models\Seller;
use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerSizeUniquenessTest extends TestCase
{
    use RefreshDatabase;

    public function test_different_sellers_can_create_same_size(): void
    {
        $sellerA = Seller::create([
            'business_name' => 'Seller A Store',
            'owner_name' => 'Owner A',
            'email' => 'sellerA@test.com',
            'phone' => '1234567891',
            'password' => bcrypt('password'),
            'business_address' => '123 Test St',
            'gst_number' => '22AAAAA0000A1Z5',
            'pan_number' => 'ABCDE1234F',
            'bank_name' => 'Test Bank',
            'bank_account_number' => '123456789012',
            'ifsc_code' => 'TEST0001234',
            'status' => 'Approved',
        ]);

        $sellerB = Seller::create([
            'business_name' => 'Seller B Store',
            'owner_name' => 'Owner B',
            'email' => 'sellerB@test.com',
            'phone' => '1234567892',
            'password' => bcrypt('password'),
            'business_address' => '123 Test St',
            'gst_number' => '22AAAAA0000A1Z6',
            'pan_number' => 'ABCDE1234G',
            'bank_name' => 'Test Bank',
            'bank_account_number' => '123456789013',
            'ifsc_code' => 'TEST0001234',
            'status' => 'Approved',
        ]);

        // Seller A creates size 'S'
        $responseA = $this->actingAs($sellerA, 'seller')
            ->post(route('seller.sizes.store'), [
                'name' => 'S',
                'status' => 1,
            ]);

        $responseA->assertRedirect(route('seller.sizes.index'));
        $this->assertDatabaseHas('sizes', [
            'seller_id' => $sellerA->id,
            'name' => 'S',
        ]);

        // Seller B creates size 'S'
        $responseB = $this->actingAs($sellerB, 'seller')
            ->post(route('seller.sizes.store'), [
                'name' => 'S',
                'status' => 1,
            ]);

        $responseB->assertRedirect(route('seller.sizes.index'));
        $this->assertDatabaseHas('sizes', [
            'seller_id' => $sellerB->id,
            'name' => 'S',
        ]);
    }

    public function test_same_seller_cannot_create_duplicate_size(): void
    {
        $sellerA = Seller::create([
            'business_name' => 'Seller A Store',
            'owner_name' => 'Owner A',
            'email' => 'sellerA@test.com',
            'phone' => '1234567891',
            'password' => bcrypt('password'),
            'business_address' => '123 Test St',
            'gst_number' => '22AAAAA0000A1Z5',
            'pan_number' => 'ABCDE1234F',
            'bank_name' => 'Test Bank',
            'bank_account_number' => '123456789012',
            'ifsc_code' => 'TEST0001234',
            'status' => 'Approved',
        ]);

        // Seller A creates size 'S'
        $response1 = $this->actingAs($sellerA, 'seller')
            ->post(route('seller.sizes.store'), [
                'name' => 'S',
                'status' => 1,
            ]);

        $response1->assertRedirect(route('seller.sizes.index'));

        // Seller A tries to create size 'S' again
        $response2 = $this->actingAs($sellerA, 'seller')
            ->post(route('seller.sizes.store'), [
                'name' => 'S',
                'status' => 1,
            ]);

        $response2->assertSessionHasErrors(['name']);
        
        // Count sizes for Seller A, should be exactly 1
        $this->assertEquals(1, Size::where('seller_id', $sellerA->id)->where('name', 'S')->count());
    }
}
