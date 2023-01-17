<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\Subtotal;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new Subtotal);
    }

    // 期間を指定する関数
    public function scopeBetweenDate($query, $startDate = null, $endDate = null)
    {

        
        // $startDate: 期間の始め
        // $endDate: 期間の終わり

        // $startDateと$endDateの両方が指定されてないとき
        if (is_null($startDate) && is_null($endDate)) {
            return $query;
        }

        // $startDateのみ指定されたとき
        if (!is_null($startDate) && is_null($endDate)) {
            return $query->where('created_at', ">=", $startDate);
        }

        // $endDateのみ指定されたとき
        if (is_null($startDate) && !is_null($endDate)) {
            // $endDateに指定された日付の時刻は00:00:00より、$endDateで指定した日付の情報が入らいない
            // $endDateに１日追加して、$endDateで指定した日付の情報も取得
            $endDateTomorrow = Carbon::parse($endDate)->addDays(1); 
            return $query->where('created_at', '<=', $endDateTomorrow);
        }

        // $startDateと$endDateの両方が指定されたとき
        if (!is_null($startDate) && !is_null($endDate)) {
            $endDateTomorrow = Carbon::parse($endDate)->addDays(1);
            return $query->where('created_at', ">=", $startDate)
                ->where('created_at', '<=', $endDateTomorrow);
        }
    }

    public function scopeSearchOrders($query, $input=null) {

        // $searchCustomers = Customer::all();

        // dd($input, $searchCustomers, $searchCustomers->exists());
        if($input !== null) {
            $input_space_converted = mb_convert_kana($input, 's'); // 全角スペース->半角スペースに変換
            $input_splited = preg_split('/\s+/', $input_space_converted); // 空白で区切る

            // where('kana', 'like', "%{$word}%")->orWhere('tel', 'like', "%{$word}%")

            foreach( $input_splited as $word) {
                // if(is_numeric($word)) {
                //     $query->where('tel', 'like', "%{$word}%");
                // } else {
                //     $query->where('customer_name', 'like', "%{$word}%");
                // }
                $query->where('customer_name', 'like', "%{$word}%");
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
}
