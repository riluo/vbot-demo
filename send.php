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
    $nickname = $row['nickname'];

}

$sql = "select * from friends where NickName='禅茶一味'";
$sth = $db->query($sql);
while($row = $sth->fetch()){
    $ToUserName = $row['UserName'];
    $ToNickName = $row['NickName'];
}



#$content =  Content::formatContent("Hello friend, I send this info by python code. 你好, 我现在在给你发送信息");
#$data = json_encode("Hello friend, I send this info by python code. 你好, 我现在在给你发送信息", JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$Content = "Hello friend, I send this info by PHP code. 早上好, 我现在在给你发送测试信息，你是独一份！";
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
        "Content"=>$Content,
        "FromUserName"=>$username,
        "ToUserName"=> $ToUserName,//NickName:撸货买买买
        "LocalID"=>time() * 1e4,
        "ClientMsgId"=>time() * 1e4
    ));


$jsonStr = json_encode($arr,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
list($returnCode, $returnContent) = http_post_json($url, $jsonStr);
var_dump($returnContent);
var_dump($returnCode);
if($returnCode == 200){
    $db->exec("insert into dialog(`Type`,FromUserName,FromNickName,ToUserName,ToNickName,Content,CreateTime) values('1','".$username."','".$nickname."','".$ToUserName."','".$ToNickName."','".$Content."','".date("Y-m-d H:i:s",time())."')");
}
$db = null;
