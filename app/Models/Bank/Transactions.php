<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = 'transactions';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bankaccounts()
    {
        return $this->hasOne('App\Models\Bank\BankAccounts', 'id', 'primary_bank_account');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currencies()
    {
        return $this->hasOne('App\Models\Bank\Currencies', 'id', 'currency_id');
    }
}
