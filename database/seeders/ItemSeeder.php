<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            [
                'item_name' => 'ノートPC',
                'memo' => 'ASUS「E410KA」E410KA-EK207WS',
                'price' => 48140,
            ],
            [
                'item_name' => 'スマートフォン',
                'memo' => 'iPhone 14',
                'price' => 119800,
            ],
            [
                'item_name' => 'USBメモリ',
                'memo' => 'トランセンド(Transcend) JetFlash 920 TS512GJF920',
                'price' => 12800,
            ],
            [
                'item_name' => 'マウス',
                'memo' => 'バッファロー マウス 無線 ワイヤレス 5ボタン 【戻る/進むボタン搭載】 小型 軽量 節電モデル 最大584日使用可能 BlueLED ブラック BSMBW315BK',
                'price' => 899,
            ],
            [
                'item_name' => 'イヤホン',
                'memo' => 'ワイヤレスイヤホン Bluetooth イヤホン 2022 ブルートゥース 最新イヤホン Bluetooth5.3+EDR搭載',
                'price' => 2890,
            ],
        ]);
    }
}
