<?php
/**
 * Created by PhpStorm.
 * User: zhaoliang
 * Date: 17/10/13
 * Time: 上午11:24
 */
namespace App\Api;

use PhalApi\Api;
use App\Model\Dialog as model_dialog;
use App\Model\Config as model_config;

class Dialog extends Api {

    public function getRules() {
        return array(
            'lists' => array(
                'nickname' => array('name' => 'nickname', 'source' => 'get', 'require' => true),
            ),
            'add' => array(
                'nickname' => array('name' => 'nickname', 'source' => 'post', 'require' => true),
                'username' => array('name' => 'username', 'source' => 'post', 'require' => true),
                'content' => array('name' => 'content', 'source' => 'post', 'require' => true),
            ),
        );
    }

    public function lists() {
        $config_model = new model_config();
        $config_config = $config_model->getObject()->order('UpdateTime DESC')->fetch();

        $selfName = $config_config['nickname'];



        $model = new model_dialog();


        return $model->getObject()->where('(FromNickName = ? AND ToNickName = ?) OR (ToNickName = ? AND FromNickName = ?)', array($selfName, $this->nickname, $selfName, $this->nickname))->order('CreateTime DESC')->fetchAll();
    }

    public function add() {
        $config_model = new model_config();
        $config_config = $config_model->getObject()->order('UpdateTime DESC')->fetch();


        //send to weixin
        $Uin = $config_config['Uin'];
        $Sid = $config_config['Sid'];
        $Skey = $config_config['Skey'];
        $DeviceID = $config_config['DeviceID'];
        $pass_ticket = $config_config['pass_ticket'];
        $username = $config_config['username'];
        $nickname = $config_config['nickname'];

        $ToUserName = $this->username;
        $ToNickName = $this->nickname;

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
                "Content"=>$this->content,
                "FromUserName"=>$username,
                "ToUserName"=> $ToUserName,//NickName:撸货买买买
                "LocalID"=>time() * 1e4,
                "ClientMsgId"=>time() * 1e4
            ));


        $jsonStr = json_encode($arr,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        list($returnCode, $returnContent) = $this->http_post_json($url, $jsonStr);

        if($returnCode == 200){
            //save to db
            $selfNickName = $config_config['nickname'];
            $selfUserName = $config_config['username'];
            $model = new model_dialog();

            $data = array('Type' => '1', 'FromUserName' => $selfUserName, 'FromNickName' => $selfNickName, 'ToUserName' => $this->username, 'ToNickName' => $this->nickname, 'Content' => $this->content, 'CreateTime' => date("Y-m-d H:i:s",time()));
            $model->getObject()->insert($data);

            return $model->getObject()->insert_id();
        }
        return null;

    }

    private function http_post_json($url, $jsonStr)
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

}
