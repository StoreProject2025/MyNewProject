<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $ratings = $product->ratings()->with('user')->get();
        return response()->json($ratings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        // Check if the user has already rated this product
        $existingRating = $user->ratings()->where('product_id', $product->id)->first();

        if ($existingRating) {
            return response()->json(['message' => 'You have already rated this product'], 409); // Conflict
        }

        $rating = $user->ratings()->create([
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $rating->load('user'); // Load user relationship for the response

        // Optional: Update average rating for the product
        // $product->updateAverageRating(); // Requires a method in Product model

        return response()->json($rating, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used for ratings
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rating $rating)
    {
        // Ensure the rating belongs to the authenticated user
        if ($rating->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rating->update(['rating' => $request->rating, 'comment' => $request->comment]);

        $rating->load('user'); // Load user relationship for the response

        // Optional: Update average rating for the product
        // $rating->product->updateAverageRating(); // Requires a method in Product model

        return response()->json($rating);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        // Ensure the rating belongs to the authenticated user
        if ($rating->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $rating->delete();

        // Optional: Update average rating for the product
        // $rating->product->updateAverageRating(); // Requires a method in Product model

        return response()->json(['message' => 'Rating deleted successfully']);
    }
}
