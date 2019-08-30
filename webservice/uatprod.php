<html>
<style>
body {text-align: center}
</style>
<?php
# Required fields
$required=array("PROJECTNAME","DOMAINNAME","ocuser","ocpasswd");
$chkfields=array("PROJECTNAME","DOMAINNAME","DOCKERREG","FRONTENDVERSION","FECHKURL","APIVERSION","APICHKURL","MQVERSION","AMQMOUNT0","AMQMOUNT1","DBVERSION","DBMOUNT","MYSQLROOTPW","FEPORT","APIPORT");
header('Content-Encoding: none'); // Disable gzip compression
ob_implicit_flush(true); // Implicit flush at each output command
?>

<h1><img src="images/nafal.png" height="60" width="100" align='absmiddle'> NAFAL</h1>
<p>Nearly as fast as lighspeed</p>

<h3>Please select the type of set up required</h3>
<?php if (! array_key_exists("jenkins",$_POST)) { ?>
<form name="get_type" action="uatprod.php" method="post" name='get_type' value='true'>
<?php } else { ?>
<form name="create" action="uatprod.php" method="post" value='create'>
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
        $fieldName=substr($line,strpos($line,':')+2);
        $fchk=0;
        if ( in_array($fieldName, $chkfields )) {
          $fchk=1;
          if ( $count == 1 ) {
            echo ">";
            $count++;
          } else {
            echo "</td></tr><tr><td>";
          }
          echo $fieldName;
          echo ":</td><td><input type=text size=80 name='$fieldName' ";
        }
      }
      if ( preg_match('/description:/',$line) && $fchk == 1 ) {
        $fieldName=substr($line,strpos($line,':')+2);
        echo "title='$fieldName' ";
      }
      if ( preg_match('/value:/',$line) && $fchk == 1 ) {
        $fieldName=substr($line,strpos($line,':')+2);
        $fieldName=preg_replace('/dev[0-9]/','uat',$fieldName);
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
  # Build export variables and run ../bin/buildUATPROD script
  foreach ( array_keys($uservalues) as $key ) {
    if ( $key != "create" && $key != "jenkins" )
      putenv("{$key}=".$uservalues[$key]);
      #$export="export {$key}='".$uservalues[$key]."';$export";
  }
  #exec("{$export};
  exec("../bin/buildUATPROD",$output,$exitSTATUS);
  if ( ! $exitSTATUS ) {
    echo "Environment built<br>";
  } else {
    echo "Build failed: ".implode(" ",$output);
    exit(1);
  }
}
?>
</html>
