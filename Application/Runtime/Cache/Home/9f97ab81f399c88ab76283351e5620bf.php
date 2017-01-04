<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8" />
    <meta name="baidu-site-verification" content="2MKKT6mbuL" />
    	<title><?php echo ($confinfo2[title]); ?></title>
    <meta name="keywords" content="移动场景应用自营销管家,移动场景自营销管家,移动场景自营销工具,活动自营销管家,场景展示,免费的报名页,收集潜在客户,二次营销,轻CRM,提高移动场景营销效果" />
    <meta id="metaDescription" name="description" content="<?php echo ($confinfo2[title]); ?>" />
    <META HTTP-EQUIV="pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Cache-Control" CONTENT="no-store, must-revalidate">
    <META HTTP-EQUIV="expires" CONTENT="Wed, 26 Feb 1997 08:21:57 GMT">
    <META HTTP-EQUIV="expires" CONTENT="0">
    <meta name="viewport" id="viewport" content="width=320, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
  	<link rel="stylesheet" href="/view/css/eqShow-2.1.5.3.css?v=<?php $version = './Application/Common/Conf/ver.php'; $ver = include($version); $ver = $ver['ver'];$ver = substr($ver,-4);echo $ver; ?>"/>
    <script> var  your_demain='<?php if(strpos("http://",C("site_url"))!==false)echo "http://"; echo rtrim(C("site_url"),"/") ?>';
   var  your_webname='<?php echo C("site_name") ?>';
   var  logo_url='<?php echo C("site_name") ?>';
     var  lastpagelink='<?php echo C("lastpagelink") ?>';
	 var lastpagetext_color='<?php echo C("lastpagetext_color") ?>';
    var  lastpagetext='<?php echo C("lastpagetext") ?>';
	var jubao_link='<?php echo C("jubao_link") ?>';
   </script>
     <link rel="stylesheet" href="/Public/css/my52.css"/>
</head>
<body>

<div id="ppitest" style="width:1in;visible:hidden;padding:0px"></div>
<div class="p-index main phoneBox" id="con" style="display: none;">
    <div class="top"></div>
    <div class="phone_menubar"></div>
    <div class="scene_title_baner" style="display: none">
        <div class="scene_title"><?php echo ($confinfo2[title]); ?></div>
    </div>
    <div class="nr" id="nr">
        <div id="audio_btn" class="off">
            <div id="yinfu"></div>
            <audio loop src="" id="media" autoplay="" preload></audio>
        </div>
        <div id="loading" class="loading">
            <div class="loadbox">
                <div class="loadlogo" style="background-image: url('/Uploads/<?php if($sceneinfo[loadinglogo]): echo ($sceneinfo[loadinglogo]); else: echo ($confinfo2[imgsrc]); ?> <?php endif; ?>');"></div>
                <div class="loadbg" ></div>
            </div>
        </div>
        <?php if($sceneinfo['accesscode']): ?>
         <div id="verifyCode" class="verifyCode">
            <div class="container">
                <div class="scene-cover">
                    <img class="cover-img" src="/Uploads/<?php echo ($confinfo2[imgsrc]); ?>" />
                </div>
                <div class="scene-name"><?php echo ($confinfo2[title]); ?></div>              
        
                <div class="confirm-password">
	                <label class="verify-label" for="password">请输入访问密码</label>
	                <div class="verify-tips" id="verifyTip">
						<ul class="password-indicator">
							<li></li>
							<li></li>
							<li></li>
							<li></li>
						</ul>
					</div>
					<div class="password-numbers">
						<span>1</span>
						<span>2</span>
						<span>3</span>
						<span>4</span>
						<span>5</span>
						<span>6</span>
						<span>7</span>
						<span>8</span>
						<span>9</span>
						<span>0</span>
					</div>
					<div class="password-operation">
						<span class="btn-clear" id="btnClear">清空</span>
						<span class="btn-cancel" id="btnCancel">取消</span>
					</div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="bottom"></div>
</div>

</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

    <script src="/Public/css/waiwan/jquery.min.js"></script>
<script >
 (function (window, $) {
   window.PREFIX_URL = "http://"+window.location.host+"/";
    window.PREFIX_S1_URL =  "http://"+window.location.host+"/";//"/json/";
    window.PREFIX_S2_URL ="http://"+window.location.host+"/index.php";
	window.PREFIX_S6_URL ="http://"+window.location.host+"/index.php";
    window.PREFIX_HOST = "http://"+window.location.host+"/index.php";
    window.PREFIX_HOST1 = "http://"+window.location.host+"/index.php";
	window.PREFIX_SHOW_HOST = "http://"+window.location.host+"/index.php";
	window.TRACK_HOST = "http://"+window.location.host+"/";
    window.PREFIX_FILE_HOST = "http://"+window.location.host+"/Uploads/";
	window.PREFIX_COMPANY_HOST_ARRAY = ["http://"+window.location.host, "http://"+window.location.host];
	window.OLD_FILE_HOST="http://"+window.location.host+"/index.php";
    window.CLIENT_CDN ="http://"+window.location.host+"/"; 
   window.clientWidth = document.documentElement.clientWidth;
    window.clientHeight = document.documentElement.clientHeight;    
	window.PREFIX_HOST_ARRAY = [
			"http://"+window.location.host,
			"http://"+window.location.host,
			"http://"+window.location.host,
			"http://"+window.location.host
		];
       
})(window, jQuery)
</script>
 <script type="text/javascript" src="/view/eqShow-preview-2.1.5.3.js?v=<?php $version = './Application/Common/Conf/ver.php'; $ver = include($version); $ver = $ver['ver'];$ver = substr($ver,-4);echo $ver; ?>"></script>
<!--  <script type="text/javascript" src="/view/eqShow-2.0.5.0.js?v=<?php echo (C("jsver")); ?>"></script> -->
   
<script>
 
	var scene = {id:<?php echo ($confinfo2[workid]); ?>,code:"<?php echo ($sceneinfo['scenecode_varchar']); ?>",name:"<?php echo ($sceneinfo['scenename_varchar']); ?>",bgAudio:{"url":"{$sceneinfo['musicurl_varchar']}","name":"","id":202382032},pageMode:<?php echo ($sceneinfo['movietype_int']); ?>,cover:"<?php echo ($sceneinfo[cover]); ?>",property:<?php echo ($sceneinfo[property]); ?>};
	   <?php if($sceneinfo['accesscode']): ?>
      eqShow.bootstrapWithPassword(); 
	   <?php else: ?>
	 eqShow.bootstrap();
	   <?php endif; ?>
	   
	   (function () {
        function isMobile() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobi/i.test(navigator.userAgent) ||
                    window.innerWidth < 500;
        }

        function setScale() {
            if (window.top !== window) {
                return;
            }
            var pageScale = 1;

            var width = document.documentElement.clientWidth || 320;
            var height = document.documentElement.clientHeight || 486;


            if (width / height >= 320 / 486) {
                pageScale = height / 486;
            } else {
                pageScale = width / 320;
            }
             console.log('screen.width', width, 'screen.height', height, 'setScale', pageScale);
            // meta
            var content = 'width=320, initial-scale=' + pageScale + ', maximum-scale=' + pageScale + ', user-scalable=no';
            document.getElementById('viewport').setAttribute('content', content);
        }
        
        if (isMobile()) {
            setScale();
        }

    })();
	
</script>
 <script>
          wx.config({
			  debug: <?php echo ($confinfo2[sdkdebug]); ?>,
			  appId: '<?php echo ($confinfo[appId]); ?>',
			  timestamp: "<?php echo ($confinfo[timestamp]); ?>",
			  nonceStr: '<?php echo ($confinfo[nonceStr]); ?>',
			  signature: '<?php echo ($confinfo[signature]); ?>',
			  jsApiList: [
				'onMenuShareTimeline',
				'onMenuShareAppMessage'
			  ]
		  });
          wx.ready(function () {
		  
			  var shareData64 = {
				title: '<?php echo ($confinfo2[title]); ?>',
				desc: '<?php echo ($confinfo2[desc]); ?>',
				link: PREFIX_URL+'<?php echo ($confinfo2[url]); ?>',
				imgUrl:  PREFIX_FILE_HOST  +'<?php echo ($confinfo2[imgsrc]); ?>'
			  };
			  wx.onMenuShareAppMessage(shareData64);
			  wx.onMenuShareTimeline(shareData64);
			  $("#media").get(0).play();
          });
		  
		  
		wx.error(function (res) {
			//alert(res.errMsg);
		});
        </script>
		<?php echo ($confinfo2[othercode]); ?>
</html>