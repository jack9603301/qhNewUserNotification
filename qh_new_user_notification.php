<?php
/*
  Plugin Name: qhjack New User Notification
  Description:起航天空重新定义新用户注册邮件
  Version: 1.0
 */
  
  if ( !function_exists('wp_new_user_notification') ) {
    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
        $user = new WP_User($user_id);
        //获取用户名和邮箱
        $user_login = stripslashes($user->user_login);
        $user_name = stripslashes($user->user_name);
        $user_email = stripslashes($user->user_email);
        //获取Key和博客名称
        $blog_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        $key = get_password_reset_key( $user );

        if ( empty($plaintext_pass) ) {
            $plaintext_pass = '<strong>空</strong>';
        }

        //自定义新用户欢迎邮件
        $message = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><div>';
        if(!empty($user_name)) {
            $message .= '<p>&emsp;&emsp;'.$user_name.'，恭喜您提出用户注册申请。</p>';
        }
        else {
            $message .= '<p>&emsp;&emsp;'.$user_login.'，恭喜您提出用户注册申请。</p>';
        }
        $message .= '<p>&emsp;&emsp;您收到这份邮件是因为你发出了用户注册申请，请确认您的注册信息：</p>';
        $message .= '<p>&emsp;&emsp;&emsp;&emsp;用户名：'.$user_login.'</p>';
        $message .= '<p>&emsp;&emsp;&emsp;&emsp;随机密码：'.$plaintext_pass.'</p>';
        $message .= '<p>&emsp;&emsp;请妥善保存好自己的账户信息，如果您忘记密码，可以通过登录窗口的密码找回功能找回密码。</p>';
        $message .= '<p>如果您要重新设置密码，请<a href=\''.network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login').'\'>单击此处重置密码</a>。';
        $message .= '<p>&emsp;&emsp;您可以扫描以下二维码关注公众号和网站：</p>';
        $message .= '<p>扫描以下二维码，关注公众号：</p>';
        $message .= '<p><img src=\''.network_site_url("wp-content/uploads/2018/09/weixin.jpg","https").'\' /></p>';
        $message .= '<p>扫描以下二维码，快速访问网站：</p>';
        $message .= '<p><img src=\''.network_site_url("wp-content/uploads/2018/09/qrcode.png","https").'\' /></p>';
		$message .= '<span style="line-height: 1.5; color: rgb(153, 153, 153);">来自：</span>';
		$message .= '<a href="'.$blogurl.'" style="line-height: 1.5;">'.$blog_name.'</a></p>';
		$message .= '<div style="font-size: 12px; border-top-color: rgb(204, 204, 204); border-top-width: 1px; border-top-style: solid; height: 35px; width: 500px; color: rgb(102, 102, 102); line-height: 35px; background-color: rgb(245, 245, 245);">';
		$message .= '该邮件为系统发送邮件，请勿直接回复！如有打扰，请向博主留言反映。灰常感谢您的阅读！</div></div></body></html>';
        if(!wp_mail($user_email, '['. $blog_name.'] 注册用户确认邮件', $message)) {
            wp_die('用户注册确认邮件发送故障。<br />\nPossible reason: your host may have disabled the mail() function.');
        }
        return;
    }
}

add_filter( 'wp_mail_content_type', 'qh_html_content_type' );

function qh_html_content_type($content_type) {
	$content_type = 'text/html';
    return $content_type;
}
?>