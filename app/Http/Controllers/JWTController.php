<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class JWTController extends Controller
{
    public function __construct()
    {
        $this->middleware("jwtAuth",["except"=>["login","register"]]);  
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),[
            "name"=>["required","string"],
            "email"=>["required","email"],
            "password"=>["required"]
        ]);

        if($validate->fails())
        {
            return response()->json($validate->errors());
        }
        $user = User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>Hash::make($request->password)
        ]);

        if($user)
        {
           return response()->json(["status"=>200,"message"=>"user registered. You can login now !","user"=>$user]);
        }
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(),[
            "email"=>["required","email"],
            "password"=>["required"]
        ]);

        if($validate->fails())
        {
            return response()->json($validate->errors());
        }

        if(! $token = auth()->attempt($validate->validated()))
        {
            return response()->json(["Email  or password does not match !",401]);
        }

        return $this->respondWithToken($token);
    }

    public function profile()
    {
        return response()->json(["user"=>auth()->user()],200);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(["logged out !"]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            "access_token"=>$token,
            "token_type"=>"Bearer",
            "expires_in"=> auth()->factory()->getTTL()*60
        ]);
    }
}
