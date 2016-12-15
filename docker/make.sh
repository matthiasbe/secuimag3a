#! /bin/sh

cd build

sudo docker build -t python_rsa .
sudo docker run --tty python_rsa
