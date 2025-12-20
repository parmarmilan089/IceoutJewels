<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $group = $request->input('group', 'general');

        $rules = [];
        if ($group === 'general') {
            $rules = [
                'store_name' => 'required|string|max:255',
                'support_email' => 'required|email',
            ];
        } elseif ($group === 'currency') {
            $rules = [
                'currency_code' => 'required|string|max:3',
            ];
        }

        if (!empty($rules)) {
            $request->validate($rules);
        }

        $data = $request->except(['_token', 'group']);

        foreach ($data as $key => $value) {
            // Skip file inputs here, handled separately
            if ($request->hasFile($key)) {
                continue;
            }

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => $group,
                    'type' => 'text'
                ]
            );
        }

        // Handle File Uploads
        $files = ['logo', 'favicon'];
        foreach ($files as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $filename = $fileKey . '.' . $file->getClientOriginalExtension();
                $path = 'uploads/settings';
                $file->move(public_path($path), $filename);
                
                Setting::updateOrCreate(
                    ['key' => $fileKey],
                    [
                        'value' => $path . '/' . $filename,
                        'group' => 'branding',
                        'type' => 'file'
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->updateSettings($request);
    }
}