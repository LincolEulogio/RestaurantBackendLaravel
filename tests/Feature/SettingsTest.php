<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed permissions if needed or just create user with permission
        // Assuming 'settings' permission exists or we can mock it.
        // For simplicity, we create a user and give them a role/permission if used.
        // The router middleware checks 'permission:settings'.
        
        $role = Role::firstOrCreate(['name' => 'admin']);
        $permission = Permission::firstOrCreate(['name' => 'settings']);
        $role->givePermissionTo($permission);

        $this->user = User::factory()->create();
        $this->user->assignRole($role);
    }

    public function test_settings_page_can_be_rendered()
    {
        $response = $this->actingAs($this->user)->get(route('settings.index'));
        $response->assertStatus(200);
    }

    public function test_can_update_settings()
    {
        Setting::set('restaurant_name', 'Old Name');
        Setting::set('payment_cash', '0', 'payment', 'boolean');

        $response = $this->actingAs($this->user)->put(route('settings.update'), [
            'restaurant_name' => 'New Name',
            'payment_cash' => 'on', // Checkbox sends 'on' usually, or 1. Controller treats presence as true for booleans if we handled it right?
            // Controller logic: foreach input -> update. foreach boolean -> if missing set 0.
            // If I send 'payment_cash' => '1', it updates to '1'.
        ]);

        $response->assertRedirect();
        
        $this->assertEquals('New Name', Setting::get('restaurant_name'));
        // If I didn't send payment_cash, it would be 0.
        // Wait, my controller logic for booleans: if !$request->has(key), set to 0.
        // If I verify this:
    }

    public function test_boolean_settings_toggle_off()
    {
        Setting::set('payment_cash', '1', 'payment', 'boolean');
        
        // Update without sending payment_cash
        $response = $this->actingAs($this->user)->put(route('settings.update'), [
            'restaurant_name' => 'Name',
        ]);

        $this->assertEquals('0', Setting::get('payment_cash'));
    }
}
