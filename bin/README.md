# Scripts used by the web application

This directory contains scripts called by the web application, or are management scripts for the NAFAL web service.

## buildnafal

This script is used to compile the nafal image.

It requires a single parameter to be passed which is the version you wish to create.

The script must be ran from the root of this project where cloned, otherwise it will fail to run.

**Example**

```
bin/buildnafal 0.0.1
```

## nafalVersion

This script is used to update your OpenShift servers **nafal** project with the latest version that you have just pushed to your Docker registry.

It requires 3 arguments to be passed on the command line;
* OpenShift login user
* OpenShift login password
* NAFAL image version number

**EXAMPLE**

```
bin/nafalVersion admin somepw 0.0.1
```

## buildUATPROD

This script is called by the web service to create UAT and PROD environments based on the developers pipelines.
