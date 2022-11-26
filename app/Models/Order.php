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
}
