<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BosAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_bos_redirected_gracefully_when_accessing_admin_bookings(): void
    {
        $bos = new User([
            'id' => 999,
            'name' => 'Pak Bos',
            'email' => 'bos@f55center.com',
            'role' => 'bos',
        ]);

        $response = $this->actingAs($bos)->get('/admin/bookings');
        $response->assertRedirect(route('bos.bookings'));
    }

    public function test_bos_can_access_check_new_bookings(): void
    {
        $bos = new User([
            'id' => 999,
            'name' => 'Pak Bos',
            'email' => 'bos@f55center.com',
            'role' => 'bos',
        ]);

        $response = $this->actingAs($bos)->get('/bos/bookings/check-new');
        $response->assertStatus(200);
        $response->assertJsonStructure(['has_new', 'total_new_count', 'latest_id', 'items']);
    }
}
