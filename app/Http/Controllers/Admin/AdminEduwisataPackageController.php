<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EduwisataPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminEduwisataPackageController extends Controller
{
    public function index(): View
    {
        $packages = EduwisataPackage::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15);

        return view('admin.eduwisata-packages.index', compact('packages'));
    }

    public function create(): View
    {
        return view('admin.eduwisata-packages.form', [
            'package' => new EduwisataPackage([
                'is_active' => true,
                'quota' => '100–120 peserta/sesi',
                'image' => 'images/edukasi2.webp',
                'sort_order' => 1,
            ]),
            'formAction' => route('admin.eduwisata-packages.store'),
            'formMethod' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $pkg = EduwisataPackage::query()->create($this->validated($request));

        return redirect()
            ->route('admin.eduwisata-packages.index')
            ->with('success', "Paket {$pkg->title} berhasil ditambahkan.");
    }

    public function edit(EduwisataPackage $eduwisataPackage): View
    {
        return view('admin.eduwisata-packages.form', [
            'package' => $eduwisataPackage,
            'formAction' => route('admin.eduwisata-packages.update', $eduwisataPackage),
            'formMethod' => 'PUT',
        ]);
    }

    public function update(Request $request, EduwisataPackage $eduwisataPackage): RedirectResponse
    {
        $eduwisataPackage->update($this->validated($request, $eduwisataPackage));

        return redirect()
            ->route('admin.eduwisata-packages.index')
            ->with('success', "Paket {$eduwisataPackage->title} berhasil diperbarui.");
    }

    public function destroy(EduwisataPackage $eduwisataPackage): RedirectResponse
    {
        $eduwisataPackage->update(['is_active' => false]);

        return back()->with('success', "Paket {$eduwisataPackage->title} dinonaktifkan.");
    }

    private function validated(Request $request, ?EduwisataPackage $package = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'short_title' => ['required', 'string', 'max:60'],
            'slug' => [
                'nullable',
                'string',
                'max:140',
                Rule::unique('eduwisata_packages', 'slug')->ignore($package?->id),
            ],
            'tag' => ['required', 'string', 'max:80'],
            'price' => ['required', 'integer', 'min:0'],
            'quota' => ['required', 'string', 'max:80'],
            'subtitle' => ['nullable', 'string', 'max:180'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'string', 'max:500'],
            'highlights_text' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $lines = preg_split('/\r\n|\r|\n/', (string) ($validated['highlights_text'] ?? ''));
        $highlights = array_values(array_filter(array_map('trim', $lines)));

        return [
            'title' => $validated['title'],
            'short_title' => $validated['short_title'],
            'slug' => filled($validated['slug'] ?? null)
                ? Str::slug($validated['slug'])
                : Str::slug($validated['title']),
            'tag' => $validated['tag'],
            'price' => $validated['price'],
            'quota' => $validated['quota'],
            'subtitle' => $validated['subtitle'] ?? null,
            'summary' => $validated['summary'] ?? null,
            'image' => $validated['image'] ?: 'images/edukasi2.webp',
            'highlights' => $highlights,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}