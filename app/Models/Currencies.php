<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currencies extends Model
{
    protected $table = 'currencies';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transactions()
    {
        return $this->belongsTo('App\Models\Transactions', 'currency_id', 'id');
    }
}
