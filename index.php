<?php

	require_once 'fb/facebook.php';
        require_once 'config.php';        

	$facebook = new Facebook(array(
			'appId'  => APP_ID,
			'secret' => APP_SECRET,
			'fileupload'=>true
	));
	
    //ユーザ−IDを取得
	$user = $facebook->getUser(); 
	
    $params = array(
		  'scope' => 'publish_stream, user_photos',//ユーザ−のデータを扱う権限をリクエスト
		  'redirect_uri' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 
	);
    $loginUrl = $facebook->getLoginUrl($params); //ログイン, アプリの許可のためのURL


    if($user){

		if (empty($_POST['paintdata']) && !$_POST['confirm']) {
			include "sasacanvas.html";
				return true;		
		} else {
		
			$data = $_POST['paintdata'];
			$decoded_data = base64_decode($data);
			$filepath = "../strage/";
			$filename = $filepath . date('YmdHis') . '.png';
			file_put_contents($filename,$decoded_data);
			chmod($filename,0666);

			$img = $filename;
	
			$caption = "";

		
				$facebook->setFileUploadSupport(true);
				try{
					  $post=$facebook->api(
									//$albumID."/photos", //指定のアルバムに
									"me/photos", // ウォール
									"POST", // 投稿
									array(
										"source"=>"@".$img, //@が大事
										"message"=>$caption //caption
					  ));
					  
					  unlink($img);
					  
				}catch(FacebookApiException $err){
					  exit($err);
				}
	    include "confirm.html";		
		}

	
    }else{
		echo("<script> top.location.href='" . $loginUrl . "'</script>"); //もしログイン前なら認証へリダイレクト
    }

