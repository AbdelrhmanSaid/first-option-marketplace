<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Requests\Api\Dashboard\ProfileUpdateRequest;
use App\Traits\CanUploadFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use CanUploadFile;

    /**
     * Display the current admin.
     */
    public function show(Request $request): JsonResponse
    {
        return $this->respond($request->user());
    }

    /**
     * Update the current admin.
     */
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $admin = $request->user();
        $admin->fill($request->only('name', 'email'));

        if ($request->hasFile('profile_picture')) {
            $admin->profile_picture = $this->uploadFile($request->file('profile_picture'), 'dashboard/profile_pictures');
        }

        if ($request->filled('password')) {
            $admin->password = bcrypt($request->password);
        }

        $admin->save();

        return $this->respond($admin);
    }
}
