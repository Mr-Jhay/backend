<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $data = $request->validate([
            'name'=>['required','string'],
            'email'=>['required','email','unique:users'],
            'password'=>['required','min:6']
        ]);

        $user = User::create($data);
        $token=$user->createToken('auth_token')->plainTextToken;
        return[
            'user'=>$user,
            'token'=>$token,

        ];
    }

    public function login(Request $request){
        $data=$request->validate([
            'email'=>['required','email','exists:users'],
            'password'=>['required','min:6']

        ]);

        $user = User::where('email',$data['email'])->first();
        if (!$user||!Hash::check ($data['password'],$user->password)){
            return response([
                'message' =>'Not Correct',

            ],401);
        }
        $token=$user->createToken('auth_token')->plainTextToken;
        return[
            'user'=> $user,
            'token'=>$token,
        ];
    }


    public function userprofile(){
        $userData = auth()->user();
        return response()->json([
            'status'=>true,
            'message'=> 'user Login Profile',
            'data'=> $userData,
            'id'=> auth()->user()->id,
            'name'=> auth()->user()->name,
            'email'=> auth()->user()->email

        ],200);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            'status'=> true,
            'message'=>'Logout Token',
            'data'=>[]
        ],200);
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id); // Find user by ID; throws ModelNotFoundException if not found
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found.'], 404);
        }
    }

}
