<?php

namespace App\Http\Services;

use App\Models\BorrowRecord;
use Illuminate\Support\Facades\Auth;

class ReturnService
{
    public function returnBorrowedBook($userId, $borrowId)
    {
        // Find the borrow record
        $borrowRecord = BorrowRecord::where('user_id', $userId)
                                    ->where('id', $borrowId)
                                    ->whereNull('returned_at')
                                    ->first();

        // Check if the borrow record exists and is not already returned
        if (!$borrowRecord) {
            return response()->json(['message' => 'Borrow record not found or already returned'], 404);
        }

        // Update the return date
        $borrowRecord->returned_at = now();
        $borrowRecord->save();

        return $borrowRecord;
    }
}
