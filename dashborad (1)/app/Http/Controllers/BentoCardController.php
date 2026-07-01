<?php

namespace App\Http\Controllers;

use App\Models\BentoCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BentoCardController extends Controller
{
    public function index()
    {
        $bentoCards = BentoCard::ordered()->get();
        return view('bento_cards.index', compact('bentoCards'));
    }

    public function create()
    {
        return view('bento_cards.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tag' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $request->sort_order ?? 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('bento_cards', $filename, 'uploads');
            $data['image_path'] = $filename;
        }

        BentoCard::create($data);

        return redirect()->route('bento-cards.index')->with('success', 'Bento Card created successfully.');
    }

    public function edit(BentoCard $bentoCard)
    {
        return view('bento_cards.edit', compact('bentoCard'));
    }

    public function update(Request $request, BentoCard $bentoCard)
    {
        $request->validate([
            'tag' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $request->sort_order ?? 0;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($bentoCard->image_path) {
                Storage::disk('uploads')->delete('bento_cards/' . $bentoCard->image_path);
            }

            $image = $request->file('image');
            $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('bento_cards', $filename, 'uploads');
            $data['image_path'] = $filename;
        }

        $bentoCard->update($data);

        return redirect()->route('bento-cards.index')->with('success', 'Bento Card updated successfully.');
    }

    public function destroy(BentoCard $bentoCard)
    {
        if ($bentoCard->image_path) {
            Storage::disk('uploads')->delete('bento_cards/' . $bentoCard->image_path);
        }
        $bentoCard->delete();
        return redirect()->route('bento-cards.index')->with('success', 'Bento Card deleted successfully.');
    }
}
