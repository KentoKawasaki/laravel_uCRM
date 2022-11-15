<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeSearchCustomers($query, $input=null) {

        $searchCustomers = Customer::where('kana', 'like', "{$input}%")->orWhere('tel', 'like', "{$input}%");
        if(!empty($input)) {
            if($searchCustomers->exists()) {
                return $searchCustomers;
            }
        }
    }
}
