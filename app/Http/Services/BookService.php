<?php

namespace App\Http\Services;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Repository\BookRepository;
/**
 * In this Service 
 * it takes all the input from the controller to keep him as simple and clean as enough
 * 
 */

class BookService
{
    protected $BookRepo;

    public function __construct(BookRepository $BookRepository)
    {
        $this->BookRepo = $BookRepository;
    }
    // return the getAllBooks from the controller request   
    public function getAllBooks()
    {
        return Book::all();
    }
    //get a book by his id and find it in the model
    public function getBook($id)
    {
        return Book::find($id);
    }
    /**
     * Summary of createBook
     * @param \Illuminate\Http\Request $request take the request and vlidate the Data using the class validateData
     * @return Book|\Illuminate\Database\Eloquent\Model return the book object that was created
     */
    public function createBook(Request $request)
    {
        $validatedData = $this->validateData($request);
        return Book::create($validatedData);
    }
    /**
     * Summary of updateBook 
     * @param \Illuminate\Http\Request and the @param id
     * to find a book id and update it's data after validating
     * @return Book|\Illuminate\Database\Eloquent\Model or message if the book is not found
     */

     public function updateBook($id, Request $request)
     {
         $book = Book::find($id);
         if (!$book) {
             return response()->json(['message' => 'Book not found'], 404);
         }
         
         $validatedData = $this->validateData($request);
         $book->update($validatedData);
         
         return response()->json([
             'status' => 'success',
             'message' => 'The book has been successfully updated.',
             'Book' => $book,
         ], 200);
     }
     
    /**
     * Summary of deleteBook delete a book
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function deleteBook($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        
        $book->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'The book has been successfully deleted.',
        ], 204);  // 204 No Content for successful deletion
    }
    
    /**
     * Summary of validateData check if the request was valid
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            "title" => "sometimes|required|string|max:255|min:2",
            "author" => "sometimes|required|string|max:40|min:2",
            "description" => "sometimes|string|max:255",
            "published_at" => "sometimes|date_format:Y-m-d",
        ]);
    }
    
    
}
