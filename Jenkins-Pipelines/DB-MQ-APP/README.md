# The Jenkins files

In this directory the Jenkins files will build a project with the following pods;

* MySQL Database server with persistent storage
* ActiveMQ server with persistent storage
* Java Spring boot application listening on port 8080 with it's own web front end

The jenkins-pipeline-parameter.yml has the ability to change the image stream Tag version number through a parameter called VERSION.  Once loaded into OpenShift the pipeline is launched using;

```
oc start-build pipeline -e VERSION=1.0.0
```

The jenkins-pipeline.yml uses a default tag version of 1.0.0 that has to be changed in the code of the Git repo.
