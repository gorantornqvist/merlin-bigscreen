<?php
$op5server="localhost";

function Authenticate($user, $password, $authtype) {

  global $op5server;

  $authstring = "$user" . $authtype . ":" . $password;
  $url = "https://" . $op5server . "/api/status/host?format=xml";

    $a_handle = curl_init($url);
    curl_setopt($a_handle, CURLOPT_USERPWD, $authstring);
    curl_setopt($a_handle, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($a_handle, CURLOPT_SSL_VERIFYPEER, false);
    $return = curl_exec($a_handle);
    if ($return != FALSE) {
      $xml = simplexml_load_string($return);
      if ($xml == FALSE) {
        die("Error validating OP5 credentials. (Error processing OP5 XML result)");
      } else {
        if ($xml->param[0]['name'] == "error") {
          die("Authentication error using credentials supplied. (OP5 message: " . $xml->param[1] . ")");
        } else {
          $_SESSION['authenticated'] = "true";
          $_SESSION['authstring'] = $authstring;
          $_SESSION['username'] = $user;
          return true;
        }
      }
    } else {
      die("Error communicating with OP5. " . curl_error($a_handle));
    }

}

function IsLoggedOn() {
  if ($_SESSION['authenticated'] == 'true') {
    return true;
  } else {
    return false;
  }
}

function debugprint($val) {
  echo "<pre>";
  echo print_r($val);
  echo "</pre>";
}



function GetUnhandledServiceProblems() {

  global $op5server;

  $authstring = $_SESSION['authstring'];

  /*
  See:
  https://kb.op5.com/display/GUI/Listview+filter+columns;jsessionid=2F02EE49719B3B2FE38E274EBCD3DAC0#Listviewfiltercolumns-Services
  */

  $query=urlencode("[services] in \"unhandled service problems\"");
  $columns="host.name,description,state,plugin_output,last_state_change,last_check,host.custom_variable_names,host.custom_variable_values,custom_variable_names,custom_variable_values";
  $url = "https://" . $op5server . "/api/filter/query?query=$query&columns=$columns&format=xml";

  if ($_SESSION['authenticated'] == 'true') {
    $a_handle = curl_init($url);
    curl_setopt($a_handle, CURLOPT_USERPWD, $authstring);
    curl_setopt($a_handle, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($a_handle, CURLOPT_SSL_VERIFYPEER, false);
    $return = curl_exec($a_handle);
    if ($return == FALSE) {
      die("Error when verifying user hostgroup permissions in OP5. CURL Error: " . curl_errno($a_handle) . "/" . curl_error($a_handle));
    } else {
      $xml = simplexml_load_string($return);
      if ($xml == FALSE) {
        return false;
      } else {
         if ($xml->param[0]['name'] == "error") {
           die("Cannot access hostgroup details in OP5. Verify that you have permission to the hostgroup in OP5 and that the hostgroup exists. (OP5 message: " . $xml->param[1] . ")");
         } else {
           return $xml;
         }
      }
    }
  } else {
    die("Not authenticated.");
  }
}

function GetUnhandledHostProblems() {

  global $op5server;

  $authstring = $_SESSION['authstring'];

  /*
  See:
  https://kb.op5.com/display/GUI/Listview+filter+columns;jsessionid=2F02EE49719B3B2FE38E274EBCD3DAC0#Listviewfiltercolumns-Services
  */

  $query=urlencode("[hosts] in \"unhandled host problems\"");
  $columns="name,address,state,plugin_output,last_state_change,last_check,custom_variable_names,custom_variable_values";
  $url = "https://" . $op5server . "/api/filter/query?query=$query&columns=$columns&format=xml";

  if ($_SESSION['authenticated'] == 'true') {
    $a_handle = curl_init($url);
    curl_setopt($a_handle, CURLOPT_USERPWD, $authstring);
    curl_setopt($a_handle, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($a_handle, CURLOPT_SSL_VERIFYPEER, false);
    $return = curl_exec($a_handle);
    if ($return == FALSE) {
      die("Error when verifying user hostgroup permissions in OP5. CURL Error: " . curl_errno($a_handle) . "/" . curl_error($a_handle));
    } else {
      $xml = simplexml_load_string($return);
      if ($xml == FALSE) {
        return false;
        die("Error processing OP5 XML result.");
      } else {
         if ($xml->param[0]['name'] == "error") {
           die("Cannot access hostgroup details in OP5. Verify that you have permission to the hostgroup in OP5 and that the hostgroup exists. (OP5 message: " . $xml->param[1] . ")");
         } else {
           return $xml;
         }
      }
    }
  } else {
    die("Not authenticated.");
  }
}
?>
