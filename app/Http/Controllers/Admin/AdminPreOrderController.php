<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreOrder;
use App\Models\Product;
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

        return view('admin.preorders.index', compact('preorders'));
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
