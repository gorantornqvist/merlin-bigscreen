<?php
session_start();
require "./config.php";
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$ret = IsLoggedOn();
if ($ret == FALSE) {
  header("Location: login.php?redir=" . $_SERVER['SCRIPT_NAME']);
  exit;
}

$user = $_SESSION['username'];

if ($_GET['unknowns'] == "false")
  $skipunknowns = true;

if ($_GET['warnings'] == "false")
  $skipwarnings = true;

if ($_GET['nosla'] == "false")
  $skipnosla = true;

?>
<div class="dash_unhandled hosts dash">
    <h2>Unhandled host problems</h2>
    <div class="dash_wrapper">
        <table class="dash_table">
            <tr class="dash_table_head">
               <th>
                    Host
                </th>
                <th>
                    Address
                </th>
                <th>
                    Output
                </th>
                <th>
                    Last state change
                </th>
                <th>
                    Last check
                </th>
                <th>
                    Host Vars
                </th>
            </tr>
<?php
$xml = GetUnhandledHostProblems();
if ($xml != FALSE) {
foreach ($xml->param as $row) {
  $skiprow = false;

  $hostname = $row->value[0];
  $address = $row->value[1];
  $state = intval($row->value[2]);

  // all host problems are displayed with red background
  $class = "critical";

  $output = $row->value[3];

  $laststatechange = gmdate("Y-m-d H:i", intval($row->value[4]));
  $lastcheck = gmdate("Y-m-d H:i", intval($row->value[5]));

  $arrhostvarnames = $row->value[6];                                                                                                                                                 
  $arrhostvarvalues = $row->value[7];                                                                                                                                                
  $hostcustomvars = "";                                                                                                                                                              
  $index=0;                                                                                                                                                                             
  foreach ($arrhostvarnames->value as $name) {                                                                                                                                       
    if ($index > 0) $hostcustomvars .= ", ";                                                                                                                                         
    $hostcustomvars .= $name . ":" . $arrhostvarvalues->value[$index];                                                                                                            
    if ($skipnosla) {
      if ($name == "SLA" && $arrhostvarvalues->value[$index] == "none") $skiprow = true;
    }
    $index++;                                                                                                                                                                           
  }

  $arrhostvars = $row->value[6];
  $customvars = $arrhostvars->value;

  if (!$skiprow) {
    echo "         <tr class=\"$class\">
                    <td><a target=\"_blank\" href=\"/monitor/index.php/extinfo/details?host=$hostname\">$hostname</td>
                    <td><a target=\"_blank\" href=\"/monitor/index.php/extinfo/details?host=$hostname\">$address</td>
                    <td>$output</td>
                    <td class=\"date date_statechange\">$laststatechange</td>
                    <td class=\"date date_lastcheck\">$lastcheck</td>
                    <td>$hostcustomvars</td>
                </tr>
    ";
   }
}
}
?>
        </table>
    </div>
</div>
<div class="clear"></div>
<div class="dash_unhandled_service_problems hosts dash">
    <h2>Unhandled service problems</h2>
    <div class="dash_wrapper">
        <table class="dash_table">
            <tr class="dash_table_head">
                <th>
                    Host
                </th>
                <th>
                    Service
                </th>
                <th>
                    Output
                </th>
                <th>
                    Last state change
                </th>
                <th>
                    Last check
                </th>
                <th>
                    Host Vars
                </th>
                <th>
                    Service Vars
                </th>
            </tr>
<?php
$xml = GetUnhandledServiceProblems();
if ($xml != FALSE) {
foreach ($xml->param as $row) {
  $skiprow = false;

  $arrhostname = $row->value[0];
  $hostname = $arrhostname->value[0];
  $arrhostvarnames = $arrhostname->value[1];
  $arrhostvarvalues = $arrhostname->value[2];
  $hostcustomvars = "";
  $index=0;
  foreach ($arrhostvarnames->value as $name) {
    if ($index > 0) $hostcustomvars .= ", ";
    $hostcustomvars .= $name . ":" . $arrhostvarvalues->value[$index];
    if ($skipnosla) {
      if ($name == "SLA" && $arrhostvarvalues->value[$index] == "none") $skiprow = true;
    } 
    $index++;
  }

  $arrservicevarnames = $row->value[6];
  $arrservicevarvalues = $row->value[7];
  $servicecustomvars = "";
  $index=0;
  foreach ($arrservicevarnames->value as $name) {
    if ($index > 0) $servicecustomvars .= ", ";
    $servicecustomvars .= $name . ":" . $arrservicevarvalues->value[$index];
    if ($skipnosla) {
      if ($name == "SLA" && $arrservicevarvalues->value[$index] == "none") $skiprow = true;
    }
    $index++;
  }

  $service = $row->value[1];
  $state = intval($row->value[2]);
  if ($state == 2) {
    $class = "critical";
  } elseif ($state == 1) {
    $class = "warning";
    if ($skipwarnings)
        $skiprow = true;
  } elseif ($state == 3) {
    $class = "unknown";
    if ($skipunknowns)
        $skiprow = true;
  }

  $output = $row->value[3];

  $laststatechange = gmdate("Y-m-d H:i", intval($row->value[4]));
  $lastcheck = gmdate("Y-m-d H:i", intval($row->value[5]));

  if (!$skiprow) {

    echo "         <tr class=\"$class\">
                      <td><a target=\"_blank\" href=\"/monitor/index.php/extinfo/details?host=$hostname\">$hostname</td>
                      <td><a target=\"_blank\" href=\"/monitor/index.php/extinfo/details?host=$hostname&service=" . urlencode($service) . "\">$service</td>
                      <td>$output</td>
                      <td class=\"date date_statechange\">$laststatechange</td>
                      <td class=\"date date_lastcheck\">$lastcheck</td>
                      <td>$hostcustomvars</td>
                      <td>$servicecustomvars</td>
                  </tr>
    ";
  }
}
}
?>
        </table>
    </div>
</div>
</body>
</html>
