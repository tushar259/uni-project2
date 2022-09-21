<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInfo;
use App\Models\Files;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function uploadFiles(Request $request){
    	session_start();
		//if(file_exists($updateCV))
	    //if(unlink($updateUserCVorVideos->cv_video)){
	    //if(move_uploaded_file($_FILES['file']['tmp_name'], $cvOrVideoFileName))								
    	//if(File::makeDirectory($path, $mode = 0777, true, true))
		//$_FILES['files']['tmp_name'][0] ====>>> to save file
    	//$_FILES['files']['name'][0]  =====>>> file's name

    	//return round($_FILES['files']['size'][0] / 1024 / 1024, 2);

    	$size = count(collect($_FILES['files']['tmp_name'])); 	

    	$nameChanged = false;
    	$currentFileName = "";
    	$j=0;
    	$done = true;

    	for($i = 0; $i < $size; $i++){
    		$done = true;
    		$j = 0;
    		$nameChanged = false;

    		if(round($_FILES['files']['size'][$i] / 1024 / 1024, 2) > 10.0){
    			$i++;
    			if($i == $size){
    				if($size > 1){
    					return response()->json(['data' => 'uploaded', 'message' => 'success']);
    				}
    				else if($size == 1){
    					return response()->json(['data' => 'could not upload', 'message' => 'failed']);
    				}
    			}
    		}

    		$currentFileName = $_FILES['files']['name'][$i];

    		if(file_exists(public_path()."/user_uploads/".$currentFileName)){
				while($nameChanged == false){
					$currentFileName = "(".$j.")".$_FILES['files']['name'][$i];
					if(file_exists(public_path()."/user_uploads/".$currentFileName)){
						$j++;
					}
					else{
						$nameChanged = true;
					}
				}
			}

			$sotredPath = "user_uploads/".$currentFileName;

			if(move_uploaded_file($_FILES['files']['tmp_name'][$i], $sotredPath)){
				$hashOfCurrentFile = hash_file('sha256', $sotredPath);
				$activeStatus = "active";
				if(count(DB::table('files')->where('hash', $hashOfCurrentFile)->get()) > 0){
					$activeStatus = "blocked";
				}
				
				$uploadNewFile = new Files();
				$uploadNewFile->name = $currentFileName;
				$uploadNewFile->location = $sotredPath;
				$uploadNewFile->hash = $hashOfCurrentFile;
				$uploadNewFile->size = round($_FILES['files']['size'][$i] / 1024 / 1024, 2)." MB";
				$uploadNewFile->active_status = $activeStatus;
				$uploadNewFile->user_id = -20;
				if(isset($_SESSION['userid'])){
					$uploadNewFile->user_id = $_SESSION['userid'];
				}

				if($uploadNewFile->save()){
					$done = true;
				}
				
				
			}
			else{
				$done = false;
			}

			//$_FILES['files']['tmp_name'][$i]->move("user_uploads", $currentFileName);

    	}
    	if($done == true){
    		return response()->json(['data' => 'uploaded', 'message' => 'success']);
    	}
    	else{
    		return response()->json(['data' => 'could not upload', 'message' => 'failed']);
    	}
    	
        //return response()->json(['data' => $_FILES['files']['name'][0], 'message' => 'success']);
    }

    public function checkIfUserLoggedIn(Request $request){
    	session_start();

    	if(isset($_SESSION['userid'])){
    		$data = array(
			    'userid' => $_SESSION['userid'],
			    'name' => $_SESSION['name'],
			    'email' => $_SESSION['email'],
			    'type' => $_SESSION['type']);

    		return response()->json(['data' => $data, 'message' => 'success']);
    	}
    	else{
    		return response()->json(['data' => 'user not found', 'message' => 'failed']);
    	}
    }

    public function getUploadedFiles(){
    	$data = DB::table('files')->orderBy('uploaded_time', 'DESC')->get();
    	if(count($data) > 0){
    		return response()->json(['data' => $data, 'message' => 'success']);
    	}
    	else{
    		return response()->json(['data' => 'no data found', 'message' => 'failed']);
    	}

    }

    public function getLoggedUploadedFiles(){
    	session_start();
    	$data = DB::table('files')->where('user_id', $_SESSION['userid'])->get();
    	if(count($data) > 0){
    		return response()->json(['data' => $data, 'message' => 'success']);
    	}
    	else{
    		return response()->json(['data' => 'no data found', 'message' => 'failed']);
    	}
    }


    public function enterReason(Request $request){
    	$insertReason = Files::where('id', $request->fileId)->first();
    	$insertReason->active_status = "request to active";
    	$insertReason->request_reason = $request->reason;
    	if($insertReason->save()){
    		return response()->json(['data' => 'Requested to unblock', 'message' => 'success']);
    	}
    	else{
    		return response()->json(['data' => 'Something went wrong', 'message' => 'failed']);
    	}

    }

    public function getRequestedFiles(){
    	session_start();
    	if($_SESSION['type'] == "admin"){
	    	if(count($data = DB::table('files')->where('active_status', 'request to active')->get()) > 0){
	    		return response()->json(['data' => $data, 'message' => 'success']);
	    	}
	    	else{
	    		return response()->json(['data' => 'no data found', 'message' => 'failed']);
	    	}
    	}
    	else{
    		return response()->json(['data' => 'no data found', 'message' => 'failed']);
    	}

    }

    public function updateRequestedFiles(Request $request){
    	session_start();
    	if($_SESSION['type'] == "admin"){
    		$data = Files::where('id', $request->fileId)->first();
    		$data->active_status = $request->value;
    		$data->request_reason = "";
    		if($data->save()){
    			return response()->json(['data' => $request->value, 'message' => 'success']);
    		}
    	}
    	
    	else{
    		return response()->json(['data' => 'Something went wrong', 'message' => 'failed']);
    	}
    }
    
    public function fileDownloaded($id){
    	$data = Files::where('id', $id)->first();
    	$data->last_downloaded = Carbon::now()->toDateTimeString();
    	if($data->save()){
    		return response()->json(['data' => 'Download updated', 'message' => 'success']);
    	}
    	else{
    		return response()->json(['data' => 'Something went wrong', 'message' => 'failed']);
    	}
    }

    
}
