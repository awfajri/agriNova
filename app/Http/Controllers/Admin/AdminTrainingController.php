<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminTrainingController extends Controller
{
    public function index(): View
    {
        $classes = TrainingClass::query()
            ->withCount('registrations')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate(15);

        return view('admin.trainings.index', compact('classes'));
    }

    public function create(): View
    {
        return view('admin.trainings.form', [
            'class' => new TrainingClass(['is_available' => true]),
            'formAction' => route('admin.trainings.store'),
            'formMethod' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $class = TrainingClass::query()->create($this->validated($request));

        return redirect()->route('admin.trainings.index')->with('success', "Kelas {$class->title} berhasil ditambahkan.");
    }

    public function edit(TrainingClass $training): View
    {
        return view('admin.trainings.form', [
            'class' => $training,
            'formAction' => route('admin.trainings.update', $training),
            'formMethod' => 'PUT',
        ]);
    }

    public function update(Request $request, TrainingClass $training): RedirectResponse
    {
        $training->update($this->validated($request, $training));

        return redirect()->route('admin.trainings.index')->with('success', "Kelas {$training->title} berhasil diperbarui.");
    }

    public function destroy(TrainingClass $training): RedirectResponse
    {
        $training->update(['is_available' => false, 'status' => 'Nonaktif']);

        return back()->with('success', "Kelas {$training->title} dinonaktifkan.");
    }

    private function validated(Request $request, ?TrainingClass $training = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('training_classes', 'slug')->ignore($training?->id)],
            'category' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'string', 'max:100'],
            'schedule' => ['required', 'string', 'max:120'],
            'capacity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'max:40'],
            'image' => ['nullable', 'string', 'max:1000'],
            'highlights' => ['nullable', 'string', 'max:2000'],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:10000'],
            'materials' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['slug'] = filled($validated['slug'] ?? null)
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);
        $validated['highlights'] = $this->linesToArray($validated['highlights'] ?? null);
        $validated['materials'] = $this->linesToArray($validated['materials'] ?? null);
        $validated['is_available'] = $request->boolean('is_available');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        return $validated;
    }

    private function linesToArray(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
