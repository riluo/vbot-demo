<?php
require_once __DIR__.'/vendor/autoload.php';
use PHPQRCode\QRcode;

$uuid = "";
$imgName = time();
$code = new QRcode();
$code::png("https://login.weixin.qq.com/l/Aez1DcqVjg==", "./img/".$imgName.".png", 'H', 4, 2);
?>
<p align="center"><img src="./img/<?php echo $imgName;?>.png" style="margin-top:10px;" /></p>


