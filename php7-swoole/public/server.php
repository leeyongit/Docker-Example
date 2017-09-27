<?php
// Server
class Server {
    private $serv;

    public function __construct() {
        $this->serv = new swoole_server("0.0.0.0", 9501);
        $this->serv->set(array(
            'worker_num' => 8,
            'task_worker_num' => 2,
            'daemonize' => false,
        ));

        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));
        $this->serv->on('Close', array($this, 'onClose'));

        $this->serv->start();
    }

    public function onStart( $serv ) {
        echo "Start\n";
    }

    public function onWorkerStart( swoole_server $server, $worker_id) {
        swoole_timer_tick(60000, array($this, 'onTick'));
    }

    public function onConnect( $serv, $fd, $from_id ) {
        $serv->send( $fd, "Hello {$fd}!");
    }

    public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
        echo "Get Message From Client {$fd}:{$data}\n";
        $serv->send($fd, $data);

        // send a task to task worker.
        $param = array(
            'fd' => $fd
        );
        // start a task
        $serv->task( json_encode( $param ) ); // -1 代表不指定task进程

    }

    /**
     * @param swoole_server $serv swole_server 对象
     * @param $task_id 任务id
     * @param $from_id 投递任务的worker_id
     * @param $data 投递的数据
     */
    public function onTask( swoole_server $serv, $task_id, $from_id, $data ) {
        echo "This Task {$task_id} from Worker {$from_id}\n";
        echo "Data: {$data}\n";
        for($i = 0 ; $i < 10 ; $i ++ ) {
            sleep(1);
            echo "Taks {$task_id} Handle {$i} times...\n";
        }
        //$fd = json_decode( $data , true )['fd'];
        //$serv->send( $fd , "Data in Task {$task_id}");
        return "Task {$task_id}'s result";
    }

    /**
     * @param swoole_server $serv swole_server 对象
     * @param $task_id 任务id
     * @param $data 任务返回的数据
     */
    public function onFinish( swoole_server $serv, $task_id, $data) {
        echo "Task {$task_id} finish\n";
        echo "Result: {$data}\n";
    }

    public function onTick() {
        echo "Ping\n";
    }

    public function onClose( $serv, $fd, $from_id ) {
        echo "Client {$fd} close connection\n";
    }
}

// 启动服务器
$server = new Server();