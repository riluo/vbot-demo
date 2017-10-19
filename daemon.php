<?php
/**
 * Created by PhpStorm.
 * User: zhaoliang
 * Date: 17/10/19
 * Time: 上午9:51
 */
require_once __DIR__.'/vendor/autoload.php';
use PHPQRCode\QRcode;


$path = __DIR__.'/tmp/';

$options = [
    'path'     => $path,
];
$vbot = new Sunland\Vbot\Foundation\Vbot($options);

$uuid = $vbot->server->getVUuid();
//echo $uuid;
$url = 'https://login.weixin.qq.com/l/'.$uuid;
$imgName = time();
$code = new QRcode();
$code::png($url, "./img/".$imgName.".png", 'H', 4, 2);

//先杀掉进城
exec("ps -ef | grep serve | grep -v grep | awk '{print $2}' |xargs kill -9");
?>
<span id="uuid" style="display: none;"><?php echo $uuid;?></span>
<p align="center"><img src="./img/<?php echo $imgName;?>.png" style="margin-top:10px;" /></p>
<p align="center">扫描后点击以下按钮跳转</p>
<p align="center"><input type="button" value="跳转" onClick="window.location.href='./frontend/index.html'"></p>
<?php
$cmd = "/usr/local/php/bin/php /data/wwwroot/Vbot/vbot-demo/serve.php $uuid";
pclose(popen($cmd.' >> /tmp/vbot.log &', 'r'));

?>
