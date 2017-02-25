<?php
require_once( "../../../../init.php" );

use \Illuminate\Database\Capsule\Manager as Capsule;

require_once(CLASS_PATH."ErrorCase.class.php");
class Recorder{
    private static $data;
    private $inc;
    private $error;

    public function __construct(){
        $this->error = new ErrorCase();
		
		$SystemURL = \WHMCS\Config\Setting::getValue('SystemURL');
		$SystemURL = $SystemURL . '/modules/addons/QQ_Connect/oauth/callback.php';
		
		
		$appid 		= Capsule::table('tbladdonmodules')->where('setting', 'qq_appid')->first();
		$appkey 	= Capsule::table('tbladdonmodules')->where('setting', 'qq_appkey')->first();
		
		$setting = [
			'appid' 	=> $appid->value,
			'appkey' 	=> $appkey->value,
			'callback' 	=> $SystemURL,
		];
		//print_r($setting);die();
		
        //-------读取配置文件
        $this->inc = (object) $setting;
        if(empty($this->inc)){
            $this->error->showError("20001");
        }

        if(empty($_SESSION['QC_userData'])){
            self::$data = array();
        }else{
            self::$data = $_SESSION['QC_userData'];
        }
    }

    public function write($name,$value){
        self::$data[$name] = $value;
    }

    public function read($name){
        if(empty(self::$data[$name])){
            return null;
        }else{
            return self::$data[$name];
        }
    }

    public function readInc($name){
        if(empty($this->inc->$name)){
            return null;
        }else{
            return $this->inc->$name;
        }
    }

    public function delete($name){
        unset(self::$data[$name]);
    }

    function __destruct(){
        $_SESSION['QC_userData'] = self::$data;
    }
}
