# OpenShift Templates

This project contains templates and a Jenkins Pipeline automation to build varying projects within OpenShift and publish and pull containers from a private Docker Registry, rather than using the inbuilt OpenShift Docker Registry.

To use this project you should clone or fork a copy and modify to suit your requirements.

For example the **bin/create-dbapp** file should be modified to contain the variables and values you need to pass to the Jenkins job to launch your pipeline and application configuration, see the **DB-APP/jenkins-pipeline.yaml** and **DB-APP/README.md** for the variables and their defaults.

The Jenkins pipelines will create the development environment pipeline for CI, whilst the **openshift-config** directory has templates that you may wish to alter depending on whether you want to change storage size or add other features to the Pods.  These templates are currently set for a default, and the deployment configs for the Pods have set names of;

* mysql    -> The database server hostname
* activemq -> The message queue server
* api      -> The Java application tier
* frontend -> Angular built web server

This whole project has now become a web service and is built using the **bin/buildnafal** command.

# Application specifics

Each of the 4 templates has some specifics which are built in, such as names of the hosts, and directory locations.

The following list is created as;
* Jenkins-Pipeline name (nafal name in select list)

* DB-APP (Database, Application)
* DB-APP-FE (Database, Application, Frontend)
* DB-MQ-APP (Database, MessageQueue, Application)
* DB-MQ-API-FE (Database, MessageQueue, Applicaion, Frontend)

## For all

* Message Queue server
  * hostname **activemq**
  * exposes ports 8161 and 61616
  * public route activemq._server_
* Database server
  * hostname **mysql**
  * exposes port 3306
* API or Application server
  * hostname **api**
  * exposes port 8080 by default
* Frontend server
  * hostname **frontend**
  * exposes port 80 by default

## DB-APP

No special requirements in this
