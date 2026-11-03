<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminGalleryController extends Controller
{
    public function index(): View
    {
        $items = GalleryItem::query()->orderBy('sort_order')->orderBy('title')->paginate(15);

        return view('admin.gallery.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.gallery.form', [
            'item' => new GalleryItem(['is_active' => true]),
            'formAction' => route('admin.gallery.store'),
            'formMethod' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = GalleryItem::query()->create($this->validated($request));

        return redirect()->route('admin.gallery.index')->with('success', "Foto {$item->title} berhasil ditambahkan.");
    }

    public function edit(GalleryItem $gallery): View
    {
        return view('admin.gallery.form', [
            'item' => $gallery,
            'formAction' => route('admin.gallery.update', $gallery),
            'formMethod' => 'PUT',
        ]);
    }

    public function update(Request $request, GalleryItem $gallery): RedirectResponse
    {
        $gallery->update($this->validated($request));

        return redirect()->route('admin.gallery.index')->with('success', "Foto {$gallery->title} berhasil diperbarui.");
    }

    public function destroy(GalleryItem $gallery): RedirectResponse
    {
        $gallery->update(['is_active' => false]);

        return back()->with('success', "Foto {$gallery->title} disembunyikan dari galeri.");
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:500'],
            'image_url' => ['required', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        return $validated;
    }
}
