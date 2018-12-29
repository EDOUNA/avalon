<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\Transactions;


class BankController extends Controller
{
    protected $paginationLimit = 50;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function transactions()
    {
        if (request()->has('uncategorized')) {
            $transactions = Transactions::with('currencies')->with('bankaccounts')
                ->whereNull('category_id')
                ->orderBy('transaction_date', 'desc')
                ->paginate($this->paginationLimit)
                ->appends('uncategorized', true);
        } else {
            $transactions = Transactions::with('currencies')->with('bankaccounts')
                ->orderBy('transaction_date', 'desc')
                ->paginate($this->paginationLimit);
        }

        foreach ($transactions as $k => $t) {
            $t['spanClass'] = 'text-danger';
            if ($t['amount'] > 0) {
                $t['spanClass'] = 'text-success';
            }
        }

        $categories = new CategoriesController();

        return view('bank.transactions', ['transactions' => $transactions, 'categories' => $categories->listCategories()]);
    }
}
