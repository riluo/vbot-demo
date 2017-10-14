<?php
/**
 * Created by PhpStorm.
 * User: zhaoliang
 * Date: 17/10/13
 * Time: 上午11:38
 */
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Friends extends NotORM {

    public function getORM() {
        $friends = $this->getORM();
        return $friends;
    }
}