<?php
/*
Plugin Name: 微信推送助手
Plugin URL: http://pq.orgwis.com/?p=50
Description: 一个帮助您推送博文到微信公众号的小插件
Version: 1.0.3
Author: PeiQi
Author URL: http://pq.orgwis.com
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define( 'WECHAT_PUSHER_DIR', WP_PLUGIN_DIR. '/'. dirname( plugin_basename( __FILE__ ) ) );
include( WECHAT_PUSHER_DIR. '/wechat-pusher-function.php' );
include( WECHAT_PUSHER_DIR. '/wechat-pusher-class.php' );
include( WECHAT_PUSHER_DIR. '/wechat-pusher-option.php' );
include( WECHAT_PUSHER_DIR. '/wechat-pusher-page.php' );
add_action( 'init', 'wechat_push_init', 11 );
function wechat_push_init(){
	if( isset( $_GET[ 'wechat' ] ) ){
		$wechatObj = new wechatPusherClass();
		if ( isset( $_GET[ 'echostr' ] ) ) {
 			$wechatObj->valid();
		}
	}
}

