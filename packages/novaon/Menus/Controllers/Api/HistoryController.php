<?php

namespace Novaon\Menus\Controllers\Api;

use Hamcrest\BaseDescription;
use Illuminate\Http\Request;
use App\ApiCode;
use Novaon\Menus\Models\History;
use DB;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class HistoryController extends Controller
{

    public function index(Request $request, History $history)
    {
        $idCustomer = $request -> customer_id;
        if($idCustomer != null){
            $data= DB::table('history')->where('id_customer', $idCustomer)->get();
        }
        else{
            $data=History::all();
        }
        return ResponseBuilder::success($data, ApiCode::SUCCESS);
    }


    public function store(Request $request, History $history)
    {
        $history = History::create($request->all());
        return ResponseBuilder::success($history, ApiCode::SUCCESS);
    }


    public function show(Request $request,History $history)
    {
        return ResponseBuilder::success($history, ApiCode::SUCCESS);

    }

    public function update(Request $request, History $history)
    {
        $data = $request->all();
        $history->update($data);
        return ResponseBuilder::success($history, ApiCode::SUCCESS);
    }

    public function destroy(History $history, Request $request)
    {
        //TODO: Delete
        $history->delete();
        return ResponseBuilder::success();
    }
}
