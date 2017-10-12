<?php
    //print_r($contacts['friends']);
    $pdo = new PDO("mysql:host=localhost;dbname=sd_chat","root","Sunland16");
    /*if($pdo -> exec("insert into friends(UserName,NickName,RemarkName,HeadImgUrl,CreateTime,UpdateTime) values('@b6c5a75bcfadec49f8c3e8f08f8d5f03','@b6c5a75bcfadec49f8c3e8f08f8d5f03','@b6c5a75bcfadec49f8c3e8f08f8d5f03','@b6c5a75bcfadec49f8c3e8f08f8d5f03','2017-06-12','2017-06-12')")){
    echo "插入成功！";
    echo $pdo -> lastinsertid();
    }*/
$q = $pdo->query("SELECT count(*) as count from config");
$q->setFetchMode(\PDO::FETCH_ASSOC);

$rows = $q->fetch();
if($rows["count"]>0) {
    echo 'update';
    $pdo->exec("UPDATE config set Sid='12',Skey='12',DeviceID='23',pass_ticket='23',UpdateTime='".date("Y-m-d H:i:s",time())."'");
} else {
    echo 'insert';
    $pdo->exec("insert into config(Uin,Sid,Skey,DeviceID,pass_ticket,username,CreateTime,UpdateTime) values('12','12','12','12','12','".date("Y-m-d H:i:s",time())."','".date("Y-m-d H:i:s",time())."')");
}
