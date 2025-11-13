<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function create()
    {
        return view('ads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $ad = new Ad();
        $ad->user_id = auth()->id();
        $ad->title = $validated['title'];
        $ad->description = $validated['description'];
        $ad->price = $validated['price'];
        $ad->status = 'moderation';
        $ad->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('ads', 'public');
                $ad->images()->create([
                    'path' => 'storage/' . $path,
                ]);
            }
        }

        return redirect()->route('ads.create')->with('success', 'Объявление отправлено на модерацию.');
    }

    public function myAds()
    {
        $ads = auth()->user()
            ->ads()
            ->with('images')
            ->latest()
            ->paginate(10);

        return view('ads.mine', compact('ads'));
    }

    public function index(Request $request)
    {
        $ads = Ad::with('images')
            ->where('status', 'published')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->input('q');
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('ads.index', compact('ads'));
    }

    public function moderate()
    {
        $ads = Ad::with(['images', 'user'])
            ->where('status', 'moderation')
            ->latest()
            ->paginate(10);

        return view('ads.moderation', compact('ads'));
    }

    public function show(Ad $ad)
    {
        if (
            $ad->status !== 'published'
            && (
                !auth()->check()
                || (
                    auth()->id() !== $ad->user_id
                    && !auth()->user()->isModerator()
                    && !auth()->user()->isAdmin()
                )
            )
        ) {
            abort(404);
        }

        $ad->load(['images', 'user']);

        return view('ads.show', compact('ad'));
    }

    public function approve(Ad $ad)
    {
        $ad->status = 'published';
        $ad->save();

        return back()->with('success', 'Объявление опубликовано.');
    }

    public function reject(Ad $ad)
    {
        $ad->status = 'rejected';
        $ad->save();

        return back()->with('success', 'Объявление отклонено.');
    }
}