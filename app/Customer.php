<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * Get the payments for the customer.
     */
    public function payments()
    {
        return $this->hasMany('App\Payment');
    }
}
