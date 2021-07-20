<?php

namespace Novaon\Menus\Controllers\Api;

use Illuminate\Http\Request;
use App\ApiCode;
use Novaon\Menus\Models\Food;
use DB;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Novaon\Menus\Models\Weekday;

class WeekdayController extends Controller
{

  public function index(Request $request)
  {
    $weekday_id=$request->menu_id;
    if($weekday_id){
      $data = DB::table('menu_foods')->where('id_menu',$weekday_id)->select('id_food')->get();
    }
    else{
      $data = Weekday::all();
    }
    return ResponseBuilder::success($data, ApiCode::SUCCESS);
  }

  public function update(Request $request, Weekday $weekday)
  {
      $data = $request->all();
      $weekday->update($data);
      $weekday = Weekday::find($request->id);
      $weekday->foods()->sync($request->foods);

//    $weekday = Weekday::find($request->id);
//    $weekday->foods()->sync($request->foods);

    return ResponseBuilder::success($weekday, ApiCode::SUCCESS);
  }

}
