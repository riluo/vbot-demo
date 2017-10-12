<?php
require_once __DIR__.'/vendor/autoload.php';
$path = __DIR__.'/tmp/';
$options = [
   'path'     => $path,
   /*
    * swoole 配置项（执行主动发消息命令必须要开启，且必须安装 swoole 插件）
    */
   'swoole'  => [
       'status' => true,
       'ip'     => '127.0.0.1',
       'port'   => '8866',
   ],
   /*
    * 下载配置项
    */
   'download' => [
       'image'         => true,
       'voice'         => true,
       'video'         => true,
       'emoticon'      => true,
       'file'          => true,
       'emoticon_path' => $path.'emoticons', // 表情库路径（PS：表情库为过滤后不重复的表情文件夹）
   ],
   /*
    * 输出配置项
    */
   'console' => [
       'output'  => true, // 是否输出
       'message' => true, // 是否输出接收消息 （若上面为 false 此处无效）
   ],
   /*
    * 日志配置项
    */
   'log'      => [
       'level'         => 'debug',
       'permission'    => 0777,
       'system'        => $path.'log', // 系统报错日志
       'message'       => $path.'log', // 消息日志
   ],
   /*
    * 缓存配置项
    */
   'cache' => [
       'default' => 'file', // 缓存设置 （支持 redis 或 file）
       'stores'  => [
           'file' => [
               'driver' => 'file',
               'path'   => $path.'cache',
           ],
       ],
   ],
   /*
    * 拓展配置
    * ==============================
    * 如果加载拓展则必须加载此配置项
    */
   'extension' => [
       // 管理员配置（必选），优先加载 remark_name
       'admin' => [
           'remark'   => '',
           'nickname' => '',
       ],
   ],
];
$vbot = new Hanson\Vbot\Foundation\Vbot($options);
$vbot->messageHandler->setHandler(function ($message) {
    Hanson\Vbot\Message\Text::send($message['from']['UserName'], 'Hi, I\'m Vbot!');
});

// 获取监听器实例
$observer = $vbot->observer;
$observer->setFetchContactObserver(function(array $contacts){
    $pdo = new PDO("mysql:host=localhost;dbname=sd_chat","root","Sunland16");
    //print_r($contacts['friends']);
    foreach($contacts as $k => $v) {
        if($k == 'friends'){
            foreach($v as $vv){
                $q = $pdo->query("SELECT count(*) as count from friends where NickName = '".$vv['NickName']."' and RemarkName = '".$vv['RemarkName']."'");
                $q->setFetchMode(PDO::FETCH_ASSOC);

                $rows = $q->fetch();
                if($rows["count"]>0) {
                    $pdo->exec("UPDATE friends set UserName='".$vv["UserName"]."',UpdateTime='".date("Y-m-d H:i:s",time())."' where NickName = '".$vv['NickName']."' and RemarkName = '".$vv['RemarkName']."'");
                } else {
                    $pdo->exec("insert into friends(UserName,NickName,RemarkName,HeadImgUrl,CreateTime,UpdateTime) values('".$vv["UserName"]."','".$vv['NickName']."','".$vv['RemarkName']."','".$vv['HeadImgUrl']."','".date("Y-m-d H:i:s",time())."','".date("Y-m-d H:i:s",time())."')");
                }
            }
        };
    }
    //print_r($contacts['groups']);
    // ...
});


$vbot->server->webServe($argv[1]);
?>

