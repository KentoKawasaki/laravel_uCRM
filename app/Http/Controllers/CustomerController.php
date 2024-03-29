<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Inertia\Inertia;
use App\Services\CheckEmptyQueryService;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use CheckEmptyQueryService;

    public function index(Request $request)
    {
        $searchCustomerQuery = Customer::searchCustomers($request->search);
        // dd($searchCustomerQuery === false);
        $columns = ['id', 'customer_name', 'kana', 'tel'];

        list($noResults, $customers) = $this->checkEmpty($searchCustomerQuery, $columns);

        if($customers !== null){
            if($request->search == null){
                $customers = $customers->paginate(50);
            } else {
                $customers = [
                    'data' => $customers->get(),
                ];
            }
        }

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
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
        return Inertia::render('Customers/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        Customer::create([
            'customer_name' => $request->customer_name,
            'kana' => $request->kana,
            'tel' => $request->tel,
            'email' => $request->email,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'memo' => $request->memo,
        ]);

        return to_route('customers.index')
            ->with([
                'message' => '登録しました。',
                'status' => 'success'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return Inertia::render('Customers/Show', [
            'customer' => $customer,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return Inertia::render('Customers/Edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {

        // dd($customer->email === $request->email);
        
        if (!empty($request->email) && $customer->email !== $request->email) {
            $request->validate([
                'email' => ['unique:customers,email'],
            ]);
            $customer->email = $request->email;
        }
        if (!empty($request->tel) && $customer->tel !== $request->tel) {
            $request->validate([
                'tel' => ['unique:customers,tel'],
            ]);
            $customer->tel = $request->tel;
        }

        // dd($request->rules());

        $customer->customer_name = $request->customer_name;
        $customer->kana = $request->kana;
        $customer->email = $request->email;
        $customer->postcode = $request->postcode;
        $customer->address = $request->address;
        $customer->birthday = $request->birthday;
        $customer->gender = $request->gender;
        $customer->memo = $request->memo;

        $customer->save();

        return to_route('customers.index')->with([
            'message' => '更新しました',
            'status' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return to_route('customers.index')->with([
            'message' => '削除しました',
            'status' => 'danger',
        ]);
    }
}
