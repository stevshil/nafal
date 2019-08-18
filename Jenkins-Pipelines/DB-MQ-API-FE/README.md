# Jenkins Pipeline for DB-MQ-API-FE

This pipeline will build a 4 tier project consisting of;
* Database Pod
* Message Queue Pod
* API Pod
* Angular Frontend Pod

The build is parameterised to make the template repeatable and reusable.  The parameters are;
* PROJECTNAME
  * This is the name of your project in OpenShift, must be unique
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
* BRANCH[FE|API|AUX|TEST]
  * The branch to use to compile the code/containers
* ANGULARCLIVERSION
  * The version of Angular required to compile the web site (default = 7.0.3)
* DOCKERREG
  * The URL of the private docker registry (default = dockerreg.conygre.com:5000)

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

All files are in the root of the git repository.openshift/frontend-service.yaml

# OpenShift configuration

The following OpenShift templates are required for each pod in the project;

## Frontend
* openshift/frontend-deploymentconfig.yaml
* openshift/frontend-service.yaml
* openshift/frontend-route.yaml

## API
* openshift/api-deploymentconfig.yaml
* openshift/api-service.yaml
* openshift/api-route.yaml

## Database
* openshift/mq-persistentvolumeclaim.yaml
* openshift/database-deploymentconfig.yaml
* openshift/database-service.yaml

## Message Queue
* openshift/database-persistentvolumeclaim.yaml
* openshift/mq-deploymentconfig.yaml
* openshift/mq-service.yaml

These files should be generated from running the **create-config-files** script and transferring the contents of the config directory to your git repositories.
