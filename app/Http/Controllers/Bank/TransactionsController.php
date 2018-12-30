<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\Transactions;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCategory(Request $request)
    {
        $transaction = Transactions::where('id', $request->get('transaction'))->first();
        if (null === $transaction) {
            return response()->json(['status' => 'Could not find transaction ID'], 400);
        }

        $transaction->category_id = $request->get('category');
        $transaction->save();

        return response()->json(['status' => 'OK']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategorizedScore()
    {
        $noCategory = Transactions::whereNull('category_id')->count();
        $totalTransactions = Transactions::count();

        return response()->json(['uncategorized' => $noCategory, 'total' => $totalTransactions]);
    }
}
