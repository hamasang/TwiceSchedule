<?php 
	header("Content-Type: text/html; charset=UTF-8");
	function send_notification ($tokens, $message)
	{
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
			 'registration_ids' => $tokens,
			 'data' => $message
			);
		$headers = array(
			'Authorization:key =' . GOOGLE_API_KEY,
			'Content-Type: application/json'
			);

	   $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       return $result;
	}
	

	//데이터베이스에 접속해서 토큰들을 가져와서 FCM에 발신요청
	include_once '../fcm_user/config.php';
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$sql = "Select Token From Fcm_Users ORDER BY id ASC";

	$result = mysqli_query($conn,$sql);
	$tokens = array();

	if(mysqli_num_rows($result) > 0 ){

		while ($row = mysqli_fetch_assoc($result)) {
			$tokens[] = $row["Token"];
		}
	}

	mysqli_close($conn);
	
        $myMessage = $_REQUEST['message']; //폼에서 입력한 메세지를 받음
	if ($myMessage == ""){
		$myMessage = "새글이 등록되었습니다.";
	}
	$message = array("message" => $myMessage);
	if(count($tokens) >= 999){
		$tokens2 = array_slice($tokens,0,999);
		$tokens3 = array_slice($tokens,1000,1999);
		$tokens4 = array_slice($tokens,2000,3999);
		$tokens5 = array_slice($tokens,2000,3999);
		$tokens6=array_slice($tokens,4000,4999);
		$tokens7=array_slice($tokens,5000,5999);
		$tokens8=array_slice($tokens,6000,6999);
		$tokens9=array_slice($tokens,7000,8999);
		$tokens10=array_slice($tokens,8000,9999);
		$tokens11=array_slice($tokens,10000,19999);
	}else{
		$tokens2 = $tokens;
	}
	$message_status1 = send_notification($tokens2, $message);
	$message_status2 = send_notification($tokens3, $message);
	$message_status3 = send_notification($tokens4, $message);
	$message_status4 = send_notification($tokens5, $message);
	$message_status5 = send_notification($tokens6, $message);
	$message_status6 = send_notification($tokens7, $message);
	$message_status7 = send_notification($tokens8, $message);
	$message_status8 = send_notification($tokens9, $message);
	$message_status9 = send_notification($tokens10, $message);
	$message_status10 = send_notification($tokens11, $message);
	echo $message_status1."".$message_status2."".$message_status3."".$message_status4;
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$date = date('Y-m-d H:i:s');
	$sql = "SET NAMES utf8;";
	$result = mysqli_query($conn,$sql);
	$sql = "INSERT INTO `Log`(`contents`, `date`) VALUES ('$myMessage','$date');";
	$result = mysqli_query($conn,$sql);
	mysqli_close($conn);
 ?>
