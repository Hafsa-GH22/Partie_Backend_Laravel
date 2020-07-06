<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\PayloadFactory;
use Tymon\JWTAuth\JWTManager as JWT;
use DB;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5',
            'type' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }  

        $user = User::create([
            'name' => $request->json()->get('name'),
            'email' => $request->json()->get('email'),
            'password' => Hash::make($request->json()->get('password')),
            'type' => $request->json()->get('type')
        ]);
        
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    // Fct Login pour un utilisateur web
    public function login(Request $request)
    {
        $credentials = $request->json()->all();
        $type = $request->input('type');

        // $type = DB::select('select type from users');// where id = ?', [$request->input('id')]);

        try {
            if(! $token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => 'E-mail ou mot de passe incorrect !'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        
        if($type == 1)
        {
            $user = JWTAuth::user();
            return response()->json(compact('token', 'user'));
        }
        else
        {
            $no = "Not a petient !";
            return response()->json(compact('type'));
        }
    }

    // Fct Login pour un utilisateur mobile
    public function loginMobile(Request $request)
    {
        $credentials = $request->json()->all();
        $type = $request->input('type');

        // $type = DB::select('select type from users');// where id = ?', [$request->input('id')]);

        try {
            if(! $token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => 'E-mail ou mot de passe incorrect !'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        
        if($type == 2)
        {
            $user = JWTAuth::user();
            return response()->json(compact('token', 'user'));
        }
        else
        {
            $no = "Not a doctor !";
            return response()->json(compact('type'));
        }
    }

    // protected function user() {
    //     return JWTAuth::parseToken()->authenticate();
    // }
    
    public function getAuthenticatedUser()
    {
        // $token = JWTAuth::parseToken()->authenticate();
        // $user = JWTAuth::toUser($token);

        // return response()->json(compact('token', 'user'));
        // $user = JWTAuth::user();
        // $tok = $request->json()->all();
        // if($token = JWTAuth::attempt($tok)){
        //     return response()->json(compact('token', 'user'));
        // }
        // else 
        // {
        //     return "not working !";
        // }

        // return Auth::user();
        // return JWTGuard::user();

        try 
        {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
        // try {
        //     if(!$user = JWTAuth::parseToken()->authenticate()){
        //         return response()->json(['user_not_found'], 404);
        //     }
        // } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        //     return response()->json(['token_expired'], $e->getStatusCode());
        // } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        //     return response()->json(['token_invalid'], $e->getStatusCode());
        // } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
        //     return response()->json(['token_absent'], $e->getStatusCode());
        // }

        // return response()->json(compact('user'));
    }

    /**
     * Get the authenticated User.
     *
     * return \Illuminate\Http\JsonResponse
     */
    // public function getAuthenticatedUser()
    // {
    //     return response()->json(auth()->user());
    // }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Déconnecté !']);
    }
    
}