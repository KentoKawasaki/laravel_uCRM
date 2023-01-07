<?php

namespace App\Services;

// use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class RFMService
{
    public static function rfm($query, $rfmPrms)
    {
        // RFM分析

        // 1. 購買ID毎にまとめる 
        $subQuery = $query
            ->groupBy('id')
            ->selectRaw('id, customer_id, 
                customer_name, SUM(subtotal) as 
                totalPerPurchase, created_at');

        // 2. 会員毎にまとめて最終購入日、回数、合計金額を取得 

        // max()で最新日を取得
        // datediff()で日付の差分を取得
        $subQuery = DB::table($subQuery)
            ->groupBy('customer_id', 'customer_name')
            ->selectRaw('customer_id, customer_name, 
                max(created_at) as recentDate, 
                datediff(now(), max(created_at)) as recency, 
                count(customer_id) as frequency, 
                sum(totalPerPurchase) as monetary');


        // 3. RFMランクを定義
        // $rfmPrms = [
        //     14, 28, 60, 90, // parameters of recency 
        //     7, 5, 3, 2, // parameters of frequency
        //     300000, 200000, 100000, 30000, // parameters of monetary
        // ];

        // 4. 会員ごとのRFMランクを計算

        $selectQuery = 'customer_id, customer_name, 
        recentDate, recency, frequency, monetary, 
        case 
        when recency < ? then 5 
        when recency < ? then 4 
        when recency < ? then 3 
        when recency < ? then 2 
        else 1 end as r, 
        case 
        when ? <= frequency then 5 
        when ? <= frequency then 4 
        when ? <= frequency then 3 
        when ? <= frequency then 2 
        else 1 end as f, 
        case 
        when ? <= monetary then 5 
        when ? <= monetary then 4 
        when ? <= monetary then 3 
        when ? <= monetary then 2 
        else 1 end as m';


        $subQuery = DB::table($subQuery)->selectRaw($selectQuery, $rfmPrms);
        // dd($subQuery);
        Log::debug($subQuery->get());


        // 5. ランクごとの数を計算

        $totals = DB::table($subQuery)->count();

        function getCountOfRFM($query, $rfm, $order = 'asc')
        {
            return DB::table($query)
                ->rightJoin('ranks', 'ranks.rank', '=', $rfm)
                ->groupBy('rank')
                ->selectRaw('rank as' . $rfm . ', count(' . $rfm . ')')
                ->orderBy($rfm, $order)
                ->pluck('count(' . $rfm . ')');
        }

        $rCount = getCountOfRFM($subQuery, 'r');

        Log::debug($rCount);

        $fCount = getCountOfRFM($subQuery, 'f');
        $mCount = getCountOfRFM($subQuery, 'm');

        $eachCount = []; // Vue側に渡す配列を定義(初期値は空の配列)
        // $rank = 1; // rankの初期値は5

        for ($i = 4; $i >= 0; $i--) {
            array_push($eachCount, [
                'rank' => $i + 1,
                'r' => $rCount[$i],
                'f' => $fCount[$i],
                'm' => $mCount[$i],
            ]);
        }

        // dd($subQuery);
        // dd($total, $eachCount, $rCount, $fCount, $mCount);

        // 6. RとFで2次元で表示

        // concatで文字列結合

        // RF分析表用データ
        // $selectRFQuery = 'concat("r_", rank) as rRank';
        // for ($i = 5; $i >= 1; $i--) {
        //     $selectRFQuery .= ', count(case when f = ' . $i . ' then 1 end) as f_' . $i;
        // }

        // $data = DB::table($subQuery)
        //     ->rightJoin('ranks', 'ranks.rank', '=', 'r')
        //     ->groupBy('rank')
        //     ->selectRaw($selectRFQuery)
        //     ->orderBy('rRank', 'desc')
        //     ->get();


        // 散布図用データ
        $data = DB::table($subQuery)
            ->rightJoin('ranks', 'ranks.rank', '=', 'm')
            ->whereNotNull('m')
            ->groupBy('rank', 'r', 'f')
            ->selectRaw('concat("mRank", rank) as mRank , r, f, count(r) as r_n')
            ->orderBy('mRank', 'asc')
            ->orderBy('r', 'asc')
            ->orderBy('f', 'asc')
            ->get();

        // dd($data, $eachCount);
        $dataArray = $data->groupBy('mRank')->toArray();
        $rfmData = array_values($dataArray);
        $mRanks = array_keys($dataArray);

        return [$eachCount, $mRanks, $rfmData, $totals];
    }
}
