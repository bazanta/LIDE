FROM debian

MAINTAINER Jerome<jerome.martinsmosca@etud.univ-angers.fr>

RUN  apt-get update

# Application
RUN apt-get install -y wget
RUN apt-get install -y ssh

# c et c++
RUN apt-get install -y g++  

# java
#RUN apt-get update && \
#    DEBIAN_FRONTEND=noninteractive \
#    apt-get -y install default-jre-headless && \
#    apt-get clean && \
#    rm -rf /var/lib/apt/lists/*

WORKDIR /home
