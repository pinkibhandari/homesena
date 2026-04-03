<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;

class CmsPageController extends Controller
{
    public function getCmsPage(Request $request, $slug)
    {
        $user = $request->user();
        // $role = $user->role;
        $page = CmsPage::where('slug', $slug)
            ->where('status', 1)
            ->where('type', $user->role)
            ->first();
        if (!$page) {
            return response()->json([
                    'data' => (object) [],
                    'code' => 422,
                    'status' => false,
                    'message' => 'CMS Page not found'
            ]);
        }
        return response()->json([
            'status' => true,
            'code'=>200,
             'message' => 'CMS Page found',
            'data' => $page
        ]);
    }
}
