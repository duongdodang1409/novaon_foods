<?php

namespace Novaon\Menus\Controllers\Api;

use Illuminate\Http\Request;
use App\ApiCode;
use Novaon\Menus\Models\Food;
use DB;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class FoodController extends Controller
{

    public function index(Request  $request)
    {
      $id = $request->menu_id;
      if($id){
        $data = DB::table('foods')
          ->join('menu_foods', 'menu_foods.id_food', '=', 'foods.id')
          ->where('menu_foods.id_menu', $id)
          ->get();
      }
      elseif ($request->food_id) {
            $data = DB::table('menu_foods')->where('id_food', $request->food_id)->select('id_menu')->get();
      }
      else {

        $data = DB::table('restaurants')
          ->join('foods', 'restaurants.id', '=', 'foods.restaurant_id')
          ->orderBy('restaurants.id', 'DESC')
          ->get();
      }
        return ResponseBuilder::success($data, ApiCode::SUCCESS);
    }


    public function store(Request $request, Food $food)
    {
      $food = Food::create($request->all());
      $food->weekdays()->sync($request->menu_id);
      return ResponseBuilder::success($food, ApiCode::SUCCESS);
    }


    public function show(Request $request,Food $food)
    {
        return ResponseBuilder::success($food, ApiCode::SUCCESS);

    }

    public function update(Request $request, Food $food)
    {
        $data = $request->all();
        $food->update($data);
      $food = Food::find($request->id);
      $food->weekdays()->sync($request->menu_id);

        return ResponseBuilder::success($food, ApiCode::SUCCESS);
    }

    public function destroy(Food $food, Request $request)
    {
        //TODO: Delete
        $food->delete();
        return ResponseBuilder::success();
    }
}
