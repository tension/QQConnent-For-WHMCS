<?php
use WHMCS\Database\Capsule;

function QQ_Connect_config() {
	$configarray = array(
		'name' 			=> 'QQ Connect',
		'description' 	=> 'This module allows your customers to use QQ account login WHMCS.',
		'version' 		=> '1.3',
		'author' 		=> '<a href="http://neworld.org" target="_blank">NeWorld</a>',
		'fields' 		=> []
	);
	
	$configarray['fields']['qq_appid'] = [
		'FriendlyName' 	=> 'APP ID',
		'Type' 			=> 'text',
		'Size' 			=> '25',
		'Description' 		=> '请输入 APP ID，请前往 <a href="http://connect.qq.com" target="_blank">QQ互联</a> 申请'
	];

	$configarray['fields']['qq_appkey'] = [
        "FriendlyName" 	=> "APP Key",
        "Type" 			=> "text",
        "Size" 			=> "25",
        "Description" 	=> "请输入 APPKEY",
	];
	
	return $configarray;
}

function QQ_Connect_activate() {
	try {
		if (!Capsule::schema()->hasTable('mod_qqconnect')) {
			Capsule::schema()->create('mod_qqconnect', function ($table) {
				$table->increments('uid');
				$table->text('openid');
				$table->text('nickname');
				$table->text('avatar');
			});
		}
		if (!Capsule::schema()->hasTable('mod_qqsetting')) {
			
			Capsule::schema()->create('mod_qqsetting', function ($table) {
				$table->text('login');
				$table->text('logins');
				$table->text('logout');
			});
			
		}
	} catch (Exception $e) {
		return [
			'status' => 'error',
			'description' => '不能创建表 mod_qqconnect: ' . $e->getMessage()
		];
	}
	return [
		'status' => 'success',
		'description' => '模块激活成功. 点击 配置 对模块进行设置。'
	];
}

function QQ_Connect_deactivate() {

	Capsule::schema()->dropIfExists('mod_qqconnect');
	Capsule::schema()->dropIfExists('mod_qqsetting');
	
	return [
		'status' => 'success',
		'description' => '模块卸载成功'
	];
}

