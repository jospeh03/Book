<?php
namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use Illuminate\Http\Request;

class AdminBorrowController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'admin']);
    }

    public function index()
    {
        $borrowRecords = BorrowRecord::with(['book', 'user'])->get();
        return response()->json([
            'message'=>'This is the list of users borrowing books',
            $borrowRecords],200);
    }

    public function show($id)
    {
        $borrowRecord = BorrowRecord::with(['book', 'user'])->findOrFail($id);
        return response()->json(['message'=>'this is the desired user with his borrowing',
            $borrowRecord],200);
    }

    public function update(Request $request, $id)
    {
        $borrowRecord = BorrowRecord::findOrFail($id);

        $request->validate([
            'returned_at' => 'nullable|date',
            'due_date' => 'nullable|date',
        ]);

        $borrowRecord->update($request->only(['returned_at', 'due_date']));

        return response()->json($borrowRecord);
    }

    public function destroy($id)
    {
        $borrowRecord = BorrowRecord::findOrFail($id);
        $borrowRecord->delete();

        return response()->json(null, 204);
    }
}
