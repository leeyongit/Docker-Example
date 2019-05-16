
#  $ locust -f locustfile.py -c 1 -n 1 --port=8000
#  -H, --host：被测系统的hos 
#  -P, --port：指定web端口，默认为8089
#  指定并发数（-c）和总执行次数（-n）

#  多进程分布式运行
#  $ locust -H http://test.fshd.com -f demo.py --master --port=8088
#  $ locust -H http://test.fshd.com -f demo.py --slave
#  如果slave与master不在同一台机器上，还需要通过--master-host参数再指定master的IP地址。


#  在这个示例中，定义了针对http://fstcandy.fshd.com网站的测试场景：
#  先模拟用户登录系统，然后随机地访问首页（/）和关于页面（/about/），请求比例为2:1；
#  并且，在测试过程中，两次请求的间隔时间为1~5秒间的随机值
#
#  测试开始后，每个虚拟用户（Locust实例）的运行逻辑都会遵循如下规律：
#  1.先执行WebsiteTasks中的on_start（只执行一次），作为初始化；
#  2.从WebsiteTasks中随机挑选（如果定义了任务间的权重关系，那么就是按照权重关系随机挑选）一个任务执行；
#  3.根据Locust类中min_wait和max_wait定义的间隔时间范围（如果TaskSet类中也定义了min_wait或者max_wait，以TaskSet中的优先），在时间范围中随机取一个值，休眠等待；
#  4.重复2~3步骤，直至测试任务终止。