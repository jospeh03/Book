<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ReturnService;
use Illuminate\Support\Facades\Auth;
class ReturnController extends Controller
{
    protected $returnService;

    public function __construct(ReturnService $returnService)
    {
        $this->middleware('auth:api');
        $this->returnService = $returnService;
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $result = $this->returnService->returnBorrowedBook($user->id, $request->borrow_id);

        if ($result instanceof \Illuminate\Http\JsonResponse) {
            return $result;
        }

        return response()->json(['message' => 'Book returned successfully', 'return' => $result], 200);
    }
}
