<?php

/* AnalysisController 練習 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Order;

class AnalysisController extends Controller
{

    public function index()
    {

        $startDate = '2022-11-28';
        $endDate = '2022-11-29';

        // $period = Order::betweenDate($startDate, $endDate)
        //     ->groupBy('id')
        //     ->selectRaw('id, sum(subtotal) as total, customer_name, status, created_at')
        //     ->orderBy('created_at')
        //     ->paginate(50);

        // dd($period);

        // $subQuery = Order::betweenDate($startDate, $endDate)
        //     ->where('status', true)
        //     ->groupBy('id')
        //     ->selectRaw('id,
        //         sum(subtotal) as totalPerPurchase,
        //         DATE_FORMAT(created_at, "%Y%m%d") as date');


        // $data = DB::table($subQuery)
        //     ->groupBy('date')
        //     ->selectRaw('date, sum(totalPerPurchase) as total')
        //     ->get();

        // dd($data);


        return Inertia::render('Analysis');
    }

    // 練習用デシル分析
    public function decile()
    {

        $startDate = '2022-11-21';
        $endDate = '2022-11-30';
        // 1. 購買IDごとにまとめる
        $subQuery = Order::betweenDate($startDate, $endDate)
            ->groupBy('id')
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

        $decile = ceil($count / 10); // 10分の1の件数を変数に入れる

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
                round(100 * totalPerGroup / @total, 1) as totalRatio')->get();

        // dd($data);
    }

    // 練習用RFM分析
    public function rfm()
    {
        // RFM分析

        $startDate = '2021-12-01';
        $endDate = '2022-11-30';

        // 1. 購買ID毎にまとめる 
        $subQuery = Order::betweenDate(
            $startDate,
            $endDate
        )
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
        $rfmPrms = [
            14, 28, 60, 90, // parameters of recency 
            7, 5, 3, 2, // parameters of frequency
            500000, 300000, 150000, 50000, // parameters of monetary
        ];

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

        // dd($subQuery->get());


        // 5. ランクごとの数を計算

        $total = DB::table($subQuery)->count();

        function getCountOfRFM($query, $rfm, $order = 'asc')
        {
            // dd(DB::table($query)->rightJoin('ranks', 'ranks.rank', '=', $rfm)->get());

            return DB::table($query)
                ->rightJoin('ranks', 'ranks.rank', '=', $rfm)
                ->groupBy('rank')
                ->selectRaw('rank as' . $rfm . ', count(' . $rfm . ')')
                ->orderBy($rfm, $order)
                ->pluck('count(' . $rfm . ')');
        }

        $rCount = getCountOfRFM($subQuery, 'r');
        $fCount = getCountOfRFM($subQuery, 'f');
        $mCount = getCountOfRFM($subQuery, 'm');

        $eachCount = []; // Vue側に渡す配列を定義(初期値は空の配列)
        $rank = 5; // rankの初期値は5

        for ($i = 0; $i < 5; $i++) {
            array_push($eachCount, [
                'rank' => $rank,
                'r' => $rCount[$i],
                'f' => $fCount[$i],
                'm' => $mCount[$i],
            ]);

            $rank--;
        }

        // dd($subQuery);
        // dd($total, $eachCount, $rCount, $fCount, $mCount);

        // 6. RとFで2次元で表示

        // concatで文字列結合

        $selectRFQuery = 'concat("r_", rank) as rRank';
        for ($i = 5; $i >= 1; $i--) {
            $selectRFQuery .= ', count(case when f = ' . $i . ' then 1 end) as f_' . $i;
        }

        // $data = DB::table($subQuery)
        //     ->rightJoin('ranks', 'ranks.rank', '=', 'r')
        //     ->groupBy('rank')
        //     ->selectRaw($selectRFQuery)
        //     ->orderBy('rRank', 'desc')
        //     ->get();

        $data = DB::table($subQuery)
            ->rightJoin('ranks', 'ranks.rank', '=', 'm')
            ->whereNotNull('m')
            ->groupBy('rank', 'r', 'f')
            ->selectRaw('concat("mRank", rank) as mRank , r, f, count(r) as r_n')
            ->orderBy('mRank', 'asc')
            ->orderBy('r', 'asc')
            ->orderBy('f', 'asc')
            ->get();


        // dd($data->where('mRank', $keys[0])->all());

        // function getRFMArray($collection, $eachCount, $arr = null)
        // {
        //     if ($arr = null) {
        //         $arr = [];
        //     }

        //     $mRanks = [];
        //     foreach ($eachCount as $index => $item) {
        //         if ($item['m'] === 0) {
        //             continue;
        //         }
        //         $mRanks[] = $index + 1;
        //     }

        //     // dd($mRanks);

        //     foreach ($mRanks as $mRank) {
        //         // $arr['mRank' . $mRank] = $collection->(null, null)->toArray();
        //     }

        //     return $arr;
        // }

        $data = array_values($data->groupBy('mRank')->toArray());
        dd($data);
        // dd(getRFMArray($data, $eachCount));

        // function labelsGenerator($numOfLabels, $suffix = '', $labels = null)
        // {
        //     if ($labels === null) {
        //         $labels = [];
        //     }
        //     for ($i = 1; $i <= $numOfLabels; $i++) {
        //         array_push($labels, $suffix . 'rank' . $i);
        //     }

        //     return $labels;
        // }

        // $rLabels = labelsGenerator(5, 'r_');
        // $fLabels = labelsGenerator(5, 'f_');

        // $scatterLabels = [
        //     'rLabels' => $rLabels,
        //     'fLabels' => $fLabels
        // ];


        // $r_count = 0;
        // $f_count = 0;

        // foreach($data as $datum) {
        //     if($datum->r === 2) {
        //         $r_count += $datum->r_n;
        //     }
        //     if ($datum->f === 2) {
        //         $f_count += $datum->f_n;

        //     }
        // }

        dd($data, $eachCount);
    }
}
