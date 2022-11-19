<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Customer;
use App\Services\CheckEmptyQueryService;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

class Methods
{
    use CheckEmptyQueryService;
}
$methods = new Methods();

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/searchCustomers', function (Request $request) use ($methods) {
    // dd($request);
    $searchQuery = Customer::searchCustomers($request->search);
    $columns = ['id', 'customer_name', 'kana', 'tel'];

    list($noResults, $searchedCustomers) = $methods->checkEmpty($searchQuery, $columns);

    $COUNT_MAX = 50;

    function CountMessage($eloquent, $limitCount) {

        if($eloquent !== null && $eloquent->count() > $limitCount) {
            return '検索条件を満たす結果が多すぎます。検索キーワードをより詳細に記入してください。';
        }

        return null;
    }

    $countOverMessage = CountMessage($searchedCustomers, $COUNT_MAX);

    // return $searchCustomers->select('id', 'customer_name', 'kana', 'tel')->paginate(50);
    return [
        'searchedCustomers' => $searchedCustomers !== null ? $searchedCustomers->get() : null,
        'noResults' => $noResults,
        'countOverMessage' => $countOverMessage,
        
    ];
    // return Inertia::render('@Components/MicromodalComponent.vue', [
    //     'searchedCustomers' => $searchedCustomers,
    //     'noResults' => $noResults,
    // ]);
});
