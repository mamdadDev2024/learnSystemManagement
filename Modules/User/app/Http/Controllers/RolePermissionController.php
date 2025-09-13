<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\Services\RolePermissionService;

class RolePermissionController extends Controller
{
    public function __construct(private RolePermissionService $service)
    {
    }

    public function index()
    {
        return response()->json([]);
    }
}
