<html>
<style>
body {text-align: center}
</style>
<?php
# Required fields
$required=array("PROJECTNAME","DOMAINNAME","GITREPOENV","GITREPOAPI","GITREPODB","ocuser","ocpasswd");
header('Content-Encoding: none'); // Disable gzip compression
ob_implicit_flush(true); // Implicit flush at each output command
?>

<h1><img src="images/nafal.png" height="60" width="100" align='absmiddle'> NAFAL</h1>
<p>Nearly as fast as lighspeed</p>

<h3>Please select the type of set up required</h3>
<?php if (! array_key_exists("jenkins",$_POST)) { ?>
<form name="get_type" action="create.php" method="post" name='get_type' value='true'>
<?php } else { ?>
<form name="create" action="create.php" method="post" value='create'>
<input type=hidden name=create value=true>
<?php } ?>
<table align='center'>
<tr><td>Select build type:</td><td><select name=jenkins>
<?php
# Get the files and associated name
$selectedType=$_POST['jenkins'];
$ocuser=$_POST['ocuser'];
$ocpasswd=$_POST['ocpasswd'];

$buildType=glob("../Jenkins-Pipelines/*");
foreach ($buildType as $type) {
  $tmpName=glob("$type/*.name");
  $extLoc=strpos(basename($tmpName[0]),".");
  $actualName=str_replace("_",", ",substr(basename($tmpName[0]),0,$extLoc));
  echo "<option value='$type/jenkins-pipeline.yaml'";
  if ( "$selectedType" == "$type/jenkins-pipeline.yaml" ) {
    echo " selected";
  } else {
    echo "";
  }
  echo ">".$actualName."</option>\n";
}
echo "</select></td></tr>";

# Get OpenShift username and password for login
if ( ! array_key_exists("jenkins",$_POST) && ! array_key_exists("get_type",$_POST) || ! array_key_exists("create",$_POST)) {
?>
<tr><td>OpenShift Username <font color='red'>*</font>:</td><td><input type="text" size="80" name="ocuser" value="<?php echo $ocuser ?>"></td></tr>
<tr><td>OpenShift Password <font color='red'>*</font>:</td><td><input type="password" size="80" name="ocpasswd" value="<?php echo $ocpasswd ?>"></td></tr>
<?php
echo "<tr><td><p><font color='red'>*</font> = Required fields</p></td></tr>";
}
foreach ($buildType as $type) {
  $tmpName=glob("$type/*.name");
  $extLoc=strpos(basename($tmpName[0]),".");
  $actualName=str_replace("_",", ",substr(basename($tmpName[0]),0,$extLoc));
}
?>
<?php
# Now get the fields and properties to display to the user
if (array_key_exists("jenkins",$_POST) && ! array_key_exists("get_type",$_POST) && ! array_key_exists("create",$_POST)) {
  echo "<tr><td colspan=2>&nbsp;</td></tr>";
  echo "<tr><td colspan=2><h3>Enter properties</h3></td></tr>";
  $attributeFile=fopen($_POST["jenkins"],"r");
  $match=0;
  $count=0;
  while ( !feof($attributeFile) ) {
    $line=trim(fgets($attributeFile));
    if ( preg_match('/env:$/',$line) ) {
      # Process each line until jenkinsfile:
      $match=1;
    }
    if ( preg_match('/jenkinsfile:/',$line) ){
      break;
    }
    if ( $match == 1 ) {
      #echo "$line<br>";
      if ( preg_match('/name:/',$line)) {
        # Print field name
        if ( $count == 1 ) {
          echo ">";
          $count++;
        } else {
          echo "</td></tr><tr><td>";
        }
        $fieldName=substr($line,strpos($line,':')+2);
        echo $fieldName;
        if ( in_array($fieldName, $required )) {
          echo " <font color='red'>*</font>";
        }
        echo ":</td><td><input type=text size=80 name='$fieldName' ";
      }
      if ( preg_match('/description:/',$line)) {
        $fieldName=substr($line,strpos($line,':')+2);
        echo "title='$fieldName' ";
      }
      if ( preg_match('/value:/',$line)) {
        $fieldName=substr($line,strpos($line,':')+2);
        echo "value='$fieldName'";
      }
    }
  }
  echo "></td></tr>";
}
if (! array_key_exists("get_type",$_POST) && ! array_key_exists("create",$_POST)) {
  echo "<tr><td colspan=2><input type='submit'></td></tr>";
}
echo "</table>";
if (array_key_exists("jenkins",$_POST) && array_key_exists("create",$_POST)) {
  # Execute the following command created from the above attributes
  $uservalues=$_POST;

  # Check required fields are present
  foreach ( $required as $field ) {
    if ( $uservalues[$field] == "" ) {
      echo "<h2><font color='red'>$field is required</font></h2>";
      echo "<input type=button onClick='javascript:history.go(-1)' value='Click to got back'>";
      exit(1);
    }
  }
  # Create temporary jenkins file with the default values set as passed, so we can start-build without changing parameters
  $newjenkins="/tmp/jenkins-".strval(uniqid());
  foreach ( array_keys($uservalues) as $key ) {
    $sedptn="{$sedptn} -e 's,J{$key},".$uservalues[$key].",g' ";
  }
  system("sed {$sedptn} ".$uservalues["jenkins"].".save > {$newjenkins}");
  echo "Jenkins file created";
  system(
    "oc login --insecure-skip-tls-verify=true -u ".$uservalues['ocuser']." -p ".$uservalues['ocpasswd']." https://".$uservalues['DOMAINNAME'].":8443 >/dev/null 2>&1;
    oc get project | grep ".$uservalues['PROJECTNAME']." >/dev/null 2>&1",$exitStatus
  );
  # Check if project exists
  # $output=system("oc get project | grep ".$uservalues['PROJECTNAME'],$exitStatus);
  if ( ! $exitStatus ) {
    echo "<h3><font color='red'>";
    echo $uservalues['PROJECTNAME']." already exists.";
    echo "<p></font></h3>";
    echo "Please use a different name.<br>";
    echo "To update your project use <b>apply -f fileName.yaml</b><br>";

    echo "<input type=button onClick='javascript:history.go(-1)' value='Click to go back'></p>";
    exit(1);
  } else {
    echo "<h2>Creating project....</h2>";
  }
  // echo "<p>Waiting for Jenkins to start...</p>";
  // echo "<p><img src='images/loading.gif' height='150' widht='150'>";
  system("oc new-project ".$uservalues['PROJECTNAME']." >/dev/null 2>&1");
  ##system("oc apply -f ".$uservalues['jenkins']." >/dev/null 2>&1 &");
  system("oc apply -f ".$newjenkins);
  sleep(300);
  system("rm -f ".$newjenkins." 1>&2");
  $startCMD="oc start-build pipeline ";
  # The following is no longer required as we set the values in the jenkins file
  #foreach ($_POST as $key => $value) {
  #  if ( $value != NULL && $key != "create" && $key != "jenkins" && $key != "ocuser" && $key != "ocpasswd") {
  #    $startCMD="$startCMD -e $key=$value ";
  #  }
  #}
  system("$startCMD");
  echo "<p>Pipeline started</p>";
}
?>
</html>
