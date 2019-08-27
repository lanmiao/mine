<?php
//http://域名/admin.php?do=xx&p=xx123
//修改密码只有这2个
define("CUR", dirname(__FILE__));
$do = isset($_REQUEST["do"]) ? trim($_REQUEST["do"]) : "";
$pass = isset($_REQUEST["p"]) ? trim($_REQUEST["p"]) : "";
$now = time();
$pwd = "xx123";//密钥
$file = "index.html";//默认文件
$url = "admin.php?do=xx&p=" . $pass . "&t=" . $now;
if($do == "xx" && md5($pass) == md5($pwd)){
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta charset="UTF-8">
    <style>
    * {margin:0;padding:0;}
    .box {width:736px;margin:60px auto 0;padding:20px;border:1px solid #ddd;border-radius:5px;}
    .box h2 {font-size:24px;color:#333;line-height:28px;margin-bottom:20px;}
    .box h2 span {font-size:14px;color:#999;}
    .box .clear {height:1px;clear:both;}
    .box input[type='text'] {color:#666;}
    .ipt {width:328px;height:24px;line-height:24px;padding:2px 5px;border:1px solid #ccc;float:left;}
    .ipl {width:668px;height:24px;line-height:24px;padding:2px 5px;border:1px solid #ccc;float:left;}
    .ipo {width:724px;height:24px;line-height:24px;padding:2px 5px;border:1px solid #ccc;float:left;}
    .txt {width:668px;height:120px;line-height:24px;padding:2px 5px;border:1px solid #ccc;float:left;resize:none;}
    .btn {width:56px;height:126px;color:#fff;text-align:center;background-color:#1b809e;border:none;float:left;}
    .bts {width:56px;height:30px;color:#fff;text-align:center;background-color:#1b809e;border:none;float:left;}
    </style>

    <script>
    function $(id){
      return document.getElementById(id);
    }
    function trim(str){
        return str.replace(/(^\s*)|(\s*$)/g, "");
    }
    function setfile(val){
      var fs = document.getElementsByName("f");
      for(x in fs){
        fs[x].value  = val;
      }
    }
    function checkweixin(){
        var wx = trim($("weixin").value);
        if(wx == ""){
            alert("请填写轮替微信号群");
            return false;
        }else{
            $("formweixin").submit();
        }
    }
    function checkimage(){
        var pic = trim($("pic").value);
        var pnm = trim($("picname").value);
        if(pic == ""){
            alert("请选择新上传图片");
            return false;
        }else if(pnm && !/^[a-zA-Z0-9_-]+\.(jpg|jpg|jpeg|gif)$/.test(pnm)){
            alert("请填写正确的图片名称");
            return false;
        }else{
            $("formimage").submit();
        }
    }

    
    </script>
    <title>管理中心</title>
  </head>
  <body>
    <div class="box">
    <h2>微信号替换<span>多个微信号间用","分隔，例如&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; weix1,weix2,weix3,weix4 </span></h2>
    <p>当前微信号为：
    <?php
    $lujin = CUR . "/index.html";
    if(file_exists($lujin)){
    	$awx = file_get_contents($lujin);
    	$bwx = substr($awx,strpos($awx,'var arr_wx = [')+14);
    	$cwx = substr($bwx,0,strpos($bwx, ']'));
    	$cwx = str_replace("\"", "", $cwx);
    	$cwx = str_replace("'", "", $cwx);
    	echo $cwx;
    }
    ?>
    </p><br />
	<form action="admin.php" method="post" id="formweixin" onkeydown="if(event.keyCode==13){return false;}" onsubmit="return checkweixin();">
      <input type="hidden" name="do" value="save">
      <input type="hidden" name="type" value="0">
      <input type="hidden" name="p" value="<?php echo $pass;?>">
      <input type="hidden" name="f" value="<?php echo $file;?>">
      <input type="text" name="weixin" id="weixin" value="" placeholder="请输入微信号" class="ipl">
      <input type="submit" value="保存" class="bts">
    </form>
    <div class="clear"></div>
    </div>
    <div class="box">
    <h2>二维码上传<span>图片名和微信号一样即可实现轮播</span></h2>
    <form action="admin.php" method="post" id="formimage" enctype="multipart/form-data" onkeydown="if(event.keyCode==13){return false;}" onsubmit="return checkimage();">
      <input type="hidden" name="do" value="save">
      <input type="hidden" name="type" value="1">
      <input type="hidden" name="p" value="<?php echo $pass;?>">
      <input type="hidden" name="f" value="<?php echo $file;?>">
      <input type="file" name="pic" id="pic" value="" placeholder="请选择新上传图片" class="ipt">
      <input type="submit" value="保存" class="bts">
    </form>
    <div class="clear"></div>
    </div>
    <br /><br /><br /><br />
  </body>
</html>
<?php
}else if($do == "save" && md5($pass) == md5($pwd)){
    $type = isset($_POST["type"]) && $_POST["type"] > 0 ? intval($_POST["type"]) : 0;
    $file = isset($_POST["f"]) ? ltrim($_POST["f"], '/') : $file;
    if($type == 1){
        $tmps = explode("/", $file);
        $subdir = "";
        if(count($tmps) > 1){
            $tmps = array_pop($tmps);
            $subdir = "/" . join("/", $tmps);
        }
        $imgpath = CUR . $subdir . '/img';
        if(!is_dir($imgpath)){
            @mkdir($imgpath, "0777");
        }
        $picname = isset($_POST["picname"]) ? $_POST["picname"] : "";
//      rename($_FILES["pic"]["name"],$_FILES["pic"]["name"].".jpg");
        
        
        $pname = $_FILES["pic"]["name"];
        $jpggh = explode(".", $pname);
//      echo "<script>alert('".$_FILES["pic"]["type"]."')</script>";
        if((($_FILES["pic"]["type"] == "image/jpg") || ($_FILES["pic"]["type"] == "image/jpg") || ($_FILES["pic"]["type"] == "image/jpeg")) && $_FILES["pic"]["error"] <= 0 && move_uploaded_file($_FILES["pic"]["tmp_name"], $imgpath . "/" . $jpggh[0].".jpg")){
            $index = CUR . "/" . $file;
            if(file_exists($index) && $picname){
                $html = file_get_contents($index);
                $html = str_replace($picname, $pname, $html);
                file_put_contents($index, $html);
                @unlink($imgpath . '/' . $picname);
                jump("图片替换成功！", $url);
                exit;
            }else{
                jump("图片上传成功！", $url);
                exit;
            }
        }else{
        	
            @unlink($_FILES["pic"]["tmp_name"]);
            
        }

    }else{
        $weixin = isset($_POST["weixin"]) ? trim($_POST["weixin"]) : "";
        if($weixin){
            $index = CUR . "/" . $file;
            if(file_exists($index)){
                $html = file_get_contents($index);
                $weixin = str_replace(" ", "", $weixin);
                $weixin = str_replace("　", "", $weixin);
                $weixin = str_replace("，", ",", $weixin);
                $weixin = trim($weixin, ",");
                $wxs = explode(",", $weixin);
                $weixin = "";
                foreach($wxs as $v){
                    $weixin .= (empty($weixin) ? "" : ",") . "'" . $v . "'";
                }
                $html = preg_replace("/var\s+arr_wx\s+=\s+\[([^\]]*)\];/i", "var arr_wx = [" . $weixin . "];", $html);
                file_put_contents($index, $html);
                jump("微信号修改成功！", $url);
                exit;
            }
        }
    }
    header("location:" . $url);
    exit;
}else{
		echo "<!DOCTYPE html><html lang='zh-CN'><head><meta charset='UTF-8'></head><body>搞事？<br>已记录你的ip:<br>".$_SERVER['REMOTE_ADDR']."<br></body></html>";
    exit("");
}

function jump($msg, $url){
    echo "<!DOCTYPE html><html lang='zh-CN'><head><meta charset='UTF-8'></head><body><script>alert('" . $msg . "');location.href='" . $url . "'</script></body></html>";
}
?>