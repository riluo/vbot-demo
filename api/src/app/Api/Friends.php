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
        $model = new model_friends();
        return $model->fetchAll();
    }
}