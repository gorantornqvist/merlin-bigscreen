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
$refreshvalue = 30; //value in seconds to refresh page
$pagetitle = "Operations OP5 Dashboard - User $user";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title><?php echo($pagetitle); ?></title>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js">
        </script>
         <style type="text/css">
            * {
                margin: 0;
                padding: 0;
            }
            
            body {
                font-family: sans-serif;
                line-height: 1.4em;
        		overflow-x: hidden;
                background: #404040;
                padding: .5em 1em;
            }
			body A:link {text-decoration: none; color: white;}
            body A:visited {text-decoration: none; color: white;}
            body A:active {text-decoration: none ; color: white;}
            body A:hover {text-decoration: underline; color: yellow;}
            
            table {
               border-collapse: collapse;
               width: 100%;
            }
            
            td {
                padding: .1em 1em;
            }
            
            h1 {
                display: inline-block;
                margin-left: 10px;
            }
            h2 {
                margin: 0 0 .2em 0;
                color: white;
                text-shadow: 1px 1px 0 #000;
                font-size: 1em;
            }
            .clear {
                clear: both;
            }
            .head {
            }
            
            .head th {
            }
            
            .dash {
            }
            .dash_wrapper {
                background: white;
                padding: 1em;
                -moz-border-radius: .5em;
				 -khtml-border-radius: 5px; 
				 -webkit-border-radius: 5px; 
				 border-radius:5px; 
				
            }
            .dash_unhandled {
                float: left;
            }
                .dash_unhandled .dash_wrapper {
                    margin-right: 1em;
                    margin-bottom: 1em;
                }

            .dash_unhandled_service_problems {
                clear: both;
                margin-top: 0em;
            }
            
            .dash_table_head {
                background: -moz-linear-gradient(top center, #d3d3d3, #bdbdbd);
				
				background-color: #d3d3d3;
				
				/* Safari 4-5, Chrome 1-9 */  
				-webkit-gradient(<type>, <point> [, <radius>]?, <point> [, <radius>]? [, <stop>]*) */ background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#d3d3d3), to(#bdbdbd)); 
				
				/* Safari 5.1+, Chrome 10+ */ 
				background: -webkit-linear-gradient(#d3d3d3, #bdbdbd); 
				
				/* Opera 11.10+ */ 
				background: -o-linear-gradient(#d3d3d3, #bdbdbd);
				
                color: #181818;
                text-shadow: 1px 1px 0 #ededed;
            }
            .dash_table_head th {
                padding: .2em 1em;
                border-bottom: 1px solid #757575;
                border-right: 2px groove #aaa;
            }
            .dash_table_head th:first-child {
                border-left: none;
            }
            .dash_table_head th:last-child {
                border-right: none;
            }
            
            .critical {
                background: -moz-linear-gradient(top center, #af1000 50%, #990000 50%);
				
				background-color: #af1000;
				
				/* Safari 4-5, Chrome 1-9 */  
				-webkit-gradient(<type>, <point> [, <radius>]?, <point> [, <radius>]? [, <stop>]*) */ background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#af1000), to(#990000)); 
				
				/* Safari 5.1+, Chrome 10+ */ 
				background: -webkit-linear-gradient(#af1000, #990000); 
				
				/* Opera 11.10+ */ 
				background: -o-linear-gradient(#af1000, #990000);
				
                color: white;
                #text-shadow: 1px 1px 0 #5f0000;
            }
            .critical td {
                border-right: 1px solid #6f0000;
                border-bottom: 1px solid #6f0000;
            }
            .critical A:link {text-decoration: none; color: white;}
            .critical A:visited {text-decoration: none; color: white;}
            .critical A:active {text-decoration: none ; color: white;}
            .critical A:hover {text-decoration: underline; color: yellow;}
            
            .ok {
                background: -moz-linear-gradient(top center, #00b400 50%, #018f00 50%);
                color: white;
                #text-shadow: 1px 1px 0 #015f00;
				
				background-color: #00b400;
				
				/* Safari 4-5, Chrome 1-9 */ /* 
				-webkit-gradient(<type>, <point> [, <radius>]?, <point> [, <radius>]? [, <stop>]*) */ background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#00b400), to(#018f00)); 
				
				/* Safari 5.1+, Chrome 10+ */ 
				background: -webkit-linear-gradient(#00b400, #018f00); 
				
				/* Opera 11.10+ */ 
				background: -o-linear-gradient(#00b400, #018f00);
            }
 
            .warning {
                background: -moz-linear-gradient(top center, yellow 50%, #edef00 50%);
                color: black;
                #text-shadow: -1px -1px 0 #feff5f;
				background-color: #edef00; 
				
            }

            .unknown {
                background: -moz-linear-gradient(top center, orange 50%, #FF9933 50%);
                color: black;
                #text-shadow: -1px -1px 0 #015f00;
                                background-color: #edef00;

            }
                .critical td,
                .ok td,
                .warning td {
                }
            .warning td{
                border-bottom: 1px solid #bdbf00;
                border-right: 1px solid #bdbf00;
            }
            .warning A:link {text-decoration: none; color: black;}
            .warning A:visited {text-decoration: none; color: black;}
            .warning A:active {text-decoration: none ; color: black;}
            .warning A:hover {text-decoration: underline; color: black;}

            .ok td{
                border-bottom: 1px solid #016f00;
                border-right: 1px solid #016f00;
            }

            .unknown td{
                border-bottom: 1px solid #FF9933;
                border-right: 1px solid #FF9933;
            }
            .unknown A:link {text-decoration: none; color: black;}
            .unknown A:visited {text-decoration: none; color: black;}
            .unknown A:active {text-decoration: none ; color: black;}
            .unknown A:hover {text-decoration: underline; color: black;}
            
            .date {
                white-space: nowrap;
                
            }
            
            .statusinfo {
                font-size: 14px !important;
            }
            
            .nagios_statusbar {
                background: -moz-linear-gradient(top center, #6a6a6a, #464646);
				
				background-color: #6a6a6a;
				
				/* Safari 4-5, Chrome 1-9 */  
				-webkit-gradient(<type>, <point> [, <radius>]?, <point> [, <radius>]? [, <stop>]*) */ background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#6a6a6a), to(#464646)); 
				
				/* Safari 5.1+, Chrome 10+ */ 
				background: -webkit-linear-gradient(#6a6a6a, #464646); 
				
				/* Opera 11.10+ */ 
				background: -o-linear-gradient(#6a6a6a, #464646);
				
                position: fixed;
                bottom: 0;
                width: 100%;
                margin: 0 0 0 -1em;
                height: 40px;
                text-align: right;
                border-top: 1px solid #818181;
                opacity: .9;
            }
            .nagios_statusbar_item {
                border-left: 2px groove #000;
                height: 40px;
                line-height: 40px;
                padding: 0 1em;
                color: white;
                text-shadow: 1px 1px 0 black;
                position: relative;
                float: right;
            }

			.link {
                color: white;
				text-decoration: none;
            }

			.link A:hover {
				text-decoration: underline;
				color: red;
			}
            
            #nagios_placeholder {
            }
            #loading {
                background: transparent no-repeat center center;
                width: 24px;
                height: 40px;
                position: absolute;
            }
            #refreshing {
                padding-left: 35px;
            }
            #refreshing_countdown {
            }
            #timestamp_wrap {
                cursor: default;
                font-size: 2em;
            }
            .timestamp_stamp {
            }
        </style>

    </head>
    <body>
        <script type="text/javascript">

            var placeHolder,
            refreshValue = <?php print $refreshvalue; ?>;
            
            $().ready(function(){
                placeHolder = $("#nagios_placeholder");
                updateNagiosData(placeHolder);
                window.setInterval(updateCountDown, 1000);
            });
            
            
            
            // timestamp stuff
            
            function createTimeStamp() {
                // create timestamp
                var ts = new Date();
                ts = ts.toTimeString();
                ts = ts.replace(/\s+GMT.+/ig, "");
                ts = ts.replace(/\:\d+(?=$)/ig, "");
                $("#timestamp_wrap").empty().append("<div class=\"timestamp_drop\"></div><div class=\"timestamp_stamp\">" + ts +"</div>");
            }
            
            function updateNagiosData(block){
                $("#loading").fadeIn(200);
    			block.load("./main.php?unknowns=<?php echo $_GET['unknowns']?>&warnings=<?php echo $_GET['warnings']?>&nosla=<?php echo $_GET['nosla']?>", function(response){
                    $(this).html(response);
                    $("#loading").fadeOut(200);
                    createTimeStamp();
                });
            }
            
            function updateCountDown(){
                var countdown = $("#refreshing_countdown"); 
                var remaining = parseInt(countdown.text());
                if(remaining == 1){
                    updateNagiosData(placeHolder);
                    countdown.text(remaining - 1);
                }
                else if(remaining == 0){
                    countdown.text(refreshValue);
                }
                else {
                    countdown.text(remaining - 1);
                }
            }
            
        </script>
	<div id="nagios_placeholder"></div>
    <div class="nagios_statusbar">
        <div class="nagios_statusbar_item">
            <div id="timestamp_wrap"></div>
        </div>
        <div class="nagios_statusbar_item">
            <div id="loading"></div>
            <p id="refreshing">Refresh in <span id="refreshing_countdown"><?php print $refreshvalue; ?></span> seconds</p>
        </div>
    </div>
    </body>
</html>
