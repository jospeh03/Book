<?php
namespace App\Http\Services;

use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Support\Facades\Auth;
use Exception;

class BorrowService
{
    /**
     * Summary of createBorrowRecord
     * @param array $data   data given by the user to create the borrow
     * @return BorrowRecord|mixed|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse json response if the creation was failed
     *  and $bookRecord type if the borrow had created succussfully
     */
    public function createBorrowRecord(array $data)
    {
        $user = Auth::user();
        $book = Book::findOrFail($data['book_id']);

        // Check if the book is already borrowed
        if (!$book->available) {
            return response()->json([
                'message' => 'تمت استعارة الكتاب من قبلك.',
            ], 404);
        }

        // Create the borrow record
        $borrowRecord = BorrowRecord::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'borrowed_at' => now(),
            'due_date' => now()->addDays(14),
            'returned_at'=>null,
        ]);

        // Mark the book as unavailable
        $book->update(['available' => false]);

        return $borrowRecord;
    }
    /**
     * Summary of updateBorrowRecord
     * @param \App\Models\BorrowRecord $borrowRecord
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse here is the corrossponding values if the book had been returned it return json 
     * and if the book selected is already borrowed it return json also
     * otherwise it return BookRecord type
     */
    public function updateBorrowRecord(BorrowRecord $borrowRecord, array $data)
    {
        // Ensure that the book has not been returned already
        if ($borrowRecord->returned_at) {
            return response()->json([
                'message' => 'Cannot update a returned book record.',
            ], 404);
        }
        
        // If changing the book
        if (isset($data['book_id']) && $borrowRecord->book_id !== $data['book_id']) {
            $newBook = Book::findOrFail($data['book_id']);
            
            // Check if the new book is available
            if (!$newBook->available) {
                return response()->json([
                    'message' => 'The selected book is not available for borrowing.',
                ], 404);
            }
            
            // Handle the transition from old book to new book
            $borrowRecord->book->update(['available' => true]);
            $newBook->update(['available' => false]);
        
            // Update borrow record with new book details
            $borrowRecord->update([
                'book_id' => $newBook->id,
                'borrowed_at' => now(),
                'due_date' => now()->addDays(14),
            ]);
        } else {
            // For non-book related updates, just update the record
            $borrowRecord->update($data);
        }
        
        // Return the updated borrow record
        return $borrowRecord;
    }
    
    /**
     * Summary of returnBorrowRecord
     * @param mixed $borrowId
     * @return BorrowRecord|BorrowRecord[]|mixed|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse return json if there is an error otherwise return the BookRecord type
     */
    public function returnBorrowRecord($borrowId)
    {
        $borrowRecord = BorrowRecord::find($borrowId);
        if(!$borrowRecord){
            return response()->json([
                'message'=> 'the borrow was not found '],404);
        }

        // Ensure the book has not already been returned
        if ($borrowRecord->returned_at) {
            return response()->json([
                'message'=>'The book had already been returned'
                ],200);
        }

        // Set the returned_at timestamp
        $borrowRecord->update(['returned_at' => now()]);

        // Mark the book as available again
        $borrowRecord->book->update(['available' => true]);

        return $borrowRecord;
    }
}
