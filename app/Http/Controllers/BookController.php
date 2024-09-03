<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookFormRequest;
use App\Models\Book;
use App\http\Services\BookService;
use Illuminate\Http\Request;
/**
 * Summary of BookController
 * the BookController Main @method are index,create,update,show,delete,store
 * There is an BookService that simplifies the work of the controller 
 * how the service is intergrated to the controller :
 * 1.there is an property of the Book controller and an instance of Bookservice 
 * 2.make a constructer using the instance
 * 3.by using the instance we can reache to the methods of the service using dependency injection
 */

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }
    /**
     * Summary of index
     * taking the response of teh bookService that will return all of the books and asign it to books
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $books = $this->bookService->getAllBooks();
        return response()->json($books, 200);
    }
    /**
     * Summary of store
     * @param \Illuminate\Http\Request $request taking request param that will be passed to the bookService to create the book using the model and return it to be the response
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(BookFormRequest $request)
    {
        $book = $this->bookService->createBook($request);
        return response()->json([
            'status'=>'success',
            'message'=>'the Book had been created succufuly',
            'Book'=>$book
        ], 201);
    }
    /**
     * Summary of show
     * @param mixed $id taking an id and passing it to the book service 
     * @return mixed|\Illuminate\Http\JsonResponse returning the book that has to be in the response
     */
    public function show($id)
    {
        $book = $this->bookService->getBook($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json([
            'status'=>'success',
            'message'=>'the Book had been found succufuly',
            'Book'=>$book
        ],200);
    }
    /**
     * Summary of update
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(BookFormRequest $request, $id)
{
    return $this->bookService->updateBook($id, $request);
}

    /**
     * Summary of destroy
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy($id)
{
    return $this->bookService->deleteBook($id);
}

}
