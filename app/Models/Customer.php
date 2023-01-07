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

        // $searchCustomers = Customer::all();

        // dd($input, $searchCustomers, $searchCustomers->exists());
        if($input !== null) {
            $input_space_converted = mb_convert_kana($input, 's'); // 全角スペース->半角スペースに変換
            $input_splited = preg_split('/\s+/', $input_space_converted); // 空白で区切る

            // where('kana', 'like', "%{$word}%")->orWhere('tel', 'like', "%{$word}%")

            foreach( $input_splited as $word) {
                if(is_numeric($word)) {
                    $query->where('tel', 'like', "%{$word}%");
                } else {
                    $query->where('kana', 'like', "%{$word}%");
                }
                
            }

            if($query->exists()) {
                // return $query->where('kana', 'like', "{$input}%")->orWhere('tel', 'like', "{$input}%");
                return $query;
            }
            else {
                return false;
            }
        }
    }

    public function purchases() {
        return $this->hasMany(Purchase::class);
    }
}
