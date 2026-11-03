<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    private const FIELDS = [
        'site_name' => 'Nama website',
        'address' => 'Alamat',
        'whatsapp' => 'Nomor WhatsApp',
        'maps_url' => 'URL Google Maps',
        'seo_title' => 'Judul SEO default',
        'seo_description' => 'Deskripsi SEO default',
    ];

    public function edit(): View
    {
        $settings = SiteSetting::query()->pluck('value', 'key');

        return view('admin.settings.edit', [
            'fields' => self::FIELDS,
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:160'],
            'address' => ['nullable', 'string', 'max:500'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'maps_url' => ['nullable', 'url', 'max:1000'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string', 'max:320'],
        ]);

        foreach (self::FIELDS as $key => $label) {
            SiteSetting::query()->updateOrCreate(['key' => $key], ['value' => $validated[$key] ?? null]);
        }

        return back()->with('success', 'Pengaturan website berhasil disimpan.');
    }
}
