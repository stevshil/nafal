FROM webdevops/php-apache:7.4
RUN apt-get -y update
RUN apt-get -y install software-properties-common
# OpenShift command oc required
COPY webservice /app/webservice
COPY Jenkins-Pipelines /app/Jenkins-Pipelines
COPY openshift-config /app/openshift-config
COPY Docker/index.php /app/index.php
ADD bin /app/bin

# Use the next line for new versions of oc
#ADD https://github.com/openshift/origin/releases/download/v3.7.0/openshift-origin-client-tools-v3.7.0-7ed6862-linux-64bit.tar.gz /tmp
# RUN cd /tmp; tar xvf /tmp/openshift-origin-client-tools-v3.7.0-7ed6862-linux-64bit.tar.gz
# RUN mv /tmp/openshift-origin-client-tools-v3.7.0-7ed6862-linux-64bit/oc /bin/oc
# RUN rm -rf /tmp/openshift-*
# Next line is just in case our version of oc disappears
COPY files/oc /bin/oc
