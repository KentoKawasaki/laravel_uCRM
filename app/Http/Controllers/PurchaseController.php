<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Services\CheckEmptyQueryService;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use CheckEmptyQueryService;

    public function index(Request $request)
    {
        // dd(Order::paginate(50));

        

        // $orders = Order::groupBy('id')
        //     ->selectRaw('id, sum(subtotal) as total,
        //         customer_name, status, created_at')
        //     ->orderByDesc('created_at')
        //     ->paginate(50);

        // dd($searchQuery === false);
        
        $orderQuery = Order::searchOrders($request->search);

        $columns = ['id', 'sum(subtotal) as total', 'customer_name', 'status', 'created_at'];


        $noResults = [
            'isShow' => $orderQuery === false,
            'message' => '検索条件に該当する顧客は存在しません',
        ];


        $orders = null;
        if(!$noResults['isShow']) {
            $orders = $orderQuery->groupBy('id')
                ->selectRaw('id, sum(subtotal) as total,
                    customer_name, status, created_at')
                ->orderByDesc('created_at');
            
            if ($request->search == null){
                $orders = $orders->paginate(50);
            } else {
                $orders = [
                    'data' => $orders->get()
                ];
            }
        }
        // list($noResults, $orders) = $this->checkEmpty($orderQuery, $columns);
        // dd($noResults, $orders);

        return Inertia::render('Purchases/Index', [
            'orders' => $orders,
            'noResults' => $noResults,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $customers = Customer::select('id', 'customer_name', 'kana')->get();
        $items = Item::select('id', 'item_name', 'price')->where('is_selling', true)->get();

        return Inertia::render('Purchases/Create', [
            // 'customers' => $customers,
            'items' => $items,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {
        // dd($request);

        DB::beginTransaction();

        try {
            $purchase = Purchase::create([
                'customer_id' => $request->customer_id,
                'status' => $request->status,
            ]);

            foreach ($request->items as $item) {
                $purchase->items()->attach($purchase->id, [
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();

            return to_route('dashboard');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {

        // 小計
        $items = Order::where('id', $purchase->id)->get();
        // 合計
        $order = Order::groupBy('id')
            ->where('id', $purchase->id)
            ->selectRaw('id, sum(subtotal) as total,
                customer_name, status, created_at')
            ->get();

        return Inertia::render('Purchases/Show', [
            'items' => $items,
            'order' => $order,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        $purchase = Purchase::find($purchase->id);

        $allItems = Item::select('id', 'item_name', 'price')
            ->where('is_selling', '=', true)->get();

        $items = [];

        foreach ($allItems as $allItem) {
            $quantity = 0;
            foreach ($purchase->items as $item) {
                if ($allItem->id === $item->id) {
                    $quantity = $item->pivot->quantity;
                }
            }
            array_push($items, [
                'id' => $allItem->id,
                'item_name' => $allItem->item_name,
                'price' => $allItem->price,
                'quantity' => $quantity,
            ]);
        }

        $order = Order::groupBy('id')
            ->where('id', $purchase->id)
            ->selectRaw('id, customer_id, customer_name, status, created_at')
            ->get();

        return Inertia::render('Purchases/Edit', [
            'items' => $items,
            'order' => $order,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        // dd($request, $purchase);
        // $arr1 = [4, 5, 6];
        // $arr2 = [1, 2, 3];
        // dd($arr1 + $arr2);


        DB::beginTransaction();

        try {

            $purchase->status = $request->status;
            $purchase->save();

            // 中間テーブルの更新
            $items = [];

            foreach ($request->items as $item) {
                $items = $items + [
                    $item['id'] => [
                        'quantity' => $item['quantity']
                    ]
                ];
            }

            // dd($items);

            $purchase->items()->sync($items);

            DB::commit();

            return to_route('dashboard');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
