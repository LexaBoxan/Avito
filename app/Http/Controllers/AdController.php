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

        // Сохраняем объявление
        $ad = new Ad();
        $ad->user_id = auth()->id();
        $ad->title = $validated['title'];
        $ad->description = $validated['description'];
        $ad->price = $validated['price'];
        $ad->status = 'moderation'; // по умолчанию
        $ad->save(); // обязательно ДО загрузки изображений

        // Обработка изображений (если есть)
        if ($request->hasFile('images')) {
            $coverPath = null;

            foreach ($request->file('images') as $image) {
                $path = $image->store('ads', 'public');

                $ad->images()->create([
                    'path' => 'storage/' . $path,
                ]);

                $coverPath = $coverPath ?? 'storage/' . $path;
            }

            if ($coverPath) {
                $ad->image = $coverPath;
                $ad->save();
            }
        }

        return redirect()->route('ads.create')->with('success', 'Объявление отправлено на модерацию.');
    }

    public function myAds()
    {
        $ads = auth()->user()->ads()->with('images')->latest()->paginate(10);
        return view('ads.mine', compact('ads'));
    }
    public function index()
    {
        $ads = Ad::with('images')
            ->where('status', 'published')
            ->latest()
            ->paginate(12);

        return view('welcome', compact('ads'));
    }

    public function moderate()
    {
        $ads = Ad::with(['images', 'user'])->where('status', 'moderation')->latest()->paginate(10);
        return view('ads.moderation', compact('ads'));
    }

    public function show(Ad $ad)
    {
        if ($ad->status !== 'published' && (!auth()->check() || (auth()->id() !== $ad->user_id && !auth()->user()->isModerator() && !auth()->user()->isAdmin()))) {
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
