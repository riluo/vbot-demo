<?php
/**
 * Created by PhpStorm.
 * User: zhaoliang
 * Date: 17/10/12
 * Time: 上午11:42
 */
require_once __DIR__.'/vendor/autoload.php';
use Hanson\Vbot\Support\Content;

function http_post_json($url, $jsonStr)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($jsonStr)
        )
    );
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return array($httpCode, $response);
}

$db = new PDO("mysql:host=localhost;dbname=sd_chat","root","Sunland16");
//查询数据
$sql = "SELECT * FROM config order by id desc limit 1";
$sth = $db->query($sql);
while($row = $sth->fetch()){
    $Uin = $row['Uin'];
    $Sid = $row['Sid'];
    $Skey = $row['Skey'];
    $DeviceID = $row['DeviceID'];
    $pass_ticket = $row['pass_ticket'];
    $username = $row['username'];

}

$sql = "select * from friends where NickName='禅茶一味'";
$sth = $db->query($sql);
while($row = $sth->fetch()){
    $ToUserName = $row['UserName'];
}

$db = null;


$url = "https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxsendmsg?pass_ticket=".$pass_ticket;
$arr = array(
    "BaseRequest" => array(
        "Uin" => $Uin,
        "Sid" => $Sid,
        "Skey"=> $Skey,
        "DeviceID"=> $DeviceID
    ),
    "Msg" => array(
        "Type"=>1,
        "Content"=>Content::formatContent("你好"),
        "FromUserName"=>$username,
        "ToUserName"=> $ToUserName,//NickName:撸货买买买
        "LocalID"=>time() * 1e4,
        "ClientMsgId"=>time() * 1e4
    ));


$jsonStr = json_encode($arr);
list($returnCode, $returnContent) = http_post_json($url, $jsonStr);
var_dump($returnContent);
var_dump($returnCode);
