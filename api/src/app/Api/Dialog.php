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
            '*' => array(
                'nickname' => array('name' => 'nickname', 'source' => 'get', 'require' => true),
            ),
        );
    }

    public function lists() {
        $config_model = new model_config();
        $config_config = $config_model->lists()->order('id DESC')->fetch();

        $selfName = $config_config['nickname'];



        $model = new model_dialog();

        return $model->lists()->where('(FromNickName = ? AND ToNickName = ?) OR (ToNickName = ? AND FromNickName = ?)', array($selfName, $this->nickname, $this->nickname, $selfName))->order('CreateTime DESC')->fetchAll();
    }
}
