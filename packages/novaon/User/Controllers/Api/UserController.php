<?php


namespace Novaon\User\Controllers\Api;

use App\ApiCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Novaon\User\Models\User;
use Novaon\User\Request\Tenant\User\DeleteUserRequest;
use Novaon\User\Request\Tenant\User\StoreUserRequest;
use Novaon\User\Request\Tenant\User\UpdateUserRequest;
use Novaon\User\Request\Tenant\User\ViewUserRequest;

/**
 * @group User Management
 *
 * APIs for managing User
 */
class UserController extends Controller
{
    /**
     * List User
     * @queryParam limit int Limit Users of page. Ex: 20
     * @queryParam page int Current Page
     * @response{
     *  "success": true,
     *  "code": 200,
     *  "locale": "en",
     *  "message": "OK",
     *  "data": {
     *  "current_page": 1,
     *  "data": [
     *      {
     *          "id": 1,
     *          "name": "admin",
     *          "email": "admin@test.com",
     *          "email_verified_at": null,
     *          "password": "$2y$10$0gS7Ga.KVrB9A0i6NW8j6.cttgAX3t7kVfuLEi3.p.5hSV9tPeEaq",
     *          "actived": 1,
     *          "role": "admin",
     *          "remember_token": null,
     *          "created_at": null,
     *          "updated_at": null
     *      },
     *  ],
     *  "first_page_url": "http://localhost:8000/api/v1/users?page=1",
     *  "from": 1,
     *  "last_page": 1,
     *  "last_page_url": "http://localhost:8000/api/v1/users?page=1",
     *  "next_page_url": null,
     *  "path": "http://localhost:8000/api/v1/users",
     *  "per_page": 15,
     *  "prev_page_url": null,
     *  "to": 1,
     *  "total": 1
     *  }
     * }
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index(ViewUserRequest $request)
    {
        $keyWord = $request->keySearch;
        $user = User::query();
        if($keyWord){
            $user = $user->where('name', 'like', '%' . $keyWord . '%');
        }
        $apiLimit = config('common.api_limit');
        if ($request->limit) {
            $apiLimit = $request->limit >= config('common.api_limit_max') ? config('common.api_limit_max') : $request->limit;
        }

        $user = $user->paginate($apiLimit);

        return ResponseBuilder::success($user, ApiCode::SUCCESS);
    }

    /**
     * Get User info
     * @urlParam id int required User ID. Ex: 1
     * @response {
     *  "success": true,
     *  "code": 200,
     *  "locale": "en",
     *  "message": "OK",
     *  "data": {
     *      "item": {
     *          "id": 1,
     *          "name": "admin",
     *          "email": "admin@test.com",
     *          "email_verified_at": null,
     *          "password": "$2y$10$0gS7Ga.KVrB9A0i6NW8j6.cttgAX3t7kVfuLEi3.p.5hSV9tPeEaq",
     *          "actived": 1,
     *          "role": "admin",
     *          "remember_token": null,
     *          "created_at": null,
     *          "updated_at": null
     *      }
     *  }
     * }
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(ViewUserRequest $request, User $user)
    {
        return ResponseBuilder::success($user, ApiCode::SUCCESS);
    }

    /**
     * Create new User
     * @bodyParam name string User Name max:200. Ex: admin
     * @bodyParam email string User Email send email verity. Ex: admin@gmail.com
     * @bodyParam password string User Password Hash md5. Ex: abc@123
     * @bodyParam checkPass string Confirm Password if correct you can create
     * @bodyParam role string User role; there are two role: admin and user
     * @response {
     *  "success": true,
     *  "code": 200,
     *  "locale": "en",
     *  "message": "OK",
     *  "data": {
     *      "item": {
     *          "id": 1,
     *          "name": "admin",
     *          "email": "admin@test.com",
     *          "email_verified_at": null,
     *          "password": "$2y$10$0gS7Ga.KVrB9A0i6NW8j6.cttgAX3t7kVfuLEi3.p.5hSV9tPeEaq",
     *          "actived": 1,
     *          "role": "admin",
     *          "remember_token": null,
     *          "created_at": null,
     *          "updated_at": null
     *      }
     *  }
     * }
     * @param StoreUserRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => $request['role'],
        ]);
        return ResponseBuilder::success($user, ApiCode::SUCCESS);
    }

    /**
     * Update User
     * @urlParam id int required User ID. Ex: 1
     * @bodyParam name string User Name max:200. Ex: admin
     * @bodyParam email string User Email send email verity. Ex: admin@gmail.com
     * @bodyParam password string User Password Hash md5. Ex: abc@123
     * @bodyParam checkPass string Confirm Password if correct you can create
     * @bodyParam role string User role; there are two role: admin and user
     * @response {
     *  "success": true,
     *  "code": 200,
     *  "locale": "en",
     *  "message": "OK",
     *  "data": {
     *      "item": {
     *          "id": 1,
     *          "name": "admin",
     *          "email": "admin@test.com",
     *          "email_verified_at": null,
     *          "password": "$2y$10$0gS7Ga.KVrB9A0i6NW8j6.cttgAX3t7kVfuLEi3.p.5hSV9tPeEaq",
     *          "actived": 1,
     *          "role": "admin",
     *          "remember_token": null,
     *          "created_at": null,
     *          "updated_at": null
     *      }
     *  }
     * }
     * @param UpdateUserRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $idAdmin = $request -> id;
        $detailAdmin = User::find($idAdmin);
        if (Hash::check($request->old_password, $detailAdmin->password)) {
            $data = [
                'name' => $request['name'] ?? $user['name'],
                'password' => $request['password'] ? Hash::make($request['password']) : $user['password'],
            ];
            $user->update($data);
        }else{
            $user = ['message' => "Bạn nhập sai mật khẩu cũ!"];
        }
        return ResponseBuilder::success($user, ApiCode::SUCCESS);

    }

    /**
     * Delete User
     * @urlParam id int required User ID. Ex: 1
     * @response {
            "success": true,
            "code": 200,
            "locale": "en",
            "message": "OK",
            "data": { }
        }
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(User $user, DeleteUserRequest $request)
    {
        //TODO: Delete
        $user->delete();
        return ResponseBuilder::success();
    }
}
