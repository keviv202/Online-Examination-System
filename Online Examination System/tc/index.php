<?php
error_reporting(0);
session_start();
include_once '../oesdb.php';
if(isset($_REQUEST['tcsubmit']))
      {
          $result=executeQuery("select *,DECODE(tcpassword,'oespass') as tc from testconductor where tcname='".htmlspecialchars($_REQUEST['name'],ENT_QUOTES)."' and tcpassword=ENCODE('".htmlspecialchars($_REQUEST['password'],ENT_QUOTES)."','oespass')");
          if(mysql_num_rows($result)>0)
          {

              $r=mysql_fetch_array($result);
              if(strcmp(htmlspecialchars_decode($r['tc'],ENT_QUOTES),(htmlspecialchars($_REQUEST['password'],ENT_QUOTES)))==0)
              {
                  $_SESSION['tcname']=htmlspecialchars_decode($r['tcname'],ENT_QUOTES);
                  $_SESSION['tcid']=$r['tcid'];
                  unset($_GLOBALS['message']);
                  header('Location: tcwelcome.php');
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
    <link rel="stylesheet" type="text/css" href="../css/testhome.css"/>
  </head>
  <body>
      <?php

        if(isset($_GLOBALS['message']))
        {
         echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
        }
      ?>
      
      <div id="container">
            
               <div class="header">
                <div id="logo">
	<div align="center">
    
    <img src="../images/logo.png" width="274" height="90" />
    online test
  	
    </div>
    </div>
            </div>
     <form id="tcloginform" action="index.php" method="post">
    <!--   <div class="menubar">
       
       <ul id="menu">
                    <?php if(isset($_SESSION['tcname'])){
                          header('Location: tcwelcome.php');}
                          /***************************** Step 2 ****************************/
                        ?>

                       <li><input type="submit" value="Register" name="register" class="subbtn" title="Register"/></li>
           <li></li>
                       
                    </ul>

      </div>-->
      
		<div id="main">
        <div class="admin_login">
              <center>
              <h2>Test Cunductor</h2>
              <table>
              <tr>
                  <td width="85" height="45">TC Name</td>
                  <td width="165"><input type="text" tabindex="1" name="name" value="" size="16" /></td>

              </tr>
              <tr>
                  <td height="45">Password</td>
                  <td><input type="password" tabindex="2" name="password" value="" size="16" /></td>
              </tr>

              <tr>
                  <td colspan="2">
                    <center> <input type="submit" tabindex="3" value="Log In" name="tcsubmit" class="btn" /></center>
                  </td><td width="10"></td>
              </tr>
            </table>

	</center>
      </div>
      </div>
       </form>

     <div id="footer">
<center>
  <h3>Design By CapsLOCK<br /></h3>
    <font color="#fffff" face="Comic Sans MS, cursive">copyright © 2013 All Right Reserved</font>  
  </center>      </div>
      </div>
  </body>
</html>
