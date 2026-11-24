<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\PreOrder;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminPreOrderController extends Controller
{
    public function index(): View
    {
        $preorders = PreOrder::query()
            ->with('product')
            ->latest()
            ->paginate(15);

        $activePreOrder = PreOrder::query()->where('is_active', true)->latest()->first();

        $bannerSettings = [
            'image' => SiteSetting::get('catalog_banner_image', 'images/banner-1.jpg'),
            'cta_text' => SiteSetting::get('catalog_banner_cta', 'Booking Melon Sekarang'),
            'cta_url' => SiteSetting::get('catalog_banner_url', ''),
        ];

        $banners = Banner::query()->orderBy('order')->orderByDesc('id')->get();

        return view('admin.preorders.index', compact('preorders', 'activePreOrder', 'bannerSettings', 'banners'));
    }

    public function updateBanner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'banner_image_file' => ['nullable', 'image', 'max:4096'],
            'banner_image_path' => ['nullable', 'string', 'max:500'],
            'cta_text' => ['required', 'string', 'max:80'],
            'cta_url' => ['nullable', 'string', 'max:500'],
            'active_preorder_id' => ['nullable', 'integer', 'exists:preorders,id'],
            'batch_title' => ['nullable', 'string', 'max:180'],
            'harvest_date' => ['nullable', 'date'],
            'booking_close_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('banner_image_file')) {
            $path = $request->file('banner_image_file')->store('banners', 'public');
            $this->saveSetting('catalog_banner_image', 'storage/' . $path);
        } elseif (filled($validated['banner_image_path'] ?? null)) {
            $this->saveSetting('catalog_banner_image', $validated['banner_image_path']);
        }

        $this->saveSetting('catalog_banner_cta', $validated['cta_text']);
        $this->saveSetting('catalog_banner_url', $validated['cta_url'] ?? '');

        if (! empty($validated['active_preorder_id'])) {
            $preorder = PreOrder::query()->find($validated['active_preorder_id']);
            if ($preorder) {
                $preorder->update([
                    'title' => $validated['batch_title'] ?: $preorder->title,
                    'harvest_date' => $validated['harvest_date'] ?: $preorder->harvest_date,
                    'booking_close_at' => $validated['booking_close_at'] ?: $preorder->booking_close_at,
                    'is_active' => true,
                ]);
            }
        }

        return back()->with('success', 'Banner katalog dan jadwal countdown pre-order berhasil diperbarui.');
    }

    public function create(): View
    {
        return view('admin.preorders.form', [
            'preorder' => new PreOrder(['is_active' => true]),
            'products' => Product::query()->orderBy('name')->get(),
            'formAction' => route('admin.preorders.store'),
            'formMethod' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $preorder = PreOrder::query()->create($this->validated($request));
        Product::query()->whereKey($preorder->product_id)->update(['is_preorder' => true]);

        return redirect()->route('admin.preorders.index')->with('success', 'Pre-order berhasil ditambahkan.');
    }

    public function edit(PreOrder $preorder): View
    {
        return view('admin.preorders.form', [
            'preorder' => $preorder,
            'products' => Product::query()->orderBy('name')->get(),
            'formAction' => route('admin.preorders.update', $preorder),
            'formMethod' => 'PUT',
        ]);
    }

    public function update(Request $request, PreOrder $preorder): RedirectResponse
    {
        $preorder->update($this->validated($request, $preorder));
        Product::query()->whereKey($preorder->product_id)->update(['is_preorder' => $preorder->is_active]);

        return redirect()->route('admin.preorders.index')->with('success', 'Pre-order berhasil diperbarui.');
    }

    public function destroy(PreOrder $preorder): RedirectResponse
    {
        $productId = $preorder->product_id;
        $preorder->delete();
        Product::query()->whereKey($productId)->update(['is_preorder' => false]);

        return back()->with('success', 'Pre-order dinonaktifkan.');
    }

    private function saveSetting(string $key, ?string $value): void
    {
        SiteSetting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value ?? '']
        );
    }

    private function validated(Request $request, ?PreOrder $preorder = null): array
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'title' => ['required', 'string', 'max:180'],
            'slug' => [
                'nullable',
                'string',
                'max:200',
                Rule::unique('preorders', 'slug')->ignore($preorder?->id),
            ],
            'harvest_date' => ['nullable', 'date'],
            'booking_open_at' => ['nullable', 'date'],
            'booking_close_at' => ['nullable', 'date', 'after_or_equal:booking_open_at'],
            'slot_available' => ['required', 'integer', 'min:0'],
            'slot_booked' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = filled($validated['slug'] ?? null)
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['slot_booked'] > $validated['slot_available']) {
            throw ValidationException::withMessages([
                'slot_booked' => 'Slot yang sudah dipesan tidak boleh melebihi slot tersedia.',
            ]);
        }

        return $validated;
    }
}