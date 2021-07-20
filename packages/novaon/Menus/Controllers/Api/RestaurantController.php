<?php

namespace Novaon\Menus\Controllers\Api;

use Illuminate\Http\Request;
use App\ApiCode;
use Novaon\Menus\Models\Restaurant;

use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class RestaurantController extends Controller
{

    public function index()
    {
        $data=Restaurant::all();
        return ResponseBuilder::success($data, ApiCode::SUCCESS);
    }


    public function store(Request $request, Restaurant $restaurant)
    {
        $restaurant = Restaurant::create($request->all());
        return ResponseBuilder::success($restaurant, ApiCode::SUCCESS);
    }


    public function show(Request $request,Restaurant $restaurant)
    {
        return ResponseBuilder::success($restaurant, ApiCode::SUCCESS);

    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $data = $request->all();
        $restaurant->update($data);
        return ResponseBuilder::success($restaurant, ApiCode::SUCCESS);
    }

    public function destroy(Restaurant $restaurant, Request $request)
    {
        //TODO: Delete
        $restaurant->delete();
        return ResponseBuilder::success();
    }
}
