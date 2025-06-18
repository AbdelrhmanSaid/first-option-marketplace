<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\PublisherMember;

class RemovePublisherMemberController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PublisherMember $publisherMember)
    {
        $publisherMember->delete();

        return $this->success(__('Member removed successfully'));
    }
}
