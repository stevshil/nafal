<html>
<h1><img src="images/nafal.png" height="60" width="100" align='absmiddle'> NAFAL</h1>
<p>Nearly as fast as lighspeed</p>

<h3>Please select the type of set up required</h3>
<?php if (! array_key_exists("jenkins",$_POST)) { ?>
<form name="get_type" action="index.php" method="post" name='get_type' value='true'>
<?php } else { ?>
<form name="create" action="index.php" method="post" value='create'>
<input type=hidden name=create value=true>
<?php } ?>
<table>
<tr><td>Select build type:</td><td><select name=jenkins>
<?php
# Get the files and associated name
$selectedType=$_POST['jenkins'];
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
foreach ($buildType as $type) {
  $tmpName=glob("$type/*.name");
  $extLoc=strpos(basename($tmpName[0]),".");
  $actualName=str_replace("_",", ",substr(basename($tmpName[0]),0,$extLoc));
}
?>
<?php
# See https://symfony.com/doc/current/components/yaml.html
use Symfony\Component\Yaml\Yaml;
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
        echo "</td><td><input type=text size=80 name='$fieldName' ";
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
  echo "<h2>Creating project</h2>";
}
?>
</html>
