<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function applyItems($instance, $items, $numberOfItems)
    {
        $itemsArray = $items->random(rand(1, $numberOfItems))->pluck('id')->all();
        foreach ($itemsArray as $id) {
            $instance->items()->attach(
                $id,
                ['quantity' => rand(1, 100)]
            );
        }
    }

    public function getRandomDate($startDate, $endDate) {

        // $startDateから$endDateまでの期間から、日時をランダムで選ぶ
        $start = new Carbon($startDate);
        $startUnix = $start->format('U'); // 日付と時刻を format('U') でUNIXタイムスタンプへ変換しておく
        $end = new Carbon($endDate);
        $endUnix = $end->format('U'); // 日付と時刻を format('U') でUNIXタイムスタンプへ変換しておく

        return mt_rand($startUnix, $endUnix);
    }

    public function run()
    {
        $numOfData = 30000;
        $numOfChunk = floor($numOfData / 10);

        $purchaseData = [];
        $item_purchaseData = [];

        $startDate = '2010-01-01 00:00:00';
        $endDate = '2024-12-31 23:59:59';

        $customers = DB::table('customers')->pluck('id'); // Customerテーブルに保存された全顧客IDを取得

        // Item Modelのデータとデータ個数を取得
        $items = DB::table('items')->get();
        $itemCount = $items->count();

        // ダミーデータを配列に格納
        for ($i = 1; $i < $numOfData + 1; $i++) {

            // 用意しておいた開始・終了日時のUNIXタイムスタンプを使用してランダムな数値を生成
            $randDate = $this->getRandomDate($startDate, $endDate);

            $date = new Carbon($randDate);
            $purchaseData[] = [
                'customer_id' => $customers->random(),
                'status' => (bool)mt_rand(0, 1),
                'created_at' => $date->toDateTimeString(),
                'updated_at' => $date->toDateTimeString(),
            ];

            $itemIds = $items->random(mt_rand(1, $itemCount))->pluck('id')->toArray();
            foreach($itemIds as $item_id) {

                $item_purchaseData[] = [
                    'item_id' => $item_id,
                    'purchase_id' => $i,
                    'quantity' => mt_rand(1, 100),
                    'created_at' => $date->toDateTimeString(),
                    'updated_at' => $date->toDateTimeString(),
                ];
            }
        }

        $purchases = array_chunk($purchaseData, $numOfChunk);
        foreach ($purchases as $purchase) {

            DB::table('purchases')->insert($purchase);
        }

        $items_purchases = array_chunk($item_purchaseData, $numOfChunk);
        foreach ($items_purchases as $item_purchase) {
 
            DB::table('item_purchase')->insert($item_purchase);
        }

        // DB::table('purchases')->chunkById($numOfChunk, function($purchases) use ($items, $itemCount) {
        //     foreach ($purchases as $purchase) {
        //         $this->applyItems(Purchase::find($purchase->id), $items, $itemCount);
        //     }
        // });
    }
}
