<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * Get the customer that owns the payment.
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
}
