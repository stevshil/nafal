#!/bin/bash

# Script to be used by PS for creating UAT and Production environments
# Makes use of the templates and similar style for the Jenkins
# Just deploys the framework, as the dockerreg will have the container versions
# uDeploy will then be used for container version deployments

function checkvar() {
  if (( $# < 1 ))
  then
    return 1
  fi

  if echo "$1" | grep '^[  ]*$' >/dev/null 2>&1
  then
    return 1
  else
    return 0
  fi
}

# Generic attributes
echo -n "Enter project name: "
read PROJECTNAME
if ! checkvar "$PROJECTNAME"
then
  echo "A Project Name is required"
  exit 1
fi

echo -n "Enter the DNS name of your OpenShift server for deployment: "
read DOMAINNAME
if ! checkvar "$DOMAINNAME"
then
  echo "A DNS name is required for your OpenShift server"
  exit 5
fi

echo -n "Enter the Docer Registry server [dockerreg.conygre.com:5000]: "
read DOCKERREG
if ! checkvar "$DOCKERREG"
then
  DOCKERREG="dockerreg.conygre.com:5000"
fi

export PROJECTNAME DOCKERREG

# Front end
echo "Do you require a frontend (y/n)? "
read FE
if [[ $FE == y* ]]
then
  echo -n "Enter Frontend container version: "
  read FRONTENDVERSION
  if ! checkvar "$FRONTENDVERSION"
  then
    echo "A version number is required"
    exit 2
  fi
  echo -n "Enter the URL for health check: "
  read FECHKURL
  if ! checkvar "$FECHKURL"
  then
    echo "A health check URL is required"
    exit 3
  fi
  eval echo \"$(cat openshift-config/Frontend/deploymentConfig.yaml)\" | oc apply -f -
  oc apply -f openshift-config/Frontend/service.yaml
  eval echo \"$(cat openshift-config/Frontend/route.yaml)\" | oc apply -f -
  echo "Internet route is `oc get route | awk \'{print $2}\' | grep ${PROJECTNAME}`"
fi

# API
echo -n "Enter Java Application container version: "
read APIVERSION
if ! checkvar "$APIVERSION"
then
  echo "A version is required for the Java Application"
  exit 2
fi
echo -n "Enter Java Application health check URL: "
read APICHKURL
if ! checkvar "$APICHKURL"
then
  echo "A health check URL is required for the Java Application"
  exit 3
fi
eval echo \"$(cat openshift-config/API/deploymentConfig.yaml)\" | oc apply -f -
oc apply -f openshift-config/API/service.yaml'
eval echo \"$(cat openshift-config/API/route.yaml)\" | oc apply -f -
echo "Internet route is `oc get route | awk \'{print $2}\' | grep ${PROJECTNAME}`"

# Message Queue/Broker
echo "Do you require a Message Queue or Order Broker (y/n)? "
read MQOB
if [[ $MQOB == y* ]]
then
  echo -n "Enter the MQ or Order Broker container version: "
  read MQVERSION
  if ! checkvar "$MQVERSION"
  then
    echo "A container version number is required"
    exit 2
  fi
  echo -n "Enter the 1st directory that the container needs to persist: "
  read AMQMOUNT0
  if ! checkvar "$AMQMOUNT0"
  then
    echo "1st directory mount is required"
    exit 3
  fi
  echo -n "Enter the 2nd directory that the container needs to persist: "
  read AMQMOUNT1
  if ! checkvar "$AMQMOUNT1"
  then
    echo "2nd directory mount is required"
    exit 3
  fi
  eval echo \"$(cat openshift-config/ActiveMQ/persistentVolume-claim0.yaml)\" | oc apply -f -
  eval echo \"$(cat openshift-config/ActiveMQ/persistentVolume-claim1.yaml)\" | oc apply -f -
  eval echo \"$(cat openshift-config/ActiveMQ/deploymentConfig.yaml)\" | oc apply -f -
  oc apply -f openshift-config/ActiveMQ/service.yaml
fi

# Database
echo -n "Enter the Database container version: "
read DBVERSION
if ! checkvar "$DBVERSION"
then
  echo "A Database container version is required"
  exit 2
fi
echo -n "Enter the directory that the container needs to persist: "
read DBMOUNT
if ! checkvar "$DBMOUNT"
then
  echo "The database must have a persistent directory"
  exit 3
fi
echo -n "Enter the database password: "
read MYSQLROOTPW
if ! checkvar "$MYSQLROOTPW"
then
  echo "A password is required for the database server"
  exit 4
fi
eval echo \"$(cat openshift-config/MySQL/persistentVolume-claim0.yaml)\" | oc apply -f -
eval echo \"$(cat openshift-config/MySQL/deploymentConfig.yaml)\" | oc apply -f -
oc apply -f openshift-config/MySQL/service.yaml
