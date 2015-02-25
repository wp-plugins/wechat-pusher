<?php
class wechatPusherClass{
    public function valid(){
        $echoStr = $_GET[ 'echostr' ];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
    private function checkSignature(){
        $signature  = 		$_GET[ 'signature' ];
        $timestamp  =		$_GET[ 'timestamp' ];
        $nonce 		= 		$_GET[ 'nonce' ];		
        $options	= 		get_option( 'wechat_options' );
        $token 		= 		$options['token'];
        $tmpArr 	=		array( $token, $timestamp, $nonce );
        sort( $tmpArr );
        $tmpStr 	= 		implode( $tmpArr );
        $tmpStr 	= 		sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}