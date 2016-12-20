FROM library/ubuntu:precise


RUN apt-get update

RUN apt-get --assume-yes install wget \
      gcc \
      libghc-zlib-dev \
      zlib1g-dev \
      zlib1g

RUN wget --no-check-certificate https://www.python.org/ftp/python/3.2.3/Python-3.2.3.tar.xz
RUN tar xf Python-3.2.3.tar.xz

RUN ./Python-3.2.3/configure --with-zlib-dir=/usr/local/lib
RUN apt-get --assume-yes install make
RUN make -j5
RUN make -j5 install

# Installing python modules
RUN wget --no-check-certificate https://bootstrap.pypa.io/get-pip.py
RUN python3 get-pip.py
RUN pip install rsa

COPY src /src

