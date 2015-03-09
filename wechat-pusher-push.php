<?php
	
function Push_article(){

	$options		= 		get_option( 'wechat_options' );

	$appid 	 		= 		$options[ 'appid' ];

	$appsecret 	 	= 		$options[ 'appsecret' ];

	$url 			= 		"https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";


	$res			= 		https_request( $url );

	$result 		= 		json_decode( $res, true );

	$access_token 	= 		$result[ "access_token" ];

	$articles 		= 		'{"articles":[';

	foreach( $options as $id=>$value ):{

		if ( $id == preg_replace( '/[^0-9]/','', $id ) ){

			$type 		 = 		"image";

			$filepath 	 = 		dirname(dirname(dirname(dirname(__FILE__))))."/wp-admin/".grabImage(catch_the_image($id),"",$id);

			$filedata    = 		array("media"  => "@".$filepath);

			$url 		 = 		"http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";

			$result 	 = 		https_request( $url, $filedata );

			$obj_mediaid = 		json_decode( $result ); 

			$media_id 	 = 		$obj_mediaid -> media_id;
			
			@unlink ( $filepath );
			
			$str = get_post($id)->post_content;
			
			$str = str_replace("\n", "<br><p>", $str);
			$str = str_replace("<br><p><ol>", "<ol>", $str);
			$str = str_replace("<br><p></ol>", "</ol>", $str);
			$str = str_replace("<br><p></blockquote>", "</blockquote>", $str);
			$str = addslashes($str);
			
			include_once( dirname( dirname( WP_PLUGIN_DIR ) ) . '/wp-includes/pluggable.php' );
			
			$temp = '{

				"thumb_media_id"		 : 		"'.$media_id.'",

				"author"				 : 		"'.get_userdata(get_post($id)->post_author)->user_login.'",

				"title"					 : 		"'.get_post($id)->post_title.'",

				"content_source_url"	 :		"'.get_post($id)->guid.'",

				"content" 				 : 		"'.$str.'",

				"digest" 				 : 		"'.get_post($id)->post_excerpt.'",

				"show_cover_pic"		 : 		"0"

			}';

			if ( $articles == '{"articles":[' )

				$articles = $articles.$temp;

			else 

				$articles = $articles.','.$temp;

		}

	}endforeach;

	$articles 		 = 			$articles."]}";
	
	$post_data		 = 			$articles;
	
	$url			 = 			"https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=$access_token";

	$data			 = 			https_request( $url, $post_data );

	$erciobj 		 = 			json_decode( $data, true ); 
	
	$media_id_gr 	 = 			$erciobj->media_id;
	
	$url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$access_token";

	$aaa = $erciobj['media_id'];
	
	$post_data = json_encode(array(

		"filter"   =>      array("is_to_all"=>true),

		"mpnews"    =>      array("media_id"=>$aaa),

		"msgtype"  =>  	   "mpnews"

	));

	$data			 = 			https_request( $url, $post_data );

	$erciobj 		 = 			json_decode( $data, true ); 
	
	if ( $erciobj['errmsg'] == 'send job submission success' ){

		foreach( $options as $id=>$value ):{

			if ( $id == preg_replace( '/[^0-9]/','', $id ) ){

				add_option( $id, 'posted' );

			}

		}endforeach;

	}

	else{

		add_option( 'errmsg', $erciobj['errmsg'] );

	}

}