FROM webdevops/php-apache:7.4
RUN apt-get -y update
RUN apt-get -y install software-properties-common
RUN apt-get -y update
# OpenShift command oc required
ADD https://github.com/openshift/origin/releases/download/v3.7.0/openshift-origin-client-tools-v3.7.0-7ed6862-linux-64bit.tar.gz
RUN mv openshift-origin-client-tools-v3.7.0-7ed6862-linux-64bit/oc /bin/oc
