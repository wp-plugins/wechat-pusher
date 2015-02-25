<?php
add_action( 'admin_menu', 'wechat_article_add_page' );
function wechat_article_add_page() {
	add_menu_page( '微信推送助手', '微信推送助手', 'manage_options', 'wechat_push', 'wechat_article_option_page', plugins_url('icon/wechat.jpg',__FILE__) );	
}
//主界面
function wechat_article_option_page() {	
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2> 微信推送助手 </h2>
		<form action="options.php" method="post">
			<?php settings_fields( 'wechat_options' ); ?>
			<?php do_settings_sections( 'wechat_article' ); ?>
			<?php submit_button(); ?>
			<?php settings_fields( 'wechat_options' ); ?>
			<?php do_settings_sections( 'wechat_push' ); ?>
			<?php submit_button('确认发送','primary','update_push',''); ?>
		</form>
	</div>
	<?php	
}
add_action( 'admin_init', 'wechat_article_admin_init' );
function wechat_article_admin_init(){
	register_setting( 'wechat_options', 'wechat_options', 'wechat_article_validate_options' );
	add_settings_section( 'wechat_article_getnumber', '文章列表', 'wechat_article_section_getnumber', 'wechat_article' );
	add_settings_field( 'wechat_article_select', '输入您想显示的文章数', 'wechat_article_select', 'wechat_article', 'wechat_article_getnumber' );
    add_settings_field( 'wechat_token', '输入您的微信token', 'wechat_token', 'wechat_article', 'wechat_article_getnumber' );
	add_settings_field( 'wechat_appid', '输入您的微信appid', 'wechat_appid', 'wechat_article', 'wechat_article_getnumber' );
	add_settings_field( 'wechat_appsecret', '输入您的微信appsecret', 'wechat_appsecret', 'wechat_article', 'wechat_article_getnumber' );
	add_settings_field( 'wechat_defaultImg', '输入您的默认图片URL', 'wechat_defaultImg', 'wechat_article', 'wechat_article_getnumber' );
	add_settings_field( 'wechat_article_article', '文章列表：', 'wechat_article_article', 'wechat_article', 'wechat_article_getnumber' );
	
}if($_POST['update_push']){		include_once ( 'wechat-pusher-push.php' );		Push_article();	}