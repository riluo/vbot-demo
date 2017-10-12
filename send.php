<?php
/**
 * Created by PhpStorm.
 * User: zhaoliang
 * Date: 17/10/12
 * Time: 上午11:42
 */
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

$url = "https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxsendmsg?pass_ticket=HrUrk%2FeApO65%2FMgJMiUDhQwl0i6U31yhu8kDCdBT1bodWgAnwLBxBdyfUtE8hv6y";
$arr = array(
    "BaseRequest" => array(
        "Uin" => 2973034280,
        "Sid" => "tSMxQ+bRslzpBZCm",
        "Skey"=> "@crypt_9086bef5_84ec22fb157e533927f19bcaba38bdcf",
        "DeviceID"=> "e703688801141976"
    ),
    "Msg" => array(
        "Type"=>1,
        "Content"=>"你好",
        "FromUserName"=>"e6bab46111a78e25f58cc3fa0d2e2514",
        "ToUserName"=> "@05b39bfcf8f576d2112cbaa623856ae3",//NickName:撸货买买买
        "LocalID"=>time() * 1e4,
        "ClientMsgId"=>time() * 1e4
    ));


$jsonStr = json_encode($arr);
list($returnCode, $returnContent) = http_post_json($url, $jsonStr);
var_dump($returnContent);
var_dump($returnCode);
