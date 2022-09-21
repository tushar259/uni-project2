<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;

class UserCredentialController extends Controller
{
    //
    public function userLogin(Request $request){
    	session_start();
    	$userLogin = DB::table('userinfo')->where('email', $request->email)->where('password', hash('sha256', $request->password))->get();
    	
    	if($userLogin->count() > 0){
    		//$request->session()->flush();
    		//$request->session()->put('userid', $userLogin[0]->id);
    		//session(['userid' => $userLogin[0]->id]);
    		//session(['name' => $userLogin[0]->name]);
    		//session(['email' => $userLogin[0]->email]);
    		//session(['type' => $userLogin[0]->type]);
    		
    		$_SESSION['userid'] = $userLogin[0]->id;
    		$_SESSION['name'] = $userLogin[0]->name;
    		$_SESSION['email'] = $userLogin[0]->email;
    		$_SESSION['type'] = $userLogin[0]->type;
    		//session('key');
    		return response()->json(['data' => 'user found', 'message' => 'success']);
    	}
    	else{
    		return response()->json(['data' => 'not verified', 'message' => 'failed']);
    	}


    }

    public function userRegistration(Request $request){
    	$insertUser = new UserInfo();
    	$insertUser->name = $request->name;
    	$insertUser->email = $request->email;
    	$insertUser->password = hash('sha256', $request->password);
    	$insertUser->type = "user";
    	if($insertUser->save()){
        	return response()->json(['data' => 'Account created', 'message' => 'success']);
    	}
    	else{
    		return response()->json(['data' => 'Something went wrong', 'message' => 'failed']);
    	}
    	

    }

    public function logout(Request $request){
    	session_start();
    	if(session_destroy()){
    		return response()->json(['data' => 'logged out', 'message' => 'success']);
    	}
    	else{
    		return response()->json(['data' => 'something went wrong', 'message' => 'failed']);
    	}
    }

}
