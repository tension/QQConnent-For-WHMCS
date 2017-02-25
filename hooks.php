<?php

use Illuminate\Database\Capsule\Manager as Capsule;

add_hook('ClientAreaHeadOutput', 1, function ($vars){
	$SystemURL = \WHMCS\Config\Setting::getValue('SystemURL');
	
	$head_out = '
<script src="'.$SystemURL.'/modules/addons/QQ_Connect/assets/js/qqconnect.js?v13"></script>
<link href="'.$SystemURL.'/modules/addons/QQ_Connect/assets/css/style.css?v13" rel="stylesheet" type="text/css">';
    return $head_out;
});

add_hook('ClientAreaPage', 1, function ($vars){
    $db = new NeWorld\Database;
    $qqsetting = Capsule::table('mod_qqsetting')->first();

	//print_r($qqsetting);die();

    if ($qqsetting) {
	    $userID = $_SESSION['uid'];
	    
        if (isset($userID)) {
	        
			$qqinfo = Capsule::table('mod_qqconnect')->where('uid', $userID)->first();
	        
			
			//print_r($qqlink['SELECT']);die();
            if ($qqinfo) {
                $qqlink = $qqsetting->logout;
            } else {
                $qqlink = $qqsetting->logins;
            }
		    
			$avatar = $qqinfo->avatar;
			$nickname = $qqinfo->nickname;

        } else {
            $qqlink = $qqsetting->login;
        }
        
    } else {
        $qqlink = "未设置按钮";
    }

    return [
        'qqlink' => $qqlink,
        'avatar' => $avatar,
        'nickname' => $nickname,
    ];
});
