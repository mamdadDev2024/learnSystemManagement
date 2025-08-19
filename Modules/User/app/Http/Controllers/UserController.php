<?php

namespace Modules\User\Http\Controllers;

use App\Contracts\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\App\Http\Requests\UserUpdateRequest;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    public function __construct(private UserService $service){}
    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request){
        $result = $this->service->update(auth('sanctum')->user() , $request->validated());
        if ($result->status)
            return ApiResponse::success($result->data);
        return ApiResponse::error('user update failed' , statusCode: 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy() {
        $result = $this->service->delete(auth('sanctum')->user());
        if ($result->status)
            return ApiResponse::success(null , 'User Deleted Successful');
    }
}
