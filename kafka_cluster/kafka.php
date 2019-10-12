<?php
$topic = 'fshd_nginx_access_log';
$conf = new \Rdkafka\Conf();
$conf->set('group.id', 0);
$conf->set('metadata.broker.list', '114.118.13.40:9092,114.118.13.40:9093,114.118.13.40:9094');
$topicConf = new \Rdkafka\topicConf();
$topicConf->set('request.required.acks', 1);
//在interval.ms的时间内自动提交确认、建议不要启动
//$topicConf->set('auto.commit.enable', 1);
$topicConf->set('auto.commit.enable', 0);
$topicConf->set('auto.commit.interval.ms', 100);
$topicConf->set('auto.offset.reset', 'latest');


/**
earliest 
当各分区下有已提交的offset时，从提交的offset开始消费；无提交的offset时，从头开始消费 
latest 
当各分区下有已提交的offset时，从提交的offset开始消费；无提交的offset时，消费新产生的该分区下的数据 
none 
topic各分区都存在已提交的offset时，从offset后开始消费；只要有一个分区不存在已提交的offset，则抛出异常

**/
// 设置offset的存储为broker
$topicConf->set('offset.store.method', 'broker');

$conf->setDefaultTopicConf($topicConf);

$consumer = new \Rdkafka\kafkaConsumer($conf);
$consumer->subscribe([$topic]); //订阅

echo "wating for message....\n";

while(true) {
    $message = $consumer->consume(120*1000);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo '要处理消息了~~~'."\n";
            echo 'Topic ['.$topic."]\n";
            echo '分区:'.$message->partition."\n";
            echo 'offset:'.$message->offset."\n";
            $messageInfo = $message->payload;
            echo $messageInfo."\n";
             //你的接收处理逻辑
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "No more messages; will wait for more\n";
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Timed out\n";
            break;
        default:
            throw new \Exception($message->errstr(), $message->err);
            break;
    }
    //sleep(1);
}
