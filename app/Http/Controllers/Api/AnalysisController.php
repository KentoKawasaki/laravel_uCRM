<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\AnalysisService;
use App\Services\DecileService;
use App\Services\RFMService;
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

            $startDate = Carbon::parse($request->startDate);
            // $endDate = Carbon::parse($request->endDate);
            // dd($startDate->format('Y-m-d'), $endDate);
            $validated = $request->validate([
                'endDate' => 'after:' . $startDate->format('Y/m/d'),
                // 'endMonth' => 'after:' . $request->startDate->format('Y/m')
            ]);
        }
        

        $subQuery = Order::betweenDate($request->startDate, $request->endDate);

        if ($request->type === 'perDay') {
            list($data, $labels, $totals) = AnalysisService::perDay($subQuery);
        }

        if ($request->type === 'perMonth') {
            list($data, $labels, $totals) = AnalysisService::perMonth($subQuery);
        }

        if ($request->type === 'perYear') {
            list($data, $labels, $totals) = AnalysisService::perYear($subQuery);
        }

        if ($request->type === 'decile') {
            list($data, $labels, $totals) = DecileService::decile($subQuery);
        }

        if ($request->type === 'rfm') {
            list($eachCount, $mRanks, $rfmData, $totals) = RFMService::rfm($subQuery, $request->rfmPrms);
            $isMRanks = $request->isMRanks;
            $selectedRfmData = [];

            foreach($mRanks as $index => $mRank) {
                // dd($index, $mRank, $isMRanks[$mRank], ($isMRanks[$mRank] === 'true'));
                if ($isMRanks[$mRank] === 'true') {
                    $selectedRfmData[] = $rfmData[$index];
                }
            }

            // dd($mRanks, $rfmData, $selectedRfmData);

            return response()->json([
                'eachCount' => $eachCount,
                'mRanks' => $mRanks,
                'rfmData' => $selectedRfmData,
                'type' => $request->type,
                'totals' => $totals,

            ], Response::HTTP_OK);
        }

        return response()->json([
            'data' => $data,
            'type' => $request->type,
            'labels' => $labels,
            'totals' => $totals,
        ], Response::HTTP_OK);
    }
}
