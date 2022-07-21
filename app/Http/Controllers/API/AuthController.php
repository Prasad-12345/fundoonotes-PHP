<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
        /**
     * @OA\Post(
     *   path="/api/newregister",
     *   summary="register",
     *   description="register",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name","email", "password"},
     *               @OA\Property(property="name", type="string"),
     *               @OA\Property(property="email", type="string"),
     *               @OA\Property(property="password", type="string"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="User successfully registered"),
     *   @OA\Response(response=401, description="The email has already been taken"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public $successToken = 200;
    public function newregister(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $userData = User::where('email', $request->email)->first();
        if($userData){
            Log::channel('custom')->error("The email has already registered");
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->password = Hash::make($user->password);

        $success['token'] = $user->createToken('MyLaravelApp')->accessToken;
        $success['name'] = $user->name;
        $user->save();
        return response()->json(['success'=>$success], $this->successToken);
        //return "welcome";
    }

                /**
     * @OA\Post(
     *   path="/api/login",
     *   summary="login",
     *   description="login",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email","password"},
     *               @OA\Property(property="email", type="string"),
     *               @OA\Property(property="password", type="string"),
     *   
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="success"),
     *   @OA\Response(response=401, description="Invalid credentials"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $result = Auth::attempt(['email'=> $request->email, 'password' => $request ->password]);
        if($result){
            $user = new User();
            $success['token'] = $user->createToken('MyLaravelApp')->accessToken;
            return response()->json(['success'=>$success], $this->successToken);
        }
        else{
            Log::channel('custom')->error("You entered wrong password");
            return response()->json(['error'=>'Unauthorised'], 401);
        }        
    }

         /**
     * @OA\get(
     *   path="/api/userInfo",
     *   summary="get users",
     *   description="get users",
     *   @OA\RequestBody(
     *    ),
     *   @OA\Response(response=201, description="success"),
     *   @OA\Response(response=401, description="Invalid credentials"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userInfo(Request $request){
       $user = User::all();
       return response()->json(['success' => $user], $this->successToken);
        //return "welcome";
    }

    public function updateUser(Request $request){
        $request->validate([
            "userId",
            "name"
        ]);
        $user = User::find($request->userId);
        $user->name = $request->name;

        return response()->json(['success' => $user]);
    }   

    public function deleteUser(Request $request){
        $request->validate([
            "userId"
        ]);
        $user = User::find($request->userId);
        $result = $user->delete();

        return $result;
    }   
}
