<?php

namespace App\Services;

// use App\Models\Order;
use Illuminate\Support\Facades\DB;


class DecileService
{
    public static function decile($query)
    {
        // 階級数を定める(デシル分析では10)
        $subsections = 10;

        // 1. 購買IDごとにまとめる
        $subQuery = $query->groupBy('id')
            ->selectRaw('id, customer_id, customer_name, SUM(subtotal) as totalPerPurchase');

        // 2. 会員ごとにまとめて購入金額順にソートする
        $subQuery = DB::table($subQuery)
            ->groupBy('customer_id', 'customer_name')
            ->selectRaw('customer_id, customer_name, SUM(totalPerPurchase) as total')
            ->orderBy('total', 'desc');

        // 3. 購入順に連番を振る
        DB::statement('set @row_num = 0;');
        $subQuery = DB::table($subQuery)
            ->selectRaw('
        @row_num := @row_num + 1 as row_num,
        customer_id,
        customer_name,
        total');

        // 4. 全体の件数を数え、1/10の値や合計金額を取得
        $count = DB::table($subQuery)->count();
        $total = DB::table($subQuery)->selectRaw('SUM(total) as total')->get();
        $total = $total[0]->total; // 構成比用

        $decile = ceil($count / $subsections); // 10分の1の件数を変数に入れる

        $bindValues = [];
        $tempValue = 0;
        $whenQuery = '';

        for ($i = 1; $i <= 10; $i++) {
            array_push($bindValues, 1 + $tempValue);
            $tempValue += $decile;
            array_push($bindValues, 1 + $tempValue);

            $whenQuery .= " when ? <= row_num and row_num < ? then {$i}";
        }

        // dd($count, $decile, $bindValues);

        // 5. 10分割し、グループごとに数字を振る
        DB::statement('set @row_num = 0;');

        $selectQuery = "
            row_num,
            customer_id,
            customer_name,
            total,
            case" . $whenQuery . " end as decile";

        $subQuery = DB::table($subQuery)
            ->selectRaw($selectQuery, $bindValues); // selectRaw第2引数にバインドしたい数値(配列)を入れる


        // 6. グループごとの合計・平均
        $subQuery = DB::table($subQuery)
            ->groupBy('decile')
            ->selectRaw('decile, round(AVG(total)) as average, SUM(total) as totalPerGroup');


        // dd($subQuery);

        // 7. 構成比
        DB::statement("set @total = ${total};");
        $data = DB::table($subQuery)
            ->selectRaw('
            decile,
            average,
            totalPerGroup,
            round(100 * totalPerGroup / @total, 2) as totalRatio')->get();

        $labels = $data->pluck('decile');
        $totals = $data->pluck('totalPerGroup');

        return [$data, $labels, $totals];
    }
}
