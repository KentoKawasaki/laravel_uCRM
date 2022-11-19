<?php

namespace App\Services;

trait CheckEmptyQueryService
{

    public static function checkEmpty($scopedQuery, $columns)
    {
        $isNoResults = [
            'isShow' => $scopedQuery === false,
            'message' => '検索条件に該当する顧客は存在しません',
        ];


        $results = null;
        if(!$isNoResults['isShow']) {
            $results = $scopedQuery->select(...$columns);
        }

        return [$isNoResults, $results];
    }
}