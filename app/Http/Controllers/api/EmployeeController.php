<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    //
    public $successStatus = 200;

    /**
     * login api
     *
     * @return\Illuminate\Http\Response
     */
    public function login()
    {
        if (Auth::attempt(
            [
                'login_name' => request('login_name'),
                'password' => request('password')
            ]
        )) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;

            return response()->json(
                [
                    'success' => $success
                ],
                $this->successStatus
            );
        }
        else {
            return response()->json(
                [
                    'error' => 'Unauthorised'
                ], 401);
        }
    }

    /**
     * Register api
     *
     * @return\Illuminate\Http\Response
     */
    public function register(AuthRequest $request)
    {
//        $validator = Validator::make($request->all(),
//            [
//                'name' => 'required',
//                'login_name' => 'required',
//                'email' => 'required|email',
//                'password' => 'required',
//                'c_password' => 'required|same:password',
//            ]
//        );
//
//        if ($validator->fails()) {
//            return response()->json(
//                [
//                    'error' => $validator->errors()
//                ], 401);
//        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Employee::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json(
            [
                'success' => $success
            ],
            $this->successStatus
        );
    }

    public function logout(Request $request){
        $request->user()->token()->revoke;
        return response()->json([
            'message' => 'logout success',
        ]);
    }

    public function user(Request $request){
        return response()->json([
            $request->user()
        ]);
    }
}
