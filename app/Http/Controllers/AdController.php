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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Важно!
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
        $ads = auth()->user()->ads()->latest()->paginate(10);
        return view('ads.mine', compact('ads'));
    }
    public function index()
    {
        $ads = Ad::where('status', 'published')
            ->latest()
            ->paginate(12);

        return view('welcome', compact('ads'));
    }

    public function moderate()
    {
        $ads = Ad::where('status', 'moderation')->latest()->paginate(10);
        return view('ads.moderation', compact('ads'));
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
