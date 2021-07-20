<?php

namespace Novaon\Menus\Controllers\Api;

use Illuminate\Http\Request;
use App\ApiCode;
use Novaon\Menus\Models\Order;
use DB;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class OrderController extends Controller
{

    public function index(Request $request, Order $order)
    {
        $weekday =$request->day;
        $from = date($request->first);
        $to = date($request->last);
        $idCustomer = $request->idCustomer;
      //  $id = $request->customer_id;
        //lấy tổng số đơn 1 ngày trong backend
        if($weekday){
            $data = DB::table('orders')
                ->selectRaw('orders.id_food,foods.name,sum(orders.quantity) as quantity,sum(orders.price) as price',)
                ->join('foods', 'orders.id_food', '=', 'foods.id')
                ->groupBy('orders.id_food', 'foods.name',)
                ->whereBetween('orders.created_at', [$from, $to])
                ->where(['orders.id_menu'=>$weekday])
                ->where(['orders.status'=>1])
                ->get();
        }
        //lấy lịch sử hủy đơn ,đặt đơn frontend,backend của mỗi khách hàng
        else{
            $data = DB::table('orders')
                ->selectRaw('orders.id_food,orders.id,orders.status,foods.name,orders.id_menu,orders.created_at,orders.updated_at,foods.image ,sum(orders.quantity) as quantity,sum(orders.price) as price',)
                ->join('foods', 'orders.id_food', '=', 'foods.id')
                ->groupBy('orders.id_food', 'foods.name','orders.id_menu','orders.id','orders.status','foods.image','orders.created_at','orders.updated_at')
                ->whereBetween('orders.created_at', [$from, $to])
                ->where(['orders.id_customer'=>$idCustomer])
                ->where(['orders.status'=>$request->status])
                ->orderBy('orders.id_menu', 'ASC')
                ->get();
        }
        return ResponseBuilder::success($data, ApiCode::SUCCESS);
    }


    public function store(Request $request, Order $order)
    {
        $order = Order::create($request->all());
        return ResponseBuilder::success($order, ApiCode::SUCCESS);
    }


    public function show(Request $request,Order $order)
    {
        return ResponseBuilder::success($order, ApiCode::SUCCESS);

    }

    public function update(Request $request, Order $order)
    {
        $data = $request->all();
        $order->update($data);
        return ResponseBuilder::success($order, ApiCode::SUCCESS);
    }

    public function destroy(Order $order, Request $request)
    {
        //TODO: Delete
        $order->delete();
        return ResponseBuilder::success();
    }
}
