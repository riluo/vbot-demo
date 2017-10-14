<?php
/**
 * Created by PhpStorm.
 * User: zhaoliang
 * Date: 17/10/13
 * Time: 上午11:38
 */
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Config extends NotORM {
    public function getORM() {
        $configs = $this->getORM();
        return $configs;
    }
}