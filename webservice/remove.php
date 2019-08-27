<html>
<style>
body {text-align: center}
</style>
<h1><img src="images/nafal.png" height="60" width="100" align='absmiddle'> NAFAL</h1>
<p>Nearly as fast as lighspeed</p>

<h2>Remove project</h2>
<p>This page can be used to remove your project completely from OpenShift</p>
<?php
$uservalues=$_POST;
?>

<table align='center'>
<?php
if ( $uservalues["login"] != "true" && $uservalues["ocuser"] == "" ) {
?>
<form name='login' action='remove.php' method='post'>
<input type='hidden' name='login' value='true'>
<tr><td>OpenShift Username:</td><td><input type='text' name='ocuser' value="<?php echo $uservalues['ocuser'] ?>"></td></tr>
<tr><td>OpenShift Password:</td><td><input type='password' name='ocpassed' value="<?php echo $uservalues['ocpasswd'] ?>"></td></tr>
<tr><td>OpenShift Server:</td><td><input type='text' name='DOMAINNAME' value="<?php echo $uservalues['DOMAINNAME'] ?>" title='The DNS name of your OpenShift server'>
<tr><td colspan=2><input type='submit' value='Login'>
</form>
<?php } ?>

<?php
if ( $uservalues["login"] == "true" && $uservalues["ocuser"] != "" ) {
?>
  <form name='delete' action='remove.php' method='post'>
  <input type='hidden' name='delete' value='true'>
  <input type='hidden' name='ocuser' value='<?php echo $uservalues["ocuser"] ?>'>
  <input type='hidden' name='ocpasswd' value='<?php echo $uservalues["ocpasswd"] ?>'>
  <input type='hidden' name='DOMAINNAME' value='<?php echo $uservalues["DOMAINNAME"] ?>'>
  <tr><td colspan=2>Select the project to delete from the list below;</td></tr>
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr><td>Project:</td><td><select name='project'>
  <?php
  exec("oc login --insecure-skip-tls-verify=true -u ".$uservalues['ocuser']." -p ".$uservalues['ocpasswd']." https://".$uservalues['DOMAINNAME'].":8443 >/dev/null 2>&1 &&
    oc describe projects | grep '^Name:' | awk '{print $2}'", $output, $exitStatus);
  #$projects=explode(" ",$output);
  foreach ($output as $project) {
    if ( $project != "default" )
      echo "<option value='$project'>$project</option>\n";
  }
  ?>
  </select></td></tr>
  <tr><td align='center'><input type=submit value="Delete"></td></tr>
  </table>
<?php
}

if ( $uservalues["delete"] == "true" && $uservalues["ocuser"] != "" ) {
  echo "Deleting project ".$uservalues["project"];
  exec("oc login --insecure-skip-tls-verify=true -u ".$uservalues['ocuser']." -p ".$uservalues['ocpasswd']." https://".$uservalues['DOMAINNAME'].":8443 >/dev/null 2>&1 &&
    oc delete project ".$uservalues['project'], $output, $exitStatus);
  echo "<p><input type=button value='Done' onClick='javascript:window.location.href=\"/webservice/remove.php\"'></p>";
}
?>
</html>
