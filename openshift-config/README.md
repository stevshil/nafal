# OpenShift Config Templates

This directory contains the relevant files for the web service to alter so that the skeleton OpenShift configuration can be created.

Each directory defines the different types of service required and the configuration files needed to enable that service to be deployed in OpenShift.

**NOTE:** the imagestream.yaml files are no longer used as we push and pull the builds directly to and from the private Docker registry.
