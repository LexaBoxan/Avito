<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\AdDescriptionImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        $this->attachDescriptionImages($ad);

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

    private function attachDescriptionImages(Ad $ad): void
    {
        $paths = $this->extractStorageImagePaths($ad->description);

        if (empty($paths)) {
            return;
        }

        AdDescriptionImage::whereNull('ad_id')
            ->where('user_id', $ad->user_id)
            ->whereIn('path', $paths)
            ->update(['ad_id' => $ad->id]);
    }

    private function extractStorageImagePaths(string $html): array
    {
        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $matches);

        if (empty($matches[1])) {
            return [];
        }

        $paths = [];
        foreach ($matches[1] as $src) {
            $normalized = $this->normalizeStoragePath($src);
            if ($normalized) {
                $paths[] = $normalized;
            }
        }

        return array_values(array_unique($paths));
    }

    private function normalizeStoragePath(string $src): ?string
    {
        $parsed = parse_url($src);
        $path = $parsed['path'] ?? $src;

        if (!Str::startsWith($path, '/storage/')) {
            return null;
        }

        return $path;
    }
}
