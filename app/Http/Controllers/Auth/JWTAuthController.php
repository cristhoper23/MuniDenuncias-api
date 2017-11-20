<?php
namespace App\Http\Controllers\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;

class JWTAuthController extends Controller
{

    public function login(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('username', 'password');

        try {
            // attempt to verify the credentials and create a token for the user or throws JWTException
            if (!$token = JWTAuth::attempt($credentials)) 
                return response()->json(['error' => 'invalid_credentials'], 401);
            
            if(!$user = JWTAuth::authenticate($token))
    	        return response()->json(['error' => 'resource_not_fund'], 404);
    	   
    	    $user->token = "Bearer $token";
        
            // all good so return the user with token
            return response()->json($user);
            
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    }

    public function profile()
    {
        $user = JWTAuth::parseToken()->authenticate();
        
        if(!$user)
	        return response()->json(['error' => 'Recurso no encontrado'], 404);
	        
	    return response()->json($user);
    }

}
