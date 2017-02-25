<?php
use \Illuminate\Database\Capsule\Manager as Capsule;

function QQ_Connect_config() {
	$configarray = array(
		'name' 			=> 'QQ Connect',
		'description' 	=> 'This module allows your customers to use QQ account login WHMCS.',
		'version' 		=> '1.3',
		'author' 		=> '<a href="http://neworld.org" target="_blank">NeWorld</a>',
		'fields' 		=> array()
	);
	
	$configarray['fields']['qq_appid'] = array(
		'FriendlyName' 	=> 'APP ID',
		'Type' 			=> 'text',
		'Size' 			=> '25',
		'Description' 		=> '请输入 APP ID，请前往 <a href="http://connect.qq.com" target="_blank">QQ互联</a> 申请'
	);

	$configarray['fields']['qq_appkey'] = array(
        "FriendlyName" 	=> "APP Key",
        "Type" 			=> "text",
        "Size" 			=> "25",
        "Description" 	=> "请输入 APPKEY",
	);
	
	return $configarray;
}

function QQ_Connect_activate() {
	try {
		if (!\Illuminate\Database\Capsule\Manager::schema()->hasTable('mod_qqconnect')) {
			\Illuminate\Database\Capsule\Manager::schema()->create('mod_qqconnect', function ($table) {
				$table->increments('uid');
				$table->text('openid');
				$table->text('nickname');
				$table->text('avatar');
			});
		}
		if (!\Illuminate\Database\Capsule\Manager::schema()->hasTable('mod_qqsetting')) {
			\Illuminate\Database\Capsule\Manager::schema()->create('mod_qqsetting', function ($table) {
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
	$delete = \Illuminate\Database\Capsule\Manager::table('tbladdonmodules')->where('module', 'QQ_Connect')->where('setting', 'delete')->first();
	if ($delete->value) {
		try {
			\Illuminate\Database\Capsule\Manager::schema()->dropIfExists('mod_qqconnect');
			\Illuminate\Database\Capsule\Manager::schema()->dropIfExists('mod_qqsetting');
		} catch (Exception $e) {
			return [
				'status' => 'error',
				'description' => 'Unable to drop tables: ' . $e->getMessage()
			];
		}
	}
	return [
		'status' => 'success',
		'description' => '模块卸载成功'
	];
}

function QQ_Connect_output($vars) {
	$db = new NeWorld\Database;
    $systemurl = \WHMCS\Config\Setting::getValue('SystemURL');
    $modulelink = $vars['modulelink'];
    $result = "<link rel=\"stylesheet\" href=\"{$systemurl}/modules/addons/QQ_Connect/style.css\">";
    
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'edit':
                    	$setting = Capsule::table('mod_qqsetting')->first();
                        if ($setting == 0) {
                            $login = "&lt;a href=\"javascript:QQ_login('login');\" class=\"btn btn-block btn-qq\"&gt;&lt;i class=\"fa fa-qq\"&gt;&lt;/i&gt; QQ&lt;/a&gt;";
                            
                            $logins = "&lt;a href=\"javascript:QQ_login('bind');\" class=\"btn btn-sm btn-qq\"&gt;&lt;i class=\"fa fa-qq\"&gt;&lt;/i&gt; 绑定QQ&lt;/a&gt;";
                            
                            $logout = "&lt;a href=\"javascript:if(confirm('您确定取消 QQ 账号绑定吗？'))QQ_login('bind');\" class=\"btn btn-sm btn-qq\"&gt;&lt;i class=\"fa fa-qq\"&gt;&lt;/i&gt; 解绑QQ&lt;/a&gt;";
                        } else {
                            $login 	= $setting->login;
                            $logins = $setting->logins;
                            $logout = $setting->logout;
                        }
                        $editor = '<div class="panel-body">
            <form action="{$modulelink}" method="post">
              <input type="hidden" name="action" value="submitedit">
              <div class="form-group">
                <label>登录页按钮</label>
                <p>链接地址： <code>{$systemurl}/modules/addons/QQ_Connect/oauth/?login</code> <a style="color: #999;font-size: 12px;" href="">查看详情</a></p>
                <textarea class="form-control" rows="3" name="login">{$login}</textarea>
              </div>
              <div class="form-group">
                <label>客户中心绑定按钮</label>
                <p>链接地址： <code>{$systemurl}/modules/addons/QQ_Connect/oauth/?bind</code> <a style="color: #999;font-size: 12px;" href="">查看详情</a></p>
                <textarea class="form-control" rows="3" name="logins">{$logins}</textarea>
              </div>
              <div class="form-group">
                <label>客户中心解除绑定</label>
                <p>链接地址： <code>{$systemurl}/modules/addons/QQ_Connect/oauth/?bind</code> <a style="color: #999;font-size: 12px;" href="">查看详情</a></p>
                <textarea class="form-control" rows="3" name="logout">{$logout}</textarea>
              </div>
                <button type="submit" class="btn btn-primary">提交修改</button>
            </form>
        </div>';
                        break;
                    case 'submitedit':
                        if (isset($_POST['login'], $_POST['logins'], $_POST['logout'])) {
                            if (empty($_POST['login']) || empty($_POST['logins']) || empty($_POST['logout'])) {
                                $result .= error("修改按钮样式失败，不允许修改值为空。");
                            } else {
                                $login = html_entity_decode($_POST['login']);
                                $logins = html_entity_decode($_POST['logins']);
                                $logout = html_entity_decode($_POST['logout']);
                                
								$check = Capsule::table('mod_qqsetting')->first();

                                if ($check) {
	                                $action = Capsule::table('mod_qqsetting')
	                                ->insert(array(
	                                	'login'		=> $login,
	                                	'logins' 	=> $logins,
	                                	'logout'	=> $logout,
	                                ));
                                } else {
	                                
	                                $action = Capsule::table('mod_qqsetting')
	                                ->update(array(
	                                	'login'		=> $login,
	                                	'logins' 	=> $logins,
	                                	'logout'	=> $logout,
	                                ));
                                }

                                if ($action) {
                                    $result .= success('修改按钮样式成功，请在模板文件 clientareahome.tpl 和 login.tpl 中合适的地方加入 <strong>{$qqlink}</strong>');
                                } else {
                                    $result .= error("数据库操作故障，这可能是由于按钮样式并未更改或修改失败，若修改失败、请重试。");
                                }
                            }
                        } else {
                            $result .= error("修改按钮样式失败，请重新提交尝试。");
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
                $count = "<span class='btn btn-info btn-sm'>{$count}</span>";
            }
            $result .= "<div class='alert alert-info'><strong>回调地址</strong> {$systemurl}/modules/addons/QQ_Connect/oauth/callback.php</div>";
            $result .= "<div class=\"panel panel-default\">
        <div class=\"panel-heading\">
            数据列表
        </div>
        {$editor}
        <table class=\"table\">
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
                {$count}
            </td>
            <td>
                <form action=\"{$modulelink}\" method=\"post\">
                    <input type=\"hidden\" name=\"action\" value=\"edit\">
                    <button type=\"submit\" class=\"btn btn-primary btn-xs\">
                        <span class=\"glyphicon glyphicon-edit\" aria-hidden=\"true\"></span> 编辑按钮样式
                    </button>
                </form>
            </td>
        </tr>
            </tbody>
        </table>
    </div>";

    echo $result;
}
    function error($str) {
        return "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\"><ul style=\"padding: 0px;\">{$str}</ul></div>";
    }

    function success($str) {
        return "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\"><ul style=\"padding: 0px;\">{$str}</ul></div>";
    }