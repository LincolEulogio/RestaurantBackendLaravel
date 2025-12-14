<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $printers = \App\Models\Printer::all();
        $paymentMethods = \App\Models\PaymentMethod::all();

        return view('settings.index', compact('settings', 'printers', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $input = $request->except(['_token', '_method', 'logo']);

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            \App\Models\Setting::set('logo', $path, 'general', 'file');
        }

        // Update provided values
        // Update or Create provided values
        foreach ($input as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => 'general', // Default group
                    'type' => 'string' // Default type, can be refined if needed
                ]
            );
        }

        // Handle unchecked checkboxes (boolean types)
        // We assume keys starting with 'system_' are booleans if not present in request (and we know they are toggles)
        $booleanKeys = ['system_auto_print', 'system_sound_notifications'];
        foreach ($booleanKeys as $key) {
            if (! $request->has($key)) {
                \App\Models\Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => '0', 'group' => 'system', 'type' => 'boolean']
                );
            } else {
                 // Ensure it's treated as boolean if passed as "on" or "1"
                 \App\Models\Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => '1', 'group' => 'system', 'type' => 'boolean']
                );
            }
        }

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}
