<?php
namespace Adminc\Controller;
use Adminc\Controller\BaseController;
class IndexController extends BaseController {
	public function get_server_ip() { 
		if (isset($_SERVER)) {
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				if (isset($_SERVER['HTTP_CLIENT_IP'])) {
					$realip = $_SERVER['HTTP_CLIENT_IP'];
				} else {
					$realip = $_SERVER['REMOTE_ADDR'];
				}
			}
		} else {
			if (getenv('HTTP_X_FORWARDED_FOR')) {
				$realip = getenv('HTTP_X_FORWARDED_FOR');
			} else {
				if (getenv('HTTP_CLIENT_IP')) {
					$realip = getenv('HTTP_CLIENT_IP');
				} else {
					$realip = getenv('REMOTE_ADDR');
				}
			}
		}
		return $realip; 
	} 
    public function index(){
		$this->assign('Adminusername', session('adminUser')); 
		$this->assign('Adminuserid', session('adminUserid')); 
		
		 $version = CONF_PATH.'ver.php';
        $ver = include($version);
        $ver = $ver['ver'];
		$release = include($version);
        $release = $release['release'];
        $hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        $updatehost = 'http://';
        $updatehosturl = $updatehost . '?a=client_check_time&v=' . $ver . '&u=' . $hosturl;
        $domain_time = file_get_contents($updatehosturl);
        if($domain_time == '0'){
            $domain_time = '[锦尚中国去除授权限制]';
        }else{
            $domain_time = '授权版本：<b>授权版</b> <br>免费更新服务截止时间： ' . date("Y-m-d", $domain_time) . '';
        }
        //$ver = substr($ver,-3);
		//echo $ver;die;
        $lastver = file_get_contents(($updatehost . '?a=check&v=') . $ver);
		//echo $lastver;die;
        if($lastver !== $ver){
            $updateinfo = '<a href="?c=Update&a=index" class="btn green big hidden-print">发现新版本_V ' . $lastver . ' 在线升级<i class="m-icon-big-swapright m-icon-white"></i></a>';
            $chanageinfo = file_get_contents(($updatehost . '?a=chanage&v=') . $lastver);
        }else{
            $updateinfo = '已经是最新系统 不需要升级';
        }
     
		
		$mysql= mysql_get_server_info();
    	$mysql=empty($mysql)?"未知":$mysql;
    	//服务器信息
    	$info = array(
    			'操作系统：' => PHP_OS,
				'服务器IP：' => $this->get_server_ip(),
    			'运行环境：' => $_SERVER["SERVER_SOFTWARE"],
    			'PHP运行方式：' => php_sapi_name(),
				'PHP版本：' => PHP_VERSION,
    			'MYSQL版本：' =>$mysql,
    			'程序系统版本' =>  "_V $ver $release &nbsp;&nbsp;&nbsp; [<a href='http://bbs.52jscn.com/' target='_blank'>访问论坛</a>]",
    			'上传附件限制：' => ini_get('upload_max_filesize'),
    			'执行时间限制：' => ini_get('max_execution_time') . "秒",
    			'剩余空间：' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
				'SOCKET支持：'  => function_exists('fsockopen') ? '是' : '否',
				'ZLIB' => function_exists('gzclose') ? '是' : '否',
				'SAFE_MODE' => (boolean) ini_get('safe_mode') ?  '是' : '否',
				'safe_mode_gid' => (boolean) ini_get('safe_mode_gid') ? '是' : '否',
				'PHP上传附件限制' => get_cfg_var('post_max_size'),
				'授权版本' => $domain_time,
		
    	);
    
     
		$ui['index'] = 'active';
        $this->assign('ui',$ui);
		$this->assign('server_info', $info);
		$this -> assign('updateinfo', $updateinfo);
        $this -> assign('chanageinfo', $chanageinfo);
		$this -> assign('domain_time', $domain_time);
        $this->assign('ver', $ver); 
        $this->assign('sys_info', $sys_info); 
		
		$this->display();
	}
}