<?php

namespace App\Http\Services;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Repository\BookRepository;

class BookService
{
    protected $BookRepo;

    public function __construct(BookRepository $BookRepository)
    {
        $this->BookRepo = $BookRepository;
    }  
    public function getAllBooks()
    {
        return Book::all();
    }

    public function getBook($id)
    {
        return Book::find($id);
    }

    public function createBook(Request $request)
    {
        $validatedData = $this->validateData($request);
        return Book::create($validatedData);
    }

    public function updateBook($id, Request $request)
    {
        $book = Book::find($id);
        if (!$book) {
            return null;
        }

        $validatedData = $this->validateData($request);
        $book->update($validatedData);
        return $book;
    }

    public function deleteBook($id)
    {
        return Book::destroy($id) > 0;
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            "title" => "sometimes|required|string|max:255|min:2",
            "author" => "sometimes|required|string|max:40|min:2",
            "description" => "nullable|string|max:255",
        ]);
    }
}
