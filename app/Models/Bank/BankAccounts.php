<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Model;

class BankAccounts extends Model
{
    protected $table = 'bank_accounts';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transactions()
    {
        return $this->belongsTo('App\Models\Transactions', 'primary_bank_account', 'id');
    }
}
