<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = 'transactions';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currencies()
    {
        return $this->hasOne('App\Models\Currencies', 'id', 'currency_id');
    }
}
