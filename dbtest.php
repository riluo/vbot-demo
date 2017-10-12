<?php
    //print_r($contacts['friends']);
    $pdo = new PDO("mysql:host=localhost;dbname=sd_chat","root","Sunland16");
    if($pdo -> exec("insert into friends(UserName,NickName,RemarkName,HeadImgUrl,CreateTime,UpdateTime) values('@b6c5a75bcfadec49f8c3e8f08f8d5f03','@b6c5a75bcfadec49f8c3e8f08f8d5f03','@b6c5a75bcfadec49f8c3e8f08f8d5f03','@b6c5a75bcfadec49f8c3e8f08f8d5f03','2017-06-12','2017-06-12')")){ 
echo "插入成功！"; 
echo $pdo -> lastinsertid(); 
} 
