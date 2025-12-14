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
        foreach ($input as $key => $value) {
            // Avoid updating non-setting fields if any slip through
            if (\App\Models\Setting::where('key', $key)->exists()) {
                \App\Models\Setting::where('key', $key)->update(['value' => $value]);
            }
        }

        // Handle unchecked checkboxes (boolean types) - strictly for those missing in request
        // Our x-toggle sends hidden input, but standard checkboxes might not.
        $booleanKeys = \App\Models\Setting::where('type', 'boolean')->pluck('key');
        foreach ($booleanKeys as $key) {
            if (! $request->has($key)) {
                \App\Models\Setting::where('key', $key)->update(['value' => '0']);
            }
        }

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}
