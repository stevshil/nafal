# Jenkins Pipeline for DB-MQ-API-FE

This pipeline will build a 4 tier project consisting of;
* Database Pod
* Message Queue Pod
* API Pod
* Angular Frontend Pod

It is recommended that you have separate GIT repositories for;
* Environment
* Database and Message queue
* API
* Frontend
* End-to-end tests

The build is parameterised to make the template repeatable and reusable.  The parameters are;
* PROJECTNAME
  * This is the name of your project in OpenShift, must be unique
* DOMAINNAME
  * The servers FQDN (Fully Qualified Domain name)
* GITREPOENV
  * Git repository containing the OpenShift YAML files to define the environment to launch your containers (default = https://bitbucket.org/stevshil/openshift-templates.git)
* GITREPOFE
  * The git URL for your Frontend repository
  * This should contain the code to build the files and the Dockerfile to build the container
* GITREPOAPI
  * The git URL for your API repository
  * This should contain the code to compile and the Dockerfile to build the container
* GITREPOAUX
  * The git URL for your Database and Message Queue repository Docker container build
* GITREPOTEST
  * The git URL that contains the end-to-end tests
* APIVERSION
  * The version number for the API Pod release (default = 0.0.1)
* DBVERSION
  * The version number for the database pod (default = 0.0.1)
* MQVERSION
  * The version number for the message queue pod (default = 0.0.1)
* FRONTENDVERSION
  * The version number for the frontend pod (default = 0.0.1)
* BRANCH[FE|API|AUX|TEST|ENV]
  * The branch to use to compile the code/containers (default = master)
* ANGULARCLIVERSION
  * The version of Angular required to compile the web site (default = 7.0.3)
* DOCKERREG
  * The URL of the private docker registry (default = dockerreg.conygre.com:5000)
* BUILDAUX
  * Set to **true** if you want to build the database, message queue pods and create the environment (default = false)
  * By default the step will be skipped
* FECHKURL
  * Set to the URL that contains the health check for your web site container (default = /)
* APICHKURL
  * Set to the URL that contains the health check page for your API container (default = /api)

All the above variable are passed to the Jenkins job using the **-e** option to the ```oc start-build```.

# GIT repository layout
The Jenkins pipeline makes the following assumptions about your GIT repository.

## Frontend

The Dockerfile and package.json files are in the root of the git repository.

## API

The Dockerfile and pom.xml files are in the root of the git repository.

## Auxilary

The Dockerfile is in directories in the git repository named as follows;
* db
* mq

The directories are case sensitive, and must be lowercase.

## Tests

All files are to be in the root of the git repository pointed to by GITREPOTEST

## OpenShift configuration files

The Jenkins templates require the use of either the default GITREPOENV or if you choose to use your own GITREPOENV make sure that your git repository is laid out as follows;

```
openshift-config
|
|-ActiveMQ
| |-deploymentConfig.yaml
| |-imagestream.yaml
| |-persistentVolume-claim0
| |-persistentVolume-claim1
| |-route.yaml
| |-service.yaml
|
|-API
| |-deploymentConfig.yaml
| |-imagestream.yaml
| |-route.yaml
| |-service.yaml
|
|-Frontend
| |-deploymentConfig.yaml
| |-imagestream.yaml
| |-route.yaml
| |-service.yaml
|
|-MySQL
| |-deploymentConfig.yaml
| |-imagestream.yaml
| |-route.yaml
| |-service.yaml
```

The contents of these files should match exactly what is in the ones found in the https://bitbucket.org/stevshil/openshift-templates.git repository under the **openshift-config** directory.

## Creating the pipeline

```
oc create -f jenkins-pipeline.yaml
```

## Launching the pipeline

```
oc start-build pipeline -e VERSION=1.0.0 -e otherVariable=...
```
