<?php


//创建websocket服务器对象，监听0.0.0.0:9502端口
$ws = new swoole_websocket_server("0.0.0.0", 9502);

$ws->set(array(
    'worker_num' => 4,
    'daemonize' => false,
    'backlog' => 128,
));
//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    echo "connection open: ".$request->fd."\n";
    $ws->push($request->fd, "hello, welcome $request->fd\n");
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {

    echo "message: ".$frame->data."\n";
    foreach($ws->connections as $fd)
    {
        $ws->push($fd, $frame->data);
    }
     //$ws->push($frame->fd, "from {$frame->fd}: {$frame->data}");
     //$ws->push($frame->fd, "server: {$frame->data}");
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();