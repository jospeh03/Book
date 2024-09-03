<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookFilterController extends Controller
{
    public function filter(Request $request)
    {
        $query = Book::query();

        // فلترة حسب المؤلف
        if ($request->has('author') && !empty($request->author)) {
            $query->where('author', 'like', '%' . $request->author . '%');
        }

        // فلترة حسب التصنيف
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        // فلترة حسب توفر الكتاب
        if ($request->has('available') && $request->available == 'true') {
            $query->where('is_available', true); // تأكد من وجود عمود `is_available` في جدول الكتب
        }

        $books = $query->get();

        return response()->json($books);
    }
}
