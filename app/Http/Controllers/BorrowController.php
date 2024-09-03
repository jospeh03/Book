<?php
namespace App\Http\Controllers;
use App\Models\BorrowRecord;
use App\Http\Requests\BorrowRecordFormRequest;
use App\Http\Services\BorrowService;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;

class BorrowController extends Controller
{
    protected $borrowService;
    /**
     * Summary of __construct
     * @param \App\Http\Services\BorrowService $borrowService the user has to be authencticated to be authrized to borrow
     */
    public function __construct(BorrowService $borrowService)
    {
        $this->middleware('auth:api'); // Ensure only authenticated users can access these routes
        $this->borrowService = $borrowService;
    }
    /**
     * Summary of index gets the user borrowing of all time 
     * @return mixed|\Illuminate\Http\JsonResponse return json response with message
     */
    public function index()
    {
        $user = Auth::user();
        $borrowRecords = BorrowRecord::where('user_id', $user->id)->get();
        return response()->json([
        'message'=> 'this is all of the borrows that had you done',
        $borrowRecords,
        ], 200);
    }
    /**
     * Summary of show
     * @param mixed $id id of the borrow that he wants 
     * @return mixed|\Illuminate\Http\JsonResponse response of the borrow as json with a messsage
     */
    public function show($id)
    {
    //checks for the user if he was the user that make the borrow and search for the id that he searching for 
        $borrowRecord = BorrowRecord::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            return response()->json([
                'message'=> 'this is all of the borrows that had you done',
                $borrowRecord,
                ], 200);
    }
    /**
     * Summary of store
     * @param \App\Http\Requests\BorrowRecordFormRequest $request handle a request of the type BorrowRecordForm Request 
     * @return mixed|\Illuminate\Http\JsonResponse json response of the book that had been succufly borrowed
     */
    public function store(BorrowRecordFormRequest $request)
    {
        $borrowRecord = $this->borrowService->createBorrowRecord($request->validated());

        return response()->json([
            'message' => 'تم استعارة الكتاب بنجاح.',
            'borrow_record' => $borrowRecord,
        ], 201);
    }
    /**
     * Summary of update
     * @param \App\Http\Requests\BorrowRecordFormRequest $request handle a request of the type BorrowRecordForm Request 
     * @param mixed $id id of the borrow that he wants to update
     * @return mixed|\Illuminate\Http\JsonResponse return a json response if the proccess status
     */
    public function update(BorrowRecordFormRequest $request, $id)
    {
        $user = Auth::user();
    
        // Find the borrow record by its ID and ensure it belongs to the authenticated user
        $borrowRecord = BorrowRecord::where('id', $id)
                                    ->where('user_id', $user->id)
                                    ->first();
    
        // If no matching record is found, return an unauthorized error
        if (!$borrowRecord) {
            return response()->json(['message' => 'Unauthorized. You cannot update a record for a book you did not borrow.'], 403);
        }
    
        // If the book has already been returned
        if ($borrowRecord->returned_at) {
            return response()->json(['message' => 'Cannot update the record. The book has already been returned.'], 400);
        }
    
        // Use the validated data from the Form Request
        $validatedData = $request->validated();
    
        // Pass the data to the service layer to handle the update logic
        $updatedBorrowRecord = $this->borrowService->updateBorrowRecord($borrowRecord, $validatedData);
    
        return response()->json([
            'message' => 'Borrow record updated successfully.',
            'borrow_record' => $updatedBorrowRecord,
        ], 200);
        //there is a bug here can't update 
    }
        /**
         * Summary of return in the return here is like a delete but not a delete just setting the return date to the date of the request
         * @param mixed $id id of the borrow that had to be returned
         * @return mixed|\Illuminate\Http\JsonResponse return the response of json if the proccess status
         */
        public function return($id)
    {
        $user = Auth::user();
        $borrowRecord = BorrowRecord::where('id', $id)
                                    ->where('user_id', $user->id)
                                    ->firstOrFail();
    
        if ($borrowRecord->returned_at) {
            return response()->json(['message' => 'This book has already been returned.'], 400);
        }
    
        // Update the record to mark as returned
        $borrowRecord->update(['returned_at' => now()]);
        $borrowRecord->book->update(['available' => true]);
    
        return response()->json(['message' => 'Book returned successfully.'], 200);
    }
    
    
}
