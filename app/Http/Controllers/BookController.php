<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\http\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index()
    {
        $books = $this->bookService->getAllBooks();
        return response()->json($books, 200);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $book = $this->bookService->createBook($request);
        return response()->json($book, 201);
    }

    public function show($id)
    {
        $book = $this->bookService->getBook($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json($book, 200);
    }

    public function update(Request $request, $id)
    {
        $book = $this->bookService->updateBook($id, $request);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json($book, 200);
    }

    public function destroy($id)
    {
        $deleted = $this->bookService->deleteBook($id);
        if (!$deleted) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json(null, 204);
    }
}
