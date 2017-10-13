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

class Friends extends Api {

    public function getRules() {
        return array(
            'login' => array(
                'username' => array('name' => 'username'),
            ),
        );
    }

    public function login() {
        return array('username' => $this->username);
    }

    public function lists() {
        $config_model = new model_config();
        $config_config = $config_model->lists()->order('id DESC')->fetch();

        $selfName = $config_config['Uin'];

        $model = new model_friends();
        return $model->lists()->where("who",$selfName)->fetchAll();
    }
}