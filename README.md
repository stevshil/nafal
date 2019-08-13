# OpenShift Templates

This repository provides some common application templates for;

* OpenShift project configuration files
* Jenkins Pipeline templates to build projects

In the files we try to use some common names, so that you could use scripts to replace the variable names with to automate the build of your final project templates.

NOTE: This is not a definitive set, and you may need to add extras to the files if you have multiple port numbers, etc.

## Variables

| VARIABLE Name | Description |
| :------------- | :---------- |
| CONTAINERNAME | The name to be given to the deployment configuration and the container/pod |
| MINIMUMPODS | Minimum number of pods to launch on start |
| IMAGESTREAM | The name of the Docker image either local or from a Docker registry<br>SYNTAX:<br>* myproject/image:tag<br>* docker.reg:port/myproject/image:tag<br>EXAMPLES:<br>* trader/server:1.0.0<br>* private.docker.local:5000/trader/server:1.0.0 |
| PORTNO | The exposed port number for the public facing service |
| CONTAINERVOLUME | The location within the container that can be mapped to an external folder, the left part of the : in a -v docker run |
| PVCLAIMNAME | The unique name to give to your persistent storage in OpenShift |
| OPENSHIFTIMAGEDISPLAYNAME | Name associated to Docker image in OpenShift |
| DOCKERIMAGETAG | The Tag only part of the Docker image, e.g. the version of your image |
| CLAIMSIZE | The amount of disk space required for persistent storage, including units (e.g. Mi, Gi) |
| PROJECTNAME | The name of your project |
| PUBLICURL | The fully qualified domain name (FQDN) for the people to access your service over the Iternet |
| FAILURETHRESHOLD | Number of times to check the readiness of a container before declaring it not ready \* |
| CHKURL | The status URL of the container to check to see if it is ready \* |
| CHKPROTOCOL | HTTP, HTTPS or TCP |
| INITIALDELAY | Time in seconds to wait before testing the readiness probe |
| DELAYPERIOD | Time to wait in seconds between checks, upto the FAILURETHRESHOLD count |
| SUCCESSTIME | Number of times the readiness probe returns OK for a success |
| TIMEOUT | How long in seconds to wait before giving up on the check and trying again (if specified to do so) |
| MAXMEMORY | The maximum amount of memory, including units (Mi, Gi), the pod can use |
| MINMEMORY | The amount of memory, including units, the pod must have to be able to launch |
