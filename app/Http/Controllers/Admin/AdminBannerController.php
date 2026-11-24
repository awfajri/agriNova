<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBannerController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'banner_image_file' => ['nullable', 'image', 'max:5120'],
            'banner_image_path' => ['nullable', 'string', 'max:500'],
            'title' => ['nullable', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'cta_text' => ['nullable', 'string', 'max:80'],
            'cta_url' => ['nullable', 'string', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_countdown' => ['nullable', 'boolean'],
            'countdown_end_at' => ['nullable', 'date'],
        ]);

        if (! $request->hasFile('banner_image_file') && empty($validated['banner_image_path'])) {
            return back()->withInput()->with('error', 'Silakan upload gambar banner atau masukkan path gambar.');
        }

        $imagePath = $validated['banner_image_path'] ?? 'images/banner-1.jpg';

        if ($request->hasFile('banner_image_file')) {
            $path = $request->file('banner_image_file')->store('banners', 'public');
            $imagePath = 'storage/' . $path;
        }

        Banner::query()->create([
            'title' => $validated['title'] ?? null,
            'eyebrow' => $validated['eyebrow'] ?? null,
            'image_path' => $imagePath,
            'cta_text' => $validated['cta_text'] ?? null,
            'cta_url' => $validated['cta_url'] ?? null,
            'order' => (int) ($validated['order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
            'is_countdown' => $request->boolean('is_countdown', false),
            'countdown_end_at' => $validated['countdown_end_at'] ?? null,
        ]);

        return back()->with('success', 'Banner baru berhasil ditambahkan.');
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'banner_image_file' => ['nullable', 'image', 'max:5120'],
            'banner_image_path' => ['nullable', 'string', 'max:500'],
            'title' => ['nullable', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'cta_text' => ['nullable', 'string', 'max:80'],
            'cta_url' => ['nullable', 'string', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_countdown' => ['nullable', 'boolean'],
            'countdown_end_at' => ['nullable', 'date'],
        ]);

        $imagePath = $banner->image_path;

        if ($request->hasFile('banner_image_file')) {
            // Hapus file lama jika ada di storage/banners
            if (str_starts_with($imagePath, 'storage/banners/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $imagePath));
            }
            $path = $request->file('banner_image_file')->store('banners', 'public');
            $imagePath = 'storage/' . $path;
        } elseif (filled($validated['banner_image_path'] ?? null)) {
            $imagePath = $validated['banner_image_path'];
        }

        $banner->update([
            'title' => $validated['title'] ?? null,
            'eyebrow' => $validated['eyebrow'] ?? null,
            'image_path' => $imagePath,
            'cta_text' => $validated['cta_text'] ?? null,
            'cta_url' => $validated['cta_url'] ?? null,
            'order' => (int) ($validated['order'] ?? $banner->order),
            'is_active' => $request->boolean('is_active'),
            'is_countdown' => $request->boolean('is_countdown'),
            'countdown_end_at' => $validated['countdown_end_at'] ?? null,
        ]);

        return back()->with('success', 'Banner berhasil diperbarui.');
    }

    public function toggle(Banner $banner): RedirectResponse
    {
        $banner->update(['is_active' => ! $banner->is_active]);

        $status = $banner->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Banner berhasil {$status}.");
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        if (str_starts_with($banner->image_path, 'storage/banners/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $banner->image_path));
        }

        $banner->delete();

        return back()->with('success', 'Banner berhasil dihapus.');
    }
}
