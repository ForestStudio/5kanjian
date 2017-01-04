<?php

namespace Adminc\Controller;

use Adminc\Controller\BaseController;

header("content-type:text/html;charset=utf-8");

class UpdateController extends BaseController{

    public function index(){

        $version = './Data/version.php';

        $ver = include($version);

          $ver = $ver['ver'];

        $ver = substr($ver,-3);

		

		$release = include($version);

        $release = $release['release'];

        $hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);

        $updatehost = 'http://柯妮妮';

         $updatehosturl = $updatehost . '?a=client_check_time&v=' . $ver . '&u=' . $hosturl;

        $domain_time = file_get_contents($updatehosturl);

        if($domain_time == '0'){

            $domain_time = '[锦尚中国解密去除授权限制]';

        }else{

            $domain_time = '授权版本：<b>折翼天使商业版</b> 免费更新服务截止时间： ' . date("Y-m-d", $domain_time) . '';

        }

		

        $lastver = file_get_contents(($updatehost . '?a=check&v=') . $ver);

        if($lastver !== $ver){

            $updateinfo = ('<div class=" details"><h3>发现新版本：_V ' . $lastver) . '</h3><p>

		   <a href="javascript:if(confirm(\'升级前,请确认已经做好数据库和程序备份!\'))location=\'./adminc.php?c=Update&a=updatenow\'" class="btn green big hidden-print">点击这里在线升级  <i class="m-icon-big-swapright m-icon-white"></i></a>

           </p></div>';

            $chanageinfo = file_get_contents(($updatehost . '?a=chanage&v=') . $lastver);

        }else{

            $updateinfo = ('<div class=" details"><h2>最新版本为：_V ' . $lastver  ) . '</h2><p class=" number">已经是最新系统 不需要升级</p></div>';

        }

		

		$ui['Update'] = 'active';

        $this->assign('ui',$ui);

		$this -> assign('lastver', $lastver);

        $this -> assign('updateinfo', $updateinfo);

        $this -> assign('chanageinfo', $chanageinfo);

		$this -> assign('domain_time', $domain_time);

        $this -> display();

    } 

    public function updatenow()

    {

        import("Org.Util.PclZip");

		$version = './Data/version.php';

        //$version = CONF_PATH . 'ver.php';

        $ver = include($version);

        $ver = $ver['ver'];

        $ver = substr($ver, -3);

        $hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);

        $updatehost = 'http://柯妮妮';

        $updatehosturl = $updatehost . '?a=update&v=' . $ver . '&u=' . $hosturl;

        $updatenowinfo = file_get_contents($updatehosturl);

        if (strstr($updatenowinfo, 'zip'))

        {

            $pathurl = $updatehost . '?a=down&f=' . $updatenowinfo;

            $updatedir = './Application/Runtime/update';

            $this ->delDirAndFile($updatedir);

            $this -> get_file($pathurl, $updatenowinfo,$updatedir);

            $updatezip = $updatedir . '/' . $updatenowinfo;

            $archive = new \PclZip($updatezip);

            if ($archive->extract(PCLZIP_OPT_PATH, './', PCLZIP_OPT_REPLACE_NEWER) == 0)

            {

                $updatenowinfo = "远程升级文件不存在.升级失败</font>";

            } 

            else

            {

                $sqlfile = $updatedir . '/update.sql';

                $sql = file_get_contents($sqlfile);

                if ($sql)

                {

                    $sql = str_replace("cj_", C('DB_PREFIX'), $sql);

                    $Model = new Model();

                    error_reporting(0);

                    foreach(split(";[\r\n]+", $sql) as $v)

                    {

                        @mysql_query($v);

                    } 

                } 

                $updatenowinfo = "<font color=red>升级完成 {$sqlinfo}</font><span><a href=./adminc.php?c=Update>点击这里 查看是否还有升级包</a></span>";

            } 

        } 

        delDirAndFile($updatedir);

        $this -> assign('updatenowinfo', $updatenowinfo);

        $this -> display();

    } 



   public  function get_file($url, $name, $folder = './')

    {

        set_time_limit((24 * 60) * 60); 

        // 设置超时时间

        $destination_folder = $folder . '/'; 

        // 文件下载保存目录，默认为当前文件目录

        if (!is_dir($destination_folder))

        { 

            // 判断目录是否存在

            $this->mkdirs($destination_folder);

        } 

        $newfname = $destination_folder . $name; 

        // 取得文件的名称

        $file = fopen($url, 'rb'); 

        // 远程下载文件，二进制模式

        if ($file)

        { 

            // 如果下载成功

            $newf = fopen($newfname, 'wb'); 

            // 远在文件文件

            if ($newf)

            { 

                // 如果文件保存成功

                while (!feof($file))

                { 

                    // 判断附件写入是否完整

                    fwrite($newf, fread($file, 1024 * 8), 1024 * 8);

                } 

            } 

        } 

        if ($file)

        {

            fclose($file);

        } 

        if ($newf)

        {

            fclose($newf);

        } 

        return true;

    } 



  public   function mkdirs($path, $mode = '7777')

    {

        if (!is_dir($path))

        { 

            // 判断目录是否存在

           $this-> mkdirs(dirname($path), $mode); 

            // 循环建立目录

            mkdir($path, $mode);

        } 

        return true;

    } 

    // 循环删除目录和文件函数

  public  function delDirAndFile($dirName)

    {

        if ($handle = opendir("$dirName"))

        {

            while (false !== ($item = readdir($handle)))

            {

                if ($item != "." && $item != "..")

                {

                    if (is_dir("$dirName/$item"))

                    {

                       $this-> delDirAndFile("$dirName/$item");

                    } 

                    else

                    {

                        unlink("$dirName/$item");

                    } 

                } 

            } 

            closedir($handle); 

            // rmdir($dirName);

        } 

    } 

} 



?>