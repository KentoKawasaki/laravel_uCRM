<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeSearchCustomers($query, $input=null) {

        $searchCustomers = Customer::where('kana', 'like', "{$input}%")->orWhere('tel', 'like', "{$input}%");
        // dd($input, $searchCustomers, $searchCustomers->exists());
        if(!empty($input)) {
            if($searchCustomers->exists()) {
                return $searchCustomers;
            } else {
                return false;
            }
        }
    }

    public function purchases() {
        return $this->hasMany(Purchase::class);
    }
}
