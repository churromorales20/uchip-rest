<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AuthController extends Controller
{
    public function createRoles(Request $request){
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $admin_role = Role::create(['name' => 'admin']);
        $staff_role = Role::create(['name' => 'staff']);
        Permission::create(['name'=>'orders.*']);
        Permission::create(['name'=>'products.*']);
        $admin_role->givePermissionTo('orders.*');
        $admin_role->givePermissionTo('products.*');
        //$staff_role->givePermissionTo('orders.*');
    }
    public function test(Request $request){
        $closer = INF;
        $ts = [-15 -7 -9 -14 -12];

        function computeDayGains($nbSeats, array $payingGuests, array $guestMovements) {
            $counter = 0;
            for ($i=0; $i < count($arr); $i++) { 
                if($i >= $n1 && $i <= $n2){
                    $counter += $arr[$i];
                }
            }
        }
        dd(countFrequencies(['the','dog','has','has','dog','dog']));
    }
    public function userCheck(Request $request){
       $user = $request->user();
       return response()->json([
            'status' => 'success',
            'user_info' => $user,
            'menu_items' => count($user->getRoleNames()) > 0 ? Role::where('name', $user->getRoleNames()[0])->first()->menues : [],
            'message' => 'User Logged In Successfully',
        ], 200);
    }
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $user->assignRole(Role::where(['name' => 'admin'])->first());
            return response()->json([
                'status' => 'success',
                'user_info' => $user,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        /*return response()->json([
            'status' => 'error',
            'message' => $request->input('email'),
        ]);*/
        try {
            $validateUser = Validator::make($request->json()->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            //dd('ABABBABB');\
            /*return response()->json([
                'status' => 'error',
                'message' => $request->only(['email', 'password'])
            ]);*/
            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }
            $user = User::where('email', $request->email)->first();
            //dd(count($user->getRoleNames()) > 0 ? Role::where('name', $user->getRoleNames()[0])->first()->menues : []);
            return response()->json([
                'status' => 'success',
                'menu_items' => count($user->getRoleNames()) > 0 ? Role::where('name', $user->getRoleNames()[0])->first()->menues : [],
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}