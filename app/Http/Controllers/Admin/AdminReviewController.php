<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReviewController extends Controller
{
    public function index(Request $request): View
    {
        $reviews = ProductReview::query()
            ->with(['product', 'user', 'order'])
            ->when($request->input('status') === 'pending', fn ($query) => $query->where('is_approved', false))
            ->when($request->input('status') === 'approved', fn ($query) => $query->where('is_approved', true))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function update(Request $request, ProductReview $review): RedirectResponse
    {
        $validated = $request->validate(['is_approved' => ['required', 'boolean']]);
        $review->update(['is_approved' => $validated['is_approved']]);

        return back()->with('success', 'Status ulasan berhasil diperbarui.');
    }

    public function destroy(ProductReview $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
