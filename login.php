<?php
session_start();
require "./config.php";

if (strlen($_GET['redir']) == 0 && strlen($_POST['redir']) == 0) {
  die("This page should only be accessed using links from OP5 Monitor!");
}

$title = "Login to Bigscreen";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $auth = Authenticate($_POST['username'], $_POST['password'], $_POST['authtype']);
  if ($auth == TRUE) {
    header("Location: " . $_POST['redir']); 
  }
} else {
  if ($_GET['autologin'] == "yes") {
    $auth = Authenticate($_GET['username'], $_GET['password'], $_GET['authtype']);
    if ($auth == TRUE) {
      header("Location: " . $_GET['redir']);
    }
  }
}

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
    <head>
        <title>Monitor | $title</title>
        <meta http-equiv='content-type' content='text/html; charset=iso-8859-1' />
        <link href='default.css' rel='stylesheet' type='text/css' />
    </head>";
?>
<BODY>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<CENTER>
<img src="bigscreen.png" border=0/>
<h3>Login</h3>
<FORM ACTION="login.php" METHOD="POST">
<INPUT TYPE="HIDDEN" NAME="redir" VALUE="<?php echo $_GET['redir']?>"/>
<TABLE BORDER=0>
  <TR>
    <TD>Username:</TD>
    <TD><INPUT TYPE="TEXT" NAME="username"/></TD>
  </TR>
  <TR>
    <TD>Password:</TD>
    <TD><INPUT TYPE="PASSWORD" NAME="password"/></TD>
  </TR>
  <TR>
    <TD>Login method:</TD>
    <TD>
      <select name="authtype">
        <option value="">OP5 Local Authentication</option>
        <option value="$SomeADDomain">Some AD Domain</option>
        <option value="$AnotherADDomain">Another AD Domain</option>
      </select> 
    </TD>
    <TR>
    <TD COLSPAN="2" ALIGN="CENTER"><INPUT TYPE="SUBMIT" VALUE="Login"/></TD>
  </TR>
  </TR>
</TABLE>
</FORM>
<CENTER>
</BODY>
</HTML>
