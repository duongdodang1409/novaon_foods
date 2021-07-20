<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;
use Session;
use Novaon\Menus\Models\Customer;
use App\Models\User;
session_start();

class LoginController extends Controller
{
    public function sso(Request  $request){
        $query = http_build_query([
            "client_id" => ' 5eaff0cb9708f7588674d66c',
            "redirect_uri" => 'http://127.0.0.1:8000/callback',
            "response_type" =>'code',
            "scope"=>"",
        ]);
        return redirect("https://accounts-dev.novaonx.com/oauth/authorize?".$query);
    }
    public function  login(Request  $request){
        $token =$request->token;
        $client_id_sso = '5eaff0cb9708f7588674d66c';
        $client_secret_sso = 'OjdOz8Nvl3R62Ra0m';
        if($token!=''){
            $client = new Client();
            $response = $client->request('POST', 'https://accounts-dev.novaonx.com/api/v1/sso/retrieve-user-profile' ,[
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                    'x-hub-signature' => $client_id_sso.':'.$client_secret_sso,
                ]
            ]);
            $data= json_decode($response->getBody(), true);
            $email = $data['user']['email'];
                Session::put('email', $email);

            return Redirect('/');


        }
        else{
            return Redirect('https://accounts-dev.novaonx.com');
        }

    }
    public function checkUser(){
        $email = Session::get('email');
        if($email){
            return view('order');
        }else{
            return Redirect('/');
        }
    }
    public function logout(){
        Session::put('email',null);
        return Redirect('/');
    }
}
