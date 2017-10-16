<?php
/**
 * Created by PhpStorm.
 * User: zhaoliang
 * Date: 17/10/13
 * Time: 上午11:38
 */
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Dialog extends NotORM {
    public function getObject() {
        $dialogs = $this->getORM();
        return $dialogs;
    }
}