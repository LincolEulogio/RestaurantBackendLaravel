<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Restaurant Data
            ['key' => 'restaurant_name', 'value' => 'RestaurantOS Demo', 'group' => 'general', 'type' => 'string'],
            ['key' => 'restaurant_cuisine_type', 'value' => 'Peruana', 'group' => 'general', 'type' => 'string'],
            ['key' => 'restaurant_phone', 'value' => '+51 999 999 999', 'group' => 'general', 'type' => 'string'],
            ['key' => 'restaurant_email', 'value' => 'contacto@restaurantos.com', 'group' => 'general', 'type' => 'string'],
            ['key' => 'restaurant_address', 'value' => 'Av. Principal 123, Lima, Perú', 'group' => 'general', 'type' => 'string'],
            ['key' => 'restaurant_timezone', 'value' => 'America/Lima', 'group' => 'general', 'type' => 'string'],
            ['key' => 'restaurant_currency', 'value' => 'PEN - Sol Peruano', 'group' => 'general', 'type' => 'string'],
            
            // System Preferences
            ['key' => 'system_auto_print', 'value' => '0', 'group' => 'system', 'type' => 'boolean'],
            ['key' => 'system_sound_notifications', 'value' => '1', 'group' => 'system', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
