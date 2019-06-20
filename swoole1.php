<?php
$server = new swoole_websocket_server("0.0.0.0", 9502);

$server->on('open', function($server, $req) {
    echo "connection open: {$req->fd}\n";
});

$server->on('message', function($server, $frame) {
    echo "received message: {$frame->data}\n";
//				$server->push($frame->fd, json_encode(["hello", "world   aaaaa"]));
    foreach($server->connections as $fds){
        if($server->isEstablished($fds)){
            $server->push($fds,json_encode([$frame->data,$frame->fd,'李永政']));
        }
    }
});




$server->on('close', function($server, $fd) {
    echo "connection close: {$fd}\n";
});




$server->start();