<?php
error_reporting(0);
session_start();
include_once 'database.php';

if(isset($_SESSION['stdname']))
{
	header('Location: stdwelcome.php');
}

      if(isset($_REQUEST['register']))
      {
            header('Location: register.php');
      }
      else if($_REQUEST['stdsubmit'])
      {		
	  		$con = new Mongo();
			if($con){

			$db = $con->oes;

			$people = $db->student;
			$qry = array("stdname" => $_REQUEST['name'], "stdpassword" => $_REQUEST['password']);
			$result = $people->findOne($qry);
		  
          if($result)
          {  
		  			$r=$result;
                  $_SESSION['stdname']=$r['stdname'];
                  $_SESSION['stdid']=$r['stdid'];
                  unset($_GLOBALS['message']);
				  error_reporting(E_ALL);
ini_set('display_errors', 'On');

                  header('Location: stdwelcome.php');
              }else
          {
              $_GLOBALS['message']="Check Your user name and Password.";
          }

          }
          else
          {
              $_GLOBALS['message']="Check Your user name and Password.";
          }
          closedb();
      }
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>Online Examination System</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="css/testhome.css">

  </head>
  <body>
      <?php

        if($_GLOBALS['message'])
        {
         echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
        }
      ?>
      
      
     
      
<div id="container">
<div id="globalheader">

<ul id="globalnav">
<li><a href="http://capslock.co.in/">Capslock.co.in</a></li>
<li><a href="#">&nbsp;&nbsp;&nbsp;&nbsp;Facebook</a></li>
<li><a href="#">Linkdin</a></li>
</ul>
</div>

<div class="header">
<div id="logo">
<div align="center">
    <img src="images/logo.png" width="274" height="90" />
    online test
  </div>
 </div>

<div class="login">

<form action="index.php" method="post">
   <font color="#000000">Username:&nbsp;&nbsp;&nbsp;</font>
  <input type="text" name="name" />
  <br />
  <br />
    <font color="#000000">Password:&nbsp;&nbsp;&nbsp;</font> 
       <input type="password" name="password" />
     <br />
     <br />
       <center>
  <input type="submit" name="stdsubmit" value="login" class="btn" />&nbsp;<?php 
	if(isset($_SESSION['stdname']))
	{
	header('Location: stdwelcome.php');}
	else
	{  
?>

           <input type="submit" name="register" value="Register" class="btn" />
                        
<?php } ?>
  </center> 
  </form>
</div>
</div>

<div id="slider">
<br /><br /><br /><br /><br /><br />
<center>
 <font size="+6" color="#FFFFFF">Slider</font>
</center>
</div>
<div id="maintext">
<div class="maintext_1">
<h1><center>
    Main Text
</center></h1>
</div>
<div class="maintext_2">
<h1><center>Most Recent</center></h1>
</div>
</div>

<div id="footer">
  
  <center>
  <h3>Design By <a href="http://capslock.co.in/">CapsLOCK</a><br /></h3>
    <font color="#fffff" face="Comic Sans MS, cursive">copyright © 2013 All Right Reserved</font>  
  </center>
</div>
</div>
</body>
</html>
