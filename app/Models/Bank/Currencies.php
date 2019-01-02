<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Model;

class Currencies extends Model
{
    protected $table = 'currencies';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transactions()
    {
        return $this->belongsTo('App\Models\Bank\Transactions', 'currency_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deviceTariffs()
    {
        return $this->belongsTo('App\Models\Meter\DeviceTariffs', 'currency_id', 'id');
    }
}
