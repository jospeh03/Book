<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::where('user_id', Auth::id())->get();
        return view('ratings.index', compact('ratings'));
    }



    public function store(Request $request, Book $book)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $rating=Rating::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return response()->json([
            'message'=> 'the rating had been created',
            ''=> $rating],200);

    }

    public function update(Request $request, Rating $rating)
    {
        $this->authorize('update', $rating);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $rating->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);
        return response()->json([
            'message'=> 'the rating had been updated'],200);

    }

    public function destroy(Rating $rating)
    {
        $this->authorize('delete', $rating);

        $rating->delete();

        return response()->json([
            'message'=> 'the rating had been deleted'],204);
    }
}
