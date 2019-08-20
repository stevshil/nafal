# OpenShift Templates

This project contains templates and a Jenkins Pipeline automation to build varying projects within OpenShift and publish and pull containers from a private Docker Registry, rather than using the inbuilt OpenShift Docker Registry.

To use this project you should clone or fork a copy and modify to suit your requirements.

For example the **bin/create-dbapp** file should be modified to contain the variables and values you need to pass to the Jenkins job to launch your pipeline and application configuration, see the **DB-APP/jenkins-pipeline.yaml** and **DB-APP/README.md** for the variables and their defaults.

The Jenkins pipelines will create the development environment pipeline for CI, whilst the **openshift-config** directory has templates that you may wish to alter depending on whether you want to change storage size or add other features to the Pods.  These templates are currently set for a default, and the deployment configs for the Pods have set names of;

* mysql    -> The database server hostname
* activemq -> The message queue server
* api      -> The Java application tier
* frontend -> Angular built web server

Running the **create-dbapp** script will build a Maven Java Springboot container, and a basic MySQL database unconnected just to show the feature and workings.
