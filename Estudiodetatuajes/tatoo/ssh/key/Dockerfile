FROM ubuntu:latest

# Instalar SSH
RUN apt-get update && apt-get install -y openssh-server
RUN mkdir /var/run/sshd

# Configurar el acceso SSH
RUN sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config
RUN echo 'root:unipoli' | chpasswd
RUN echo "PubkeyAuthentication yes" >> /etc/ssh/sshd_config
RUN echo "PasswordAuthentication yes" >> /etc/ssh/sshd_config

# Puerto SSH
EXPOSE 22

CMD ["/usr/sbin/sshd", "-D"]
