<?php
//发送post请求
function https_request( $url, $data = null ){
    $curl = curl_init();
    curl_setopt( $curl, CURLOPT_URL, $url );
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
    if ( !empty( $data ) ){
        curl_setopt( $curl, CURLOPT_POST, 1 );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
    }
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    $output = curl_exec( $curl );
    curl_close( $curl );
    return $output;
}
//将URL形式的图片下载至本地
function grabImage( $url, $filename="", $id ){
	if ( $url == "" ) 
		return false;
	if( $filename == "" ) {
		$ext = strrchr( $url, "." );
		$ext_arr = array( ".gif", ".png", ".jpg", ".bmp" );
		if ( !in_array( $ext, $ext_arr ) ) 
			return false;
		if ( $id == '' )
			$filename = date();
		else
			$filename = get_post($id)->post_title.$ext;
	}
	ob_start();
	readfile( $url );
	$img= ob_get_contents();
	ob_end_clean();
	$fp= @fopen( $filename, "a" );
	@fwrite( $fp, $img );
	@fclose( $fp );
	return $filename;
}
//转换时间戳
function changetimestamp( $strtime ){
	$array = explode( "-", $strtime );
	$year = $array[0];
	$month = $array[1];
	
	$array = explode(":",$array[2]);
	$minute = $array[1];
	$second = $array[2];

	$array = explode(" ",$array[0]);
	$day = $array[0];
	$hour = $array[1] - 8;

	$timestamp = mktime( $hour, $minute, $second, $month, $day, $year );
	return $timestamp;
}
//得到文章id中的图片
function catch_the_image( $id ) {
	$first_img 			= 		'';
	$post_thumbnail_id  = 		get_post_thumbnail_id( $id );
	if ( $post_thumbnail_id ) {
		$output 		= 		wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
		$first_img 		= 		$output[0];
	}
	else{
		ob_start();
		ob_end_clean();
		$output 		= 		preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_post($id)->post_content, $matches);
		$first_img		= 		$matches [1][0];		
		if(empty($first_img)){
			$options 	= 		get_option( 'wechat_options' );
			$first_img  = 		$options['defaultImg'];
		}
	}
	return $first_img;
}

