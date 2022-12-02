<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::get();

        if (count($transactions) > 0) {
            return response()->json([
                'code' => 202,
                'status' => 'success',
                'message' => 'data successfully accepted',
                'data' => $transactions
            ], 202);
        }

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'data successfully accepted',
            'data' => 'no data available'
        ], 202);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'user_id' => ['required','numeric'],
            'book_id' => ['required','numeric'],
            'quantity' => ['required','numeric'],
            'total_payment' => ['required','string','max:255'],
            'payment_date' => ['required','string','date_format:d-m-Y']
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

        if (!empty($validated['payment_date'])) {
            $validated['payment_date'] = date('Y-m-d', strtotime($validated['payment_date']));
        }

        $transaction = Transaction::create($validated);

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'data successfully created',
            'data' => $transaction
        ], 202);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'transaction not found in our database'
            ], 404);
        }

        return response()->json([
            'code' => 206,
            'status' => 'success',
            'message' => 'data successfully accepted',
            'data' => $transaction
        ], 206);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = validator($request->all(), [
            'user_id' => ['nullable','numeric'],
            'book_id' => ['nullable','numeric'],
            'quantity' => ['nullable','numeric'],
            'total_payment' => ['nullable','string','max:255'],
            'payment_date' => ['nullable','string','date_format:d-m-Y']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => 'data not match with our validation',
                'data' => $validator->errors()
            ], 422);
        }

        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'transaction not found in our database'
            ], 404);
        }

        $validated = $validator->validated();

        if (!empty($validated['payment_date'])) {
            $validated['payment_date'] = date('Y-m-d', strtotime($validated['payment_date']));
        }

        $transaction->update($validated);

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'data successfully updated',
            'data' => $transaction
        ], 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        
        if (!$transaction) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'transaction not found in our database'
            ], 404);
        }
        
        $transaction->delete();

        $transactions = Transaction::get();

        if (count($transactions) > 0) {
            return response()->json([
                'code' => 202,
                'status' => 'success',
                'message' => 'data successfully removed',
                'data' => $transactions
            ], 202);
        }

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'data successfully removed',
            'data' => 'no data available'
        ], 202);
    }
}
