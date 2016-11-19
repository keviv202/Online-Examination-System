<?php
error_reporting(0);
session_start();
include_once 'database.php';

if(!isset($_SESSION['starts']))
{
	header('Location: testack.php');
}

if(!isset($_SESSION['stdname'])) {
    $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
}
else if(isset($_REQUEST['logout']))
{
    //Log out and redirect login page
       unset($_SESSION['stdname']);
       header('Location: index.php');

}
else if(isset($_REQUEST['dashboard'])){
    //redirect to dashboard
    //
     header('Location: stdwelcome.php');

    }
    else if(isset($_REQUEST['change']))
    {
        //redirect to testconducter
       
       $_SESSION['qn']=substr($_REQUEST['change'],7);
       header('Location: testconducter.php');

    }
    else if(isset($_REQUEST['finalsubmit'])){
    //redirect to dashboard
    //
	unset($_SESSION['starts']);
     header('Location: testack.php');

    }
     else if(isset($_REQUEST['fs'])){
    //redirect to dashboard
    //
     header('Location: testack.php');

    }

   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
  <head>
    <title>Test Summary</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
    <meta http-equiv="PRAGMA" content="NO-CACHE"/>
    <meta name="ROBOTS" content="NONE"/>

    <link rel="stylesheet" type="text/css" href="css/testhome.css"/>
    <script type="text/javascript" src="validate.js" ></script>
    <script type="text/javascript" src="cdtimer.js" ></script>
    <script type="text/javascript" >
    <!--
        <?php
                $elapsed=time()-strtotime($_SESSION['starttime']);
                if(((int)$elapsed/60)<(int)$_SESSION['duration'])
                {
                    $result=executeQuery("select TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%H') as hour,TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%i') as min,TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%s') as sec from studenttest where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid'].";");
                    if($rslt=mysql_fetch_array($result))
                    {
                     echo "var hour=".$rslt['hour'].";";
                     echo "var min=".$rslt['min'].";";
                     echo "var sec=".$rslt['sec'].";";
                    }
                    else
                    {
                        $_GLOBALS['message']="Try Again";
                    }
                    closedb();
                }
                else
                {
                    echo "var sec=01;var min=00;var hour=00;";
                }
        ?>

    -->
    </script>


    </head>
  <body >
       <?php

        if($_GLOBALS['message']) {
            echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
        }
        ?>
<div id="container">
     <div class="header">
            <img style="margin:10px 2px 2px 10px;float:left;" height="90" width="274" src="images/logo.png" alt="Online Test"/><h3 class="headtext"> &nbsp;Online Examination System </h3><h4 style="color:#ffffff;text-align:center;margin:0 0 5px 5px;"><i>...because Examination Matters</i></h4>
      </div>
      
      <form id="summary" action="summary.php" method="post">
      <div class="menubar">
              
       <?php if(isset($_SESSION['stdname'])) {
                         // Navigations
       ?>
              
      </div>
      
      <div id="secondmain">
                 <table border="0" width="100%" class="ntab">
                  <tr>
                      <th style="width:40%;"><h3><span id="timer" class="timerclass"></span></h3></th>
                      
                  </tr>
              </table>
          <?php

                        $result=executeQuery("select * from studentquestion where testid=".$_SESSION['testid']." and stdid=".$_SESSION['stdid']." order by qnid ;");
                        if(mysql_num_rows($result)==0) {
                          echo"<h3 style=\"color:#0000cc;text-align:center;\">Please Try Again.</h3>";
                        }
                        else
                        {
                           //editing components
                 ?>
          <table width="940">
                        <tr>
                            <th>Question No</th>
                            <th>Status</th>
                            <th>Change Your Answer</th>
                       </tr>
                       
        <?php
                        while($r=mysql_fetch_array($result)) {
                                    $i=$i+1;
                                    if($i%2==0)
                                    {
                                    echo "<tr>";
                                    }
                                    else{ echo "<tr>";}
                                    echo "<td>".$r['qnid']."</td>";
                                    if(strcmp(htmlspecialchars_decode($r['answered'],ENT_QUOTES),"unanswered")==0 ||strcmp(htmlspecialchars_decode($r['answered'],ENT_QUOTES),"review")==0)
                                    {
                                        echo "<td style=\"color:#ff0000\">".htmlspecialchars_decode($r['answered'],ENT_QUOTES)."</td>";
                                    }
                                    else
                                    {
                                        echo "<td>".htmlspecialchars_decode($r['answered'],ENT_QUOTES)."</td>";
                                    }
                                    echo"<td><input type=\"submit\" value=\"Change ".$r['qnid']."\" name=\"change\" class=\"ssubbtn\" /></td></tr>";
                                }

                                ?>
            
              
                    
                            <?php
                            }
                            closedb();

         
                    }echo "</table>"
                    ?>

<br /> <center>
<input type="submit" name="finalsubmit" value="Final Submit" class="btn"/></center>

      </div>

           </form>
    <div id="footer">
<center>
  <h3>Design By <a href="http://capslock.co.in/">CapsLOCK</a><br /></h3>
    <font color="#fffff" face="Comic Sans MS, cursive">copyright © 2013 All Right Reserved</font>  
  </center>
      </div>
      </div>
  </body>
</html>

