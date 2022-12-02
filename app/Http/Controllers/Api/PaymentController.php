<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->is_admin == 'true') {
            return response()->json([
                'code' => 202,
                'status' => 'success',
                'message' => 'data successfully accepted',
                'data' => $request->user()
            ], 202);
        }

        $validator = validator($request->all(), [
            'book_id' => ['required','numeric'],
            'quantity' => ['required','numeric']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => 'data not match with our validation',
                'data' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $book = Book::find($validated['book_id']);

        if (!$book) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'book not found in our database'
            ], 404);
        }

        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'book_id' => $book->id,
            'quantity' => $validated['quantity'],
            'total_payment' => $book->price * $validated['quantity'],
            'payment_date' => now()->format('Y-m-d')
        ]);

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'Data created successfully',
            'data' => $transaction
        ], 202);
    }
}
