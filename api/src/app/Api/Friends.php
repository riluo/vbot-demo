<?php
/**
 * Created by PhpStorm.
 * User: zhaoliang
 * Date: 17/10/13
 * Time: 上午11:24
 */
namespace App\Api;

use PhalApi\Api;
use App\Model\Friends as model_friends;
use App\Model\Config as model_config;
use App\Model\Dialog as model_dialog;

class Friends extends Api {

    public function getRules() {
        return array(
            'login' => array(
                'username' => array('name' => 'username'),
            ),
            'search' => array(
                'username' => array('name' => 'username','source' => 'get', 'require' => true),
            ),
        );
    }

    public function login() {
        return array('username' => $this->username);
    }

    public function lists() {
        $config_model = new model_config();
        $config_config = $config_model->getObject()->order('UpdateTime DESC')->fetch();

        $selfName = $config_config['Uin'];
        $selfNickName = $config_config['nickname'];

        $model = new model_friends();
        $model_dialog =new model_dialog();

        $friends = $model->getObject()->where("who",$selfName)->fetchAll();

        //取出最近的聊天信息
        /*$recentChatFromFriends = $model_dialog->getObject()->select('DISTINCT FromNickName as NickName,max(CreateTime) as lastTime')->where("ToNickName",$selfNickName)->group('FromNickName')->order('CreateTime DESC')->fetchAll();
        $recentChatToFriends = $model_dialog->getObject()->select('DISTINCT ToNickName as NickName,max(CreateTime) as lastTime')->where("FromNickName",$selfNickName)->group('ToNickName')->order('CreateTime DESC')->fetchAll();
        $recentFriends = array_merge($recentChatToFriends,$recentChatFromFriends);
        $lastTime = [];
        foreach ($recentFriends as $key => $row) {
            $lastTime[$key]  = $row['lastTime'];
        }
        if(count($lastTime)>0){
            // 将数据根据 lastTime 降序排列，可能有重复的人名，不用管
            array_multisort($lastTime, SORT_DESC,$recentFriends);

            //按照聊天顺序排序
            $sortedFriends = [];
            foreach($recentFriends as $v){
                foreach($friends as $k =>$f){
                    if($f['NickName'] == $v['NickName']){
                        array_push($sortedFriends, $f);
                        unset($friends[$k]);
                    }
                }
            }
            //将未在最近聊天的好友插入列表
            array_push($sortedFriends, $friends);

            return $sortedFriends;
        }*/
        return $friends;

    }


    public function search() {
        $config_model = new model_config();
        $config_config = $config_model->getObject()->order('UpdateTime DESC')->fetch();

        $selfName = $config_config['Uin'];

        $model = new model_friends();

        $friends = $model->getObject()->where("who",$selfName)->and('NickName LIKE ? or RemarkName LIKE ?', '%'.$this->username.'%','%'.$this->username.'%')->fetchAll();

        return $friends;
    }

    public function self() {
        $config_model = new model_config();
        $config_config = $config_model->getObject()->order('UpdateTime DESC')->fetch();

        $selfName = $config_config['Uin'];

        return ['name'=>$config_config['nickname'], 'icon'=>$config_config['HeadImgUrl']];
    }

}
