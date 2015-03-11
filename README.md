# merlin-bigscreen
OP5 Monitor Unhandled Problems on big screen TV

A fork of Morten Bekkelunds nagios-dashboard, see http://dingleberry.me/2010/04/our-new-dashboard/ , based on the OP5 Monitor API.
There is also another fork that uses livestatus that you may want to check out as well:
https://kb.op5.com/display/LIVESTATUS/merlin-dashboard

Note that in my fork, the html/css and fonts/colors may have changed a lot from the original due to lots of non developers playing around :)

Installation instructions:
* Extract the tar ball in the /var/www/html directory of your OP5 Monitor server.
* You can put the files on the another server as well as long as it can reach the op5 monitor server using https - if you do, don´t forget to update the op5server variable at the top of the config.php file.
* Make sure your user has API access permission.
* Go to yourserver/bigscreen in your browser.
* If you use Active Directory authentication you need to edit the login.php file and update the authtype select box and add your domain there.

The following filtering options are available for passing using the querystring by adding it to end of the url in your web browser, example:
http://yourserver/bigscreen/?warnings=false&amp;unknowns=false&amp;nosla=false
The "nosla" option is used to filter out hosts or services which have a custom variable "_SLA" set to the value "none".

You may also use autologin as a start page in a web browser by using url: http://yourserver/bigscreen/login.php?redir=index.php&autologin=true&username=youruser&password=yourpassword

If you want to include filtering options in the redir, you need to urlencode them according to this example:
?redir=index.php%3Funknowns=false%26warnings=false&autologin...
