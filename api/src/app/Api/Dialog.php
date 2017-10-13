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
                'nickname' => array('name' => 'nickname'),
            ),
        );
    }

    public function lists() {
        $config_model = new model_config();
        $config_config = $config_model->get(1)->order('id DESC');

        $selfName = $config_config['nickname'];


        $model = new model_dialog();
        return $model->where(array('FromNickName = ?' => $selfName, 'ToNickName = ?' => $this->nickname))->or(array('ToNickName > =' => $selfName, 'FromNickName = ?' => $this->nickname))->order('CreateTime DESC')->fetchAll();
    }
}