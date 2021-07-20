<?php
/**
 * Project: Caller_Core
 * Package: Novaon\User\Controllers\Api\Tenant
 * Author: tony
 * Create time: 13:56 9/29/20
 * Copyright (c) 2020 NOVAON.
 **/


namespace Novaon\User\Controllers\Api\Tenant;


use App\ApiCode;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Cache;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Novaon\User\Models\Tenant\Group;
use Novaon\User\Request\Tenant\AddUserGroupRequest;
use Novaon\User\Request\Tenant\DeleteGroupRequest;
use Novaon\User\Request\Tenant\RemoveUserGroupRequest;
use Novaon\User\Request\Tenant\StoreGroupRequest;
use Novaon\User\Request\Tenant\UpdateGroupRequest;
use Novaon\User\Request\Tenant\ViewGroupRequest;
use Novaon\User\Services\StringeePccUserApi;

/**
 * @group Group Management
 *
 * APIs for managing group
 */
class GroupController extends Controller
{
    /**
     * Get all groups
     * @urlParam name Name to filter group by name
     * @bodyParam limit int Limit item of page. Ex: 20
     * @bodyParam asc boolean Sorter type. Ex: true
     * @bodyParam collumn string Sorter Column. Ex: 'name'
     * @response {
        "success": true,
        "code": 200,
        "locale": "en",
        "message": "OK",
        "data": {
        "current_page": 1,
        "data": [
        {
        "id": 1,
        "name": "HR Team 01",
        "description": "HR Team",
        "provider_group_id": "GRA93U6Z",
        "created_at": "2020-09-30T11:07:45.000000Z",
        "updated_at": "2020-09-30T11:07:45.000000Z"
        }
        ],
        "first_page_url": "https://{subdomain}.oncaller.asia/api/v1/group?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "https://{subdomain}.oncaller.asia/api/v1/group?page=1",
        "next_page_url": null,
        "path": "https://{subdomain}.oncaller.asia/api/v1/group",
        "per_page": 20,
        "prev_page_url": null,
        "to": 1,
        "total": 1
        }
        }
     * @param ViewGroupRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ViewGroupRequest $request)
    {

        $groups = Group::all();
        $groups = Group::query();
        if($request->name){
            $groups->where('name', 'LIKE', '%'.$request->name.'%');
        }
        if(isset($request->column) && $request->column){
            $orderBy = $request->asc ? 'ASC' : 'DESC';
            $groups = $groups->orderBy($request->column, $orderBy);
        }else{
            $groups = $groups->orderBy('id', 'DESC');
        }
        // Limit per page
        $limit_item_per_page = config('api.limit_item_per_page');
        if($request->limit){
            $limit_item_per_page = $request->limit > config('api.max_limit_item_per_page') ? config('api.max_limit_item_per_page') : $request->limit;
        }

        $groups = $groups->with('users')->withCount('users')->paginate($limit_item_per_page);
        // Response
        return ResponseBuilder::success($groups, ApiCode::SUCCESS);
    }

    /**
     * Get Group info
     * @urlParam group_id required Group ID. Ex: 1
     * @response {
     * "success": true,
     * "code": 200,
     * "locale": "en",
     * "message": "OK",
     * "data": {
     * "id": 1,
     * "name": "HR Team 01",
     * "description": "HR Team",
     * "provider_group_id": "GRA93U6Z",
     * "created_at": "2020-09-30T11:07:45.000000Z",
     * "updated_at": "2020-09-30T11:07:45.000000Z"
     * }
     * }
     * @param Group $group
     * @param ViewGroupRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Group $group, ViewGroupRequest $request)
    {
        // Response
        return ResponseBuilder::success($group, ApiCode::SUCCESS);
    }

    /**
     * Update Group
     * @urlParam group_id required Group ID. Ex: 1
     * @bodyParam name string required Group Name
     * @bodyParam description string Group description
     * @bodyParam users.*.id integer User Id to add to group
     * @param UpdateGroupRequest $request
     * @param Group $group
     * @return bool
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        $data = $request->validated();
        //Check if users is set => Update user
        if(isset($request->users)) {
            //TODO: Remove old user out of current group
            $users = $group->users;
            $stringeePcc = new StringeePccUserApi(tenancy()->tenant);
            // Detach user from group
            foreach ($users as $user) {
                $user->groups()->detach($group->id);
                $stringeePcc->deleteUserInGroup($user['id'], $group->id);
                $cacheKey = 'monitor_group_user_'.$user['id'];
                Cache::forget($cacheKey);
            }
            // Attach again
            if(isset($data['users'])) {
                foreach ($data['users'] as $user)
                {
                    $existUser = User::find($user['id']);
                    $cacheKey = 'monitor_group_user_'.$user['id'];
                    Cache::forget($cacheKey);
                    $existUser->groups()->attach($group->id);
                    $stringeePcc->addUserToGroup($user['id'], $group->id);
                }
            }
        }
        //Update group
        $group->update($data);

        return ResponseBuilder::success($group, ApiCode::SUCCESS);
    }

    /**
     * Create new Group
     * @bodyParam name string required Group name. Ex: HR Team
     * @bodyParam description string optional Group description. Ex: Group for all HR team's members
     * @bodyParam users.*.id integer User Id to add to group
     * @response {
        "success": true,
        "code": 200,
        "locale": "en",
        "message": "OK",
        "data": {
        "id": 1,
        "name": "HR Team 01",
        "description": "HR Team",
        "provider_group_id": "GRA93U6Z",
        "created_at": "2020-09-30T11:07:45.000000Z",
        "updated_at": "2020-09-30T11:07:45.000000Z"
        }
        }
     * @param StoreGroupRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreGroupRequest $request)
    {
        $data = $request->validated();

        //TODO: Create group
        $group = Group::create($data);

        //Check if users is set
        if(isset($data['users'])) {
            //TODO: Add users to group
            foreach ($data['users'] as $user)
            {
                $existUser = User::find($user['id']);
                $existUser->groups()->attach($group->id);
                $cacheKey = 'monitor_group_user_'.$user['id'];
                Cache::forget($cacheKey);
                $stringeePcc = new StringeePccUserApi(tenancy()->tenant);
                $stringeePcc->addUserToGroup($user['id'], $group->id);
            }
        }

        return ResponseBuilder::success($group, ApiCode::SUCCESS);
    }

    /**
     * Get all users of current group
     * @urlParam group_id required Group ID. Ex: 1
     * @response {
     * "success": true,
     * "code": 200,
     * "locale": "en",
     * "message": "OK",
     * "data": {
     * "0": {
     * "id": 1,
     * "name": "Vu Quang Tam",
     * "email": "tam222@novaon.vn",
     * "email_verified_at": null,
     * "agent_ext": null,
     * "provider_agent_id": "AGERZQNJ",
     * "phone_number": null,
     * "routing_type": null,
     * "manual_status": null,
     * "system_status": null,
     * "device_status": null,
     * "last_time_pickup": null,
     * "last_time_support_call_ended": null,
     * "created_at": "2020-09-30T09:07:38.000000Z",
     * "updated_at": "2020-09-30T09:07:38.000000Z",
     * "pivot": {
     * "group_id": 1,
     * "groupable_id": 1,
     * "groupable_type": "App\\User"
     * }
     * }
     * }
     * }
     * @param Group $group
     * @param ViewGroupRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function users(Group $group, ViewGroupRequest $request)
    {
        return ResponseBuilder::success($group->users, ApiCode::SUCCESS);
    }

    /**
     * Add user to group
     * @urlParam group_id required Group ID. Ex: 1
     * @bodyParam user_id integer required User ID to add. Ex: 3
     * @response {
        "success": true,
        "code": 200,
        "locale": "en",
        "message": "OK",
        "data": {
        "0": {
        "id": 1,
        "name": "Vu Quang Tam",
        "email": "tam222@novaon.vn",
        "email_verified_at": null,
        "agent_ext": null,
        "provider_agent_id": "AGERZQNJ",
        "phone_number": null,
        "routing_type": null,
        "manual_status": null,
        "system_status": null,
        "device_status": null,
        "last_time_pickup": null,
        "last_time_support_call_ended": null,
        "created_at": "2020-09-30T09:07:38.000000Z",
        "updated_at": "2020-09-30T09:07:38.000000Z",
        "pivot": {
        "group_id": 1,
        "groupable_id": 1,
        "groupable_type": "App\\User"
        }
        }
        }
        }
     * @param Group $group
     * @param AddUserGroupRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addUser(Group $group, AddUserGroupRequest $request)
    {
        $data = $request->validated();
        foreach($group->users as $item) {
            $cacheKey = 'monitor_group_user_'.$item->id;
            Cache::forget($cacheKey);
        }
        $userId = $data['user_id'];

        $user = User::find($userId);
        $user->groups()->attach($group->id);

        return ResponseBuilder::success($group->users, ApiCode::SUCCESS);
    }

    /**
     * Remove user from group
     * @urlParam group_id required Group ID. Ex: 1
     * @urlParam user_id required User ID. Ex: 3
     * @response {
     * "success": true,
     * "code": 200,
     * "locale": "en",
     * "message": "OK",
     * "data": {
     * "0": {
     * "id": 1,
     * "name": "Vu Quang Tam",
     * "email": "tam222@novaon.vn",
     * "email_verified_at": null,
     * "agent_ext": null,
     * "provider_agent_id": "AGERZQNJ",
     * "phone_number": null,
     * "routing_type": null,
     * "manual_status": null,
     * "system_status": null,
     * "device_status": null,
     * "last_time_pickup": null,
     * "last_time_support_call_ended": null,
     * "created_at": "2020-09-30T09:07:38.000000Z",
     * "updated_at": "2020-09-30T09:07:38.000000Z",
     * "pivot": {
     * "group_id": 1,
     * "groupable_id": 1,
     * "groupable_type": "App\\User"
     * }
     * }
     * }
     * }
     * @param Group $group
     * @param User $user
     * @param RemoveUserGroupRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeUser(Group $group, User $user, RemoveUserGroupRequest $request)
    {
        foreach($group->users as $item) {
            $cacheKey = 'monitor_group_user_'.$item->id;
            Cache::forget($cacheKey);
        }
        $user->groups()->where('id', $group->id)->detach();

        return ResponseBuilder::success($group->users, ApiCode::SUCCESS);
    }

    /**
     * Delete group
     * @urlParam group_id required Group ID. Ex: 1
     * @response {
     * "success": true,
     * "code": 200,
     * "locale": "en",
     * "message": "OK",
     * "data": { }
     * }
     * @param Group $group
     * @param DeleteGroupRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Group $group, DeleteGroupRequest $request)
    {
        foreach($group->users as $item) {
            $cacheKey = 'monitor_group_user_'.$item->id;
            Cache::forget($cacheKey);
        }
        $group->delete();
        return ResponseBuilder::success();
    }
}
