<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vanguard\AttributeRating;
use Vanguard\Http\Controllers\Controller;
use Vanguard\RateableAttribute;
use Vanguard\RateableItem;
use Vanguard\Rating;

class RateableItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ratings.manage', ['except' => ['showRatingForm', 'submitRating']]);
    }

    public function index(Request $request)
    {
        $query = RateableItem::query()
            ->withCount('ratings')
            ->with(['attributes' => function ($query) {
                $query->withAvg('ratings as average_rating', 'rating')
                    ->withCount('ratings');
            }]);
    
        // Add overall average rating using a subquery
        $query->addSelect([
            'overall_average_rating' => DB::table('attribute_ratings')
                ->join('rateable_attributes', 'attribute_ratings.rateable_attribute_id', '=', 'rateable_attributes.id')
                ->whereColumn('rateable_attributes.rateable_item_id', 'rateable_items.id')
                ->selectRaw('COALESCE(AVG(rating), 0)')
        ]);
    
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }
    
        $items = $query->latest()->paginate(20);
    
        return view('ratings.index', compact('items'));
    }
    
    public function create()
    {
        return view('ratings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date|after:now',
            'status' => 'boolean',
            'attributes' => 'required|array|min:1',
            'attributes.*.name' => 'required|string|max:255',
            'attributes.*.description' => 'nullable|string|max:1000'
        ]);
    
        try {
            DB::beginTransaction();
    
            // Create the rateable item
            $item = RateableItem::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'expires_at' => $validated['expires_at'],
                'created_by' => auth()->id(),
                'status' => $request->boolean('status')
            ]);
    
            // Create attributes
            $attributes = collect($validated['attributes'])->map(function ($attributeData) {
                return [
                    'name' => $attributeData['name'],
                    'description' => $attributeData['description'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            });
    
            $item->attributes()->createMany($attributes->all());
    
            DB::commit();
    
            return redirect()
                ->route('ratings.show', $item->id)
                ->with('success', 'Item created successfully.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Failed to create item: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $item = RateableItem::with([
            'attributes' => function($query) {
                $query->withCount('ratings')
                      ->withAvg('ratings as average_rating', 'rating');
            },
            'attributes.ratings',
            'ratings' => function($query) {
                $query->with('attributeRatings.attribute')
                      ->latest();
            }
        ])->findOrFail($id);
    
        return view('ratings.show', compact('item'));
    }

    public function edit($id)
    {
        $item = RateableItem::findOrFail($id);
        return view('ratings.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = RateableItem::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date|after:now',
            'status' => 'boolean'
        ]);

        $item->update($validated);

        return redirect()->route('ratings.show', $item->id)
            ->with('success', 'Item updated successfully.');
    }

    public function destroy($id)
    {
        $item = RateableItem::findOrFail($id);
        
        if ($item->ratings()->count() > 0) {
            return back()->with('error', 'Cannot delete item with existing ratings.');
        }
        
        $item->delete();
        
        return redirect()->route('ratings.index')
            ->with('success', 'Item deleted successfully.');
    }

    public function showRatingForm($slug)
    {
        $item = RateableItem::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        if ($item->isExpired()) {
            return view('ratings.expired');
        }

        return view('ratings.form', compact('item'));
    }

    public function submitRating(Request $request, $slug)
    {
        $item = RateableItem::with('attributes')->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
    
        if ($item->isExpired()) {
            return back()->with('error', 'This rating form has expired.');
        }
    
        // Validate the request
        $validated = $request->validate([
            'attributes' => 'required|array|min:1',
            'attributes.*' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
            'rater_name' => 'required|string|max:255',
            'rater_email' => 'required|email|max:255',
            'location' => 'required|string|max:255'
        ]);
    
        try {
            DB::beginTransaction();
    
            // Create the main rating
            $rating = Rating::create([
                'rateable_item_id' => $item->id,
                'comment' => $validated['comment'] ?? null,
                'rater_name' => $validated['rater_name'],
                'rater_email' => $validated['rater_email'],
                'location' => $validated['location'], 
                'ip_address' => $request->ip(),
                'hidden' => false
            ]);
    
            // Create individual attribute ratings
            foreach ($validated['attributes'] as $attributeId => $score) {
                // Verify that the attribute belongs to this item
                if ($item->attributes->contains($attributeId)) {
                    AttributeRating::create([
                        'rating_id' => $rating->id,
                        'rateable_attribute_id' => $attributeId,
                        'rating' => $score
                    ]);
                }
            }
    
            DB::commit();
    
            return back()->with('success', 'Thank you for your rating!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rating submission failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Failed to submit rating. Please try again.')
                ->withInput();
        }
    }

    public function generateShareableLink($id)
    {
        $item = RateableItem::findOrFail($id);
        $link = route('ratings.form', $item->slug);

        return response()->json([
            'success' => true,
            'link' => $link
        ]);
    }

    public function hideRating($id)
{
    try {
        $rating = Rating::findOrFail($id);
        $rating->update(['hidden' => true]);

        return back()->with('success', 'Rating has been hidden successfully.');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to hide rating.');
    }
}
}
