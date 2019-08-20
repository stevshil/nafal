# Binaries

This directory contains scripts and commands to build an OpenShift configuration for deployment.

It makes use of the template directories to build your OpenShift configuration from the questions answered.

## Create OpenShift configuration

Use the command ```create-config``` to build a set of OpenShift YAML files that can be loaded into OpenShift using;
```
oc apply -f fileName.yml
```

Where fileName is the name of the final files to load.
