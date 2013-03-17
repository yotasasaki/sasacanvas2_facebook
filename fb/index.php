<?php

require_once 'facebook.php';

	$facebook = new Facebook(array(
			'appId'  => '474966375871341', //AppID
			'secret' => 'af05563b0e01168a33cafb1912c5b04a', //secret
			'fileupload'=>true
	));
	
    //ユーザーIDを取得
	$user = $facebook->getUser(); 
	
    $params = array(
		  'scope' => 'publish_stream, user_photos',//ユーザ−のデータを扱う権限をリクエスト
		  'redirect_uri' => 'http://yotsak.verse.jp/sasacanvas/fb/index.php', 
	);
  
    $loginUrl = $facebook->getLoginUrl($params); //ログイン, アプリの許可のためのURL
	
    $img = $filepath;
	$caption = "Photo uploaded via the PHP SDK!";

    if($user){
        $facebook->setFileUploadSupport(true);
		try{
			  $post=$facebook->api(
							//$albumID."/photos", //指定アルバムに
							"me/photos", //ウォールに
							"POST", //投稿	
							array(
								"source"=>"@".$img, //@が大事
								"message"=>$caption //画像キャプション
			  ));
		}catch(FacebookApiException $err){
              exit($err);
        }
	
    }else{
		echo("<script> top.location.href='" . $loginUrl . "'</script>"); //Userがログイン前なら認証へリダイレクト
	}

?>
<h1>投稿テスト</h1>