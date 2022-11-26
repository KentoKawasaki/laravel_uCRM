<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {

        if(!is_null($request->startDate) && !is_null($request->endDate)){

            // endDateがstartDate以降であるかを確認するためのvalidation
            $validated = $request->validate([
                'endDate' => 'after:' . $request->startDate . ' 23:59',
            ]);
        }
        

        $subQuery = Order::betweenDate($request->startDate, $request->endDate);

        if ($request->type === 'perDay') {
            $subQuery->where('status', true)
                ->groupBy('id')
                ->selectRaw('id,
                sum(subtotal) as totalPerPurchase,
                DATE_FORMAT(created_at, "%Y%m%d") as date');

            $data = DB::table($subQuery)
                ->groupBy('date')
                ->selectRaw('date, sum(totalPerPurchase) as total')
                ->get(); // $dataはコレクション型

            $labels = $data->pluck('date');
            $totals = $data->pluck('total');
            
        }
        return response()->json([
            'data' => $data,
            'type' => $request->type,
            'labels' => $labels,
            'totals' => $totals,
        ], Response::HTTP_OK);
    }
}
