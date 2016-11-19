<?php
error_reporting(E_ALL);
include_once '../database.php';

/********************* Step 1 *****************************/
session_start();
        if(!isset($_SESSION['admname'])){
            $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
        }
        else if(isset($_REQUEST['logout'])){
           unset($_SESSION['admname']);
            $_GLOBALS['message']="You are Loggged Out Successfully.";
            header('Location: index.php');
        }
?>

<html>
    <head>
        <title>Home</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="../css/testhome.css"/>
    </head>
    <body>
        <?php
       /********************* Step 2 *****************************/
        if(isset($_GLOBALS['message'])) {
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
            <div class="menubar">

                <form name="admwelcome" action="admwelcome.php" method="post">
                    <ul>
                        <?php if(isset($_SESSION['admname'])){ ?>
                        <li><input type="submit" value="LogOut" name="logout" class="btn1" title="Log Out"/></li>
                        <?php } ?>
                    </ul>
                </form>
            </div>
            <div id="main">
                <?php if(isset($_SESSION['admname'])){ ?>
		<center><table width="782">
        <tr>
        <td width="391" height="160" align="center" valign="middle" pa><a href="usermng.php" alt="Manage Users"><img src="../images/muser.jpg" width="117" height="106"/><br>Manage User</a></td>
        <td width="366" align="center" valign="middle"><a href="submng.php"><img src="../images/msub.png" width="117" height="106"/><br/>Manage Subject</a></td>
        </tr>
        <tr>
        <td height="160" align="center" valign="middle"><a href="testmng.php?forpq=true"><img src="../images/pques.jpg" width="117" height="106"/><br/>Prepare Question</a></td>
        <td align="center" valign="middle"><a href="testmng.php"/><img src="../images/test.jpg"/ width="117" height="106"><br/>Manage Test</td>
        </tr>
        <tr align="center" valign="middle">
        <td height="160" width="391"><a href="rsltmng.php"><img src="../images/mresult.jpg" width="117" height="106"/><br/>Manage Result</a></td>
        </tr>
        </table></center>
               
                <?php }?>

            </div>

          <div id="footer">
  
  <center>
  <h3>Design By CapsLOCK<br /></h3>
    <font color="#fffff" face="Comic Sans MS, cursive">copyright © 2013 All Right Reserved</font>  
  </center>
</div>

      </div>
  </body>
</html>
