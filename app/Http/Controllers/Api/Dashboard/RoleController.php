<?php

namespace App\Http\Controllers\Api\Dashboard;

use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display the dashboard home page.
     */
    public function index()
    {
        return $this->respond(
            payload: Role::paginate(columns: ['name']),
        );
    }
}
