<?php 
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Broadcast;
class BroadcastAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        //dd(Auth::user());
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        $response = [
            'id' => $user->id,
            'name' => $user->name,
            // Add any other user information that you want to send to the client
        ];

        return Broadcast::auth($request);
    }
}

?>