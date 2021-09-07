<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteUser extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        auth()->guard('web')->logout();
        // Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $user->delete();

        return response()->json(null, 204);
    }
}