function QQ_Connect_output($vars) {
    $systemurl = \WHMCS\Config\Setting::getValue('SystemURL');
    $modulelink = $vars['modulelink'];
    $result = '<link rel="stylesheet" href="'.$systemurl.'/modules/addons/QQ_Connect/style.css?v3">';
    
    if (isset($_REQUEST['action'])) {
        switch ($_REQUEST['action']) {
            case 'init':
            	$action = Capsule::table('mod_qqsetting')
		            ->insert([
		            	'login'		=> "&lt;a href=\"javascript:QQ_login('login');\" class=\"btn btn-block btn-qq\"&gt;&lt;i class=\"fa fa-qq\"&gt;&lt;/i&gt; QQ&lt;/a&gt;",
		            	'logins' 	=> "&lt;a href=\"javascript:QQ_login('bind');\" class=\"btn btn-sm btn-qq\"&gt;&lt;i class=\"fa fa-qq\"&gt;&lt;/i&gt; 绑定QQ&lt;/a&gt;",
		            	'logout'	=> "&lt;a href=\"javascript:if(confirm('您确定取消 QQ 账号绑定吗？'))QQ_login('bind');\" class=\"btn btn-sm btn-qq\"&gt;&lt;i class=\"fa fa-qq\"&gt;&lt;/i&gt; 解绑QQ&lt;/a&gt;",
		            ]);
		        if ( $action ) {
                        $alert = success('<p>初始化按钮样式成功，请在模板文件 clientareahome.tpl 和 login.tpl 中合适的地方加入 </p>
                        	<p>{$qqlink} 是登录按钮，绑定按钮，解绑按钮，一个按钮多用。</p>
                        	<p>{$avatar} 是头像，{$nickname} 是昵称，例如</p>
                        	<code style="margin-top: 10px;">{if $avatar}
&lt;span class="avatars"&gt;
&lt;img src="{$avatar}" alt="{$nickname}" /&gt;
{/if}
</code>');
                } else {
                    $alert = error('初始化失败');
                }
            	breeak;
            case 'edit':
            	$setting = Capsule::table('mod_qqsetting')->first();
                if ( $setting ) {
                    $login 	= $setting->login;
                    $logins = $setting->logins;
                    $logout = $setting->logout;
                }
                $editor = '
<div class="panel-body">
    <form action="'.$modulelink.'" method="post">
      <input type="hidden" name="action" value="submitedit">
      <div class="form-group">
        <label>登录页按钮</label>
        <textarea class="form-control" rows="3" name="login">'.$login.'</textarea>
      </div>
      <div class="form-group">
        <label>客户中心绑定按钮</label>
        <textarea class="form-control" rows="3" name="logins">'.$logins.'</textarea>
      </div>
      <div class="form-group">
        <label>客户中心解除绑定</label>
        <textarea class="form-control" rows="3" name="logout">'.$logout.'</textarea>
      </div>
        <button type="submit" class="btn btn-primary">提交修改</button>
    </form>
</div>';
                break;
            case 'submitedit':
                if (empty($_POST['login']) || empty($_POST['logins']) || empty($_POST['logout'])) {
	                
                    $alert = error("修改按钮样式失败，不允许修改值为空。");
                    
                } else {
	                
                    $login = html_entity_decode($_POST['login']);
                    $logins = html_entity_decode($_POST['logins']);
                    $logout = html_entity_decode($_POST['logout']);

                    $action = Capsule::table('mod_qqsetting')
                            ->update([
                            	'login'		=> $login,
                            	'logins' 	=> $logins,
                            	'logout'	=> $logout,
                            ]);
                    if ( $action ) {
                        $alert = success('<p>修改按钮样式成功，请在模板文件 clientareahome.tpl 和 login.tpl 中合适的地方加入 </p>
                        	<p>{$qqlink} 是登录按钮，绑定按钮，解绑按钮，一个按钮多用。</p>
                        	<p>{$avatar} 是头像，{$nickname} 是昵称，例如</p>
                        	<code style="margin-top: 10px;">{if $avatar}
&lt;span class="avatars"&gt;
&lt;img src="{$avatar}" alt="{$nickname}" /&gt;
{/if}
</code>');
                    } else {
                        $alert = error('按钮样式没有修改。');
                    }
                }
                break;
            case 'count':
                $qqconnect = Capsule::table('mod_qqconnect')->orderBy('uid','ASC')->get();
                //print_r($qqconnect);die();
                foreach ($qqconnect as $key => $value) {
	                $getName = Capsule::table('tblclients')->where('id', $value->uid)->first();
	                $qqinfo[$key]['name']		= $getName->firstname . ' ' . $getName->lastname;
					$qqinfo[$key]['id'] 		= $value->uid;
					$qqinfo[$key]['openid'] 	= $value->openid;
					$qqinfo[$key]['avatar'] 	= $value->avatar;
					$qqinfo[$key]['nickname'] 	= $value->nickname;
                	$qqlist .= "<tr>
					    <td>{$qqinfo[$key]['id']}</td>
					    <td><a href='clientssummary.php?userid={$qqinfo[$key]['id']}'>{$qqinfo[$key]['name']}</a></td>
					    <td>{$qqinfo[$key]['openid']}</td>
					    <td>{$qqinfo[$key]['nickname']}</td>
					    <td><img src='{$qqinfo[$key]['avatar']}' style='width: 36px;' /></td>
					</tr>";
				}
                break;
            default:
                break;
        }
    }
    $count = Capsule::table('mod_qqconnect')->count();
    if ($count == 0) {
        $count = '暂无记录';
    } else {
        $count = '<a href="'.$modulelink.'&action=count" class="btn btn-info btn-xs">'.$count.'</a>';
    }
    
    $setting = Capsule::table('mod_qqsetting')->first();
    if ( !$setting ) {
	     $button = '<a href="'.$modulelink.'&action=init" class="btn btn-xs btn-default">初始化按钮</a>';
    } else {
	     $button = '<a href="'.$modulelink.'&action=edit" class="btn btn-xs btn-default">编辑按钮样式</a>';
    }
    
    $header = '<div class="alert alert-info"><strong>回调地址</strong> '.$systemurl.'/modules/addons/QQ_Connect/oauth/callback.php</div>
    	<a href="'.$modulelink.'" class="btn btn-default btn-xs" style="margin-bottom: 20px;"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> 返回</a>
    <div class="panel panel-default">';
    if ( $editor ) {
		$result .= '<div class="panel-heading">编辑按钮信息</div>'.$editor;
	} elseif ( $qqlist ) {
		$result .= '<table class="table">
		    <thead>
			    <tr>
			        <th>UID</th>
			        <th>用户名</th>
			        <th>OPENID</th>
			        <th>QQ昵称</th>
			        <th>QQ头像</th>
			    </tr>
		    </thead>
		    <tbody>
		    '.$qqlist.'
		    </tbody>
		</table>';
	} else {
		$result .= '<table class="table">
		    <thead>
			    <tr>
			        <th>模块名称</th>
			        <th>绑定数量</th>
			        <th>按钮信息</th>
			    </tr>
		    </thead>
		    <tbody>
				<tr>
				    <td>QQ Connect</td>
				    <td>
				        '.$count.'
				    </td>
				    <td>
				        '.$button.'
				    </td>
				</tr>
		    </tbody>
		</table>';
	}
	$footer = '</div>';

    echo $alert.$header.$result.$footer;
}
    function error($str) {
        return "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\">{$str}</div>";
    }

    function success($str) {
        return "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">{$str}</div>";
    }