from locust import HttpLocust, TaskSet, task
import Queue

class UserBehavior(TaskSet):
    def on_start(self):
        self.client.get("/")

        
    @task(0)
    def index(self):
        self.client.get("/")

    @task(10)
    def login(self):
        try:
            data = self.locust.user_data_queue.get()
        except Queue.Empty:
            print('account data run out, test ended.')
            exit(0)
        print('register with code: {}, email: {}'\
             .format(data['code'], data['email']))
        #self.client.get('/receive/login?code='+data['code']+'&email='+data['email'])
        payload = {
            'code': data['code'],
            'email': data['email']
        }
        self.client.post('/index/login', data=payload)

    @task(0)
    def invite(self):
        try:
            data = self.locust.user_data_queue.get()
        except Queue.Empty:
            print('account data run out, test ended.')
            exit(0)
        print('register with code: {}, email: {}, invite: {}'\
             .format(data['code'], data['email'], data['invite']))
        #self.client.get('/receive/login?code='+data['code']+'&email='+data['email'])
        payload = {
            'code': data['code'],
            'email': data['email'],
            'invite': data['invite']
        }
        self.client.post('/index/login', data=payload)


class WebsiteUser(HttpLocust):
    task_set = UserBehavior
    host = "http://candy.fanstime.org"

    user_data_queue = Queue.Queue()
    for index in range(100000):
        data = {
            "code": "0x09a0B6236D01Fd4d689D27899d3fDC7a6b02%08d" % index,
            "email": "test%08d@fshd.com" % index,
            "invite": "OPDEPIHZ",
        }
        user_data_queue.put_nowait(data)


    min_wait = 1000
    max_wait = 5000
