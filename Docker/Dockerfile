FROM webdevops/php-apache:7.4
RUN apt-get -y update
RUN apt-get -y install software-properties-common
RUN apt-get -y update
# OpenShift command oc required
COPY webservice /app/webservice
COPY Jenkins-Pipelines /app/Jenkins-Pipelins
COPY openshift-config /app/openshift-config
COPY Docker/index.php /app/index.php
COPY Docker/vhost.conf /opt/docker/etc/httpd/vhost.conf
RUN chown root:root /opt/docker/etc/httpd/vhost.conf
RUN chmod 644 /opt/docker/etc/httpd/vhost.conf
ADD https://github.com/openshift/origin/releases/download/v3.7.0/openshift-origin-client-tools-v3.7.0-7ed6862-linux-64bit.tar.gz /tmp
RUN cd /tmp; tar xvf /tmp/openshift-origin-client-tools-v3.7.0-7ed6862-linux-64bit.tar.gz
RUN mv /tmp/openshift-origin-client-tools-v3.7.0-7ed6862-linux-64bit/oc /bin/oc
RUN rm -rf /tmp/openshift-*
RUN groupadd -g 999 webapp
RUN useradd -r -m -u 999 -g 999 webapp
USER webapp
