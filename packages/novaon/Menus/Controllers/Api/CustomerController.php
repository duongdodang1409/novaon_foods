<?php

namespace Novaon\Menus\Controllers\Api;

use Illuminate\Http\Request;
use App\ApiCode;
use Novaon\Menus\Models\Customer;
use DB;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class CustomerController extends Controller
{

    public function index(Request $request, Customer $customer)
    {
        $from = date($request->first);
        $to = date($request->last);
       $keyWord = $request -> keySearch;
       $email = $request ->email;
        if($keyWord) {
            $data = Customer::where('email', 'like', '%' . $keyWord . '%')->get();
        }
        elseif ($email){
            $data = Customer::where('email',$email )->get();
        }
        elseif($from) {
            $data = DB::table('orders')
                ->selectRaw('customers.name,customers.email,customers.id,customers.status ,customers.property ,sum(quantity) as quantity, sum(price) as price')
                ->rightJoin('customers', 'orders.id_customer', '=', 'customers.id')
                ->groupBy('customers.id', 'customers.name', 'customers.email', 'customers.property','customers.status')
                ->where(['orders.status'=>1])
                ->whereBetween('orders.created_at', [$from, $to])
                ->orderBy('quantity', 'DESC')
                ->get();
        }
        else{
            $data =Customer::all();
        }
        return ResponseBuilder::success($data, ApiCode::SUCCESS);
    }


    public function store(Request $request, Customer $customer)
    {
        $customer = Customer::create($request->all());
        return ResponseBuilder::success($customer, ApiCode::SUCCESS);
    }


    public function show(Request $request,Customer $customer)
    {
        return ResponseBuilder::success($customer, ApiCode::SUCCESS);

    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->all();
        $customer->update($data);
        return ResponseBuilder::success($customer, ApiCode::SUCCESS);
    }

    public function destroy(Customer $customer, Request $request)
    {
        //TODO: Delete
        $customer->delete();
        return ResponseBuilder::success();
    }
}
