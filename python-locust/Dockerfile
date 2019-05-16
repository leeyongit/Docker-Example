FROM ubuntu:14.04
RUN cp /etc/apt/sources.list /etc/apt/sources.list.bak
COPY sources.list .
RUN mv sources.list /etc/apt/sources.list
RUN sudo apt-get update
RUN sudo apt-get -y upgrade 
RUN sudo apt-get -y install python
#sudo apt-get install python3.4  
RUN sudo apt-get install  -y python-pip

RUN sudo apt-get -y install libxml2-dev libxslt1-dev python-dev
RUN sudo apt-get -y install zlib1g-dev
RUN sudo apt-get -y install libevent-dev
RUN pip install greenlet
RUN easy_install greenlet
RUN pip install locustio
EXPOSE 8000
WORKDIR /home
