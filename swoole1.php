<?php
$server = new swoole_websocket_server("0.0.0.0",9503);

$server->on('open', function($server, $req) {
    echo "connection open: {$req->fd}\n";
});

$server->on('message', function($server, $frame) {
    echo "received message: {$frame->data}\n";
//				$server->push($frame->fd, json_encode(["hello", "world   aaaaa"]));
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    $user='root';
    $pass='lyz986160.';
    $pdo= new PDO('mysql:host=192.168.93.106;dbname=dsj',$user,$pass);
    $pdo->exec('set names utf8');//设置编码
    $sql = "INSERT INTO chat (name,fd,cs,zx) VALUES ('李四',1,'{$frame->data}','')";
   $pdo->exec($sql);

   if(substr($frame->data,0, 1)=="@"){
       $vvc=substr($frame->data,0,strpos($frame->data, ':'));
       $cc=substr($vvc,1);
       $server->push($cc,json_encode([$frame->data,$frame->fd,'ccc']));
       $server->push($frame->fd,json_encode([$frame->data,$frame->fd,'ccc']));
   }else {
       foreach ($server->connections as $fds) {
           if ($server->isEstablished($fds)) {
               $server->push($fds, json_encode([$frame->data, $frame->fd, '李永政']));
           }
       }
   }


    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

});

$server->on('close', function($server, $fd) {
    echo "connection close: {$fd}\n";
});




$server->start();