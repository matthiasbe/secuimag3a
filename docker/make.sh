#! /bin/sh

cd build

sudo docker build -t python_rsa .
sudo docker run --tty --interactive python_rsa
