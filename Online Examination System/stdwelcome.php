<?php
error_reporting(0);
session_start();
include_once 'database.php';

        if(!isset($_SESSION['stdname'])){
            $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
        }
        else if(isset($_REQUEST['logout'])){
                unset($_SESSION['stdname']);
            $_GLOBALS['message']="You are Loggged Out Successfully.";
            header('Location: index.php');
        }
?>
<html>
    <head>
        <title>DashBoard</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="css/testhome.css"/>
    </head>
    <body>
        <p>
          <?php
       
        if($_GLOBALS['message']) {
            echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
        }
        ?>
        </p>
        <div id="container">
           <div class="header">
                <div id="logo">
	<div align="center">
    
    <img src="images/logo.png" width="274" height="90" />
    online test
  	</div>
    </div>
            </div>
            <div class="menubar">

                <form name="stdwelcome" action="stdwelcome.php" method="post">
                    <ul id="menu">
                        <?php if(isset($_SESSION['stdname'])){ ?>
                        <li><input type="submit" value="LogOut" name="logout" class="btn1" title="Log Out"/></li>
                        <?php } ?>
                    </ul>
                </form>
            </div>
            <div id="secondmain">
                <?php if(isset($_SESSION['stdname'])){ ?>
                <div class="topimg">
                  
                  <center><table width="782">
        <tr>
        <td width="391" height="160" align="center" valign="middle" pa><a href="viewresult.php" alt="View Result"><img src="images/result.jpg" width="117" height="106"/><br>View Result</a></td>
        <td width="366" align="center" valign="middle"><a href="stdtest.php" alt="Take Test"><img src="images/test1.jpg" width="117" height="106"/><br/>Take Test</a></td>
        </tr>
        <tr>
        <td height="160" align="center" valign="middle"><a href="editprofile.php?edit=edit"><img src="images/edit1.jpg" alt="Edit Profile" width="117" height="106"/><br/>Edit Profile</a></td>
        <td align="center" valign="middle"><a href="resumetest.php"/><img src="images/resume_test.jpg" alt="resume test" width="117" height="106"><br/>Resume Test</td>
        </tr>
        
        </table></center>
                        
               </div>
                
                <?php }?>

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
