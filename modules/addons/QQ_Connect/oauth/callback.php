<?php
define( "CLIENTAREA", false );

require_once( "../API/qqConnectAPI.php" );

use \Illuminate\Database\Capsule\Manager as Capsule;

$qc = new QC();

$acs = $qc->qq_callback();
//callback主要是验证 code和state,返回token信息，并写入到文件中存储，方便get_openid从文件中度
 
$oid = $qc->get_openid();
//根据callback获取到的token信息得到openid,所以callback必须在openid前调用
 
$qc = new QC($acs,$oid);

$info = $qc->get_user_info();

$qqurl = explode('http:', $info['figureurl_qq_2']);
$nikename = $info["nickname"]; // QQ昵称
$avatar = $qqurl[1]; // QQ 头像

$openID = Capsule::table('mod_qqconnect')->where('openid', $oid)->first();

// 数据库 OPENID 是否存在
//print_r($openID['SELECT']['result']);die();

if ( $openID ) {
    
    $uid = $openID->uid;
	
	// 更新内容到数据库
	Capsule::table('mod_qqconnect')->where('uid', $uid)->update([
		'nickname' 	=> $nikename,
		'avatar'	=> $avatar,
		'openid'	=> $oid,
	]);
    
    // 写入 SESSION UID
    $_SESSION['uid'] = $uid;
	
	// 获取 UID
	$userinfo 	= Capsule::table('tblclients')->where('id', $uid)->first();
	$username 	= $userinfo->firstname . ' ' . $userinfo->lastname;
	
	// 取出值
	$login_uid 	= $userinfo->id;
	$login_pwd 	= $userinfo->password;
	$language 	= $userinfo->language;

	// 更新登录时间登录IP
	$fullhost = gethostbyaddr($remote_ip);
	Capsule::table('tblclients')->where('id', $login_uid)->update([
		'lastlogin' 	=> now(),
		'ip'			=> $remote_ip,
		'host'			=> $fullhost,
	]);
	$_SESSION['uid'] = $login_uid;
	if ($login_cid) {
		$_SESSION['cid'] = $login_cid;
	}
    $haship = $CONFIG['DisableSessionIPCheck'] ? '' : \WHMCS\Utility\Environment\CurrentUser::getIP();
    $whmcsVersion = \WHMCS\Config\Setting::getValue('Version');
    $Version = strpos($whmcsVersion,'7.3.0');
	// 写入登录数据 判断是否版本大于 7.3.0
	if ( $Version == '0' ) {
    	$_SESSION['upw'] = \WHMCS\Authentication\Client::generateClientLoginHash($login_uid, $login_cid, $login_pwd);
	} else {
    	$_SESSION['upw'] = sha1($login_uid . $login_pwd . $haship . substr(sha1($cc_encryption_hash),0,20));
	}
	$_SESSION['tkval'] = genRandomVal();
	if ($language) {
		$_SESSION['Language'] = $language;
	}
	$hookParams = array('userid' => $login_uid);
	$hookParams['contactid'] = $login_cid ? $login_cid : 0;
	run_hook('ClientLogin', $hookParams);
	logActivity( $username . ' - 通过 QQ扫码 登录' );
	$loginsuccess = true;
    
    //提示
    die( qqMessage('success', '登录成功！') );
	
} else {
// 数据库 OPENID 不存在

	// 判断当前是否登录
	if ( $_SESSION['uid'] ) {
		// 不存在 UID,存在 $_SESSION['uid'] 代表已登录为绑定，执行绑定操作。		
		Capsule::table('mod_qqconnect')
        ->insert(array(
        	'uid'		=> $_SESSION['uid'],
        	'openid' 	=> $oid,
        	'nickname'	=> $nikename,
        	'avatar'	=> $avatar,
        ));
    
	    //提示
		die( qqMessage('success', '关联成功！') );
	    
	} else {
	// 未登录
	
		// 不存在UID，不存在 $_SESSION['uid'] 代表未登录，未绑定，输出需先绑定再登录页面。
    
	    //提示
	    die( qqMessage('error', '尚未绑定QQ<br/>请前往用户中心进行绑定', false) );
	}
	
}