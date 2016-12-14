FROM library/ubuntu:precise

RUN apt-get update
RUN apt-get --assume-yes install wget
RUN apt-get --assume-yes install gcc
RUN wget --no-check-certificate https://www.python.org/ftp/python/3.2.3/Python-3.2.3.tar.xz
RUN tar xf Python-3.2.3.tar.xz

RUN ./Python-3.2.3/configure
RUN apt-get --assume-yes install make
RUN make
RUN make install

CMD python3 --version
