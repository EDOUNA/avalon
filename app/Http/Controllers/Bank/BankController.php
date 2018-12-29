<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Illuminate\Http\Request;


class BankController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function transactions()
    {
        $transactions = Transactions::with('currencies')->orderBy('transaction_date', 'desc')->paginate(50);

        return view('bank.transactions', ['transactions' => $transactions]);
    }
}
