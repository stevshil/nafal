FROM webdevops/php-apache:7.4
RUN apt-get -y update
RUN apt-get -y install software-properties-common
# OpenShift command oc required
COPY webservice /app/webservice
COPY Jenkins-Pipelines /app/Jenkins-Pipelines
COPY openshift-config /app/openshift-config
COPY Docker/index.php /app/index.php
#COPY Docker/apache/vhost.conf /opt/docker/etc/httpd/vhost.conf
#COPY Docker/apache/envvars /etc/apache2/envvars
#COPY Docker/php-fpm/www.conf.default /opt/docker/etc/php/fpm/pool.d/www.conf.default
#RUN chown root:root /opt/docker/etc/httpd/vhost.conf
#RUN chmod 644 /opt/docker/etc/httpd/vhost.conf
ADD https://github.com/openshift/origin/releases/download/v3.7.0/openshift-origin-client-tools-v3.7.0-7ed6862-linux-64bit.tar.gz /tmp
RUN cd /tmp; tar xvf /tmp/openshift-origin-client-tools-v3.7.0-7ed6862-linux-64bit.tar.gz
RUN mv /tmp/openshift-origin-client-tools-v3.7.0-7ed6862-linux-64bit/oc /bin/oc
RUN rm -rf /tmp/openshift-*
#RUN groupadd -g 999 webapp
#RUN useradd -r -m -u 999 -g 999 webapp
#USER webapp
#RUN chmod -R 777 /entrypoint.d
#ENV SERVICE_SUPERVISOR_USER webapp
#ENV UID 999
#env CONTAINER_UID 999
#EXPOSE 1080
