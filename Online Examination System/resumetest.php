<?php
error_reporting(0);
session_start();
include_once 'database.php';
$tc1=$_COOKIE['TC'];
$tc=date('H:i:s',$tc);
if(!isset($_SESSION['stdname'])) {
    $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
}
else if(isset($_REQUEST['logout'])) {
    //Log out and redirect login page
        unset($_SESSION['stdname']);
        header('Location: index.php');

    }
    else if(isset($_REQUEST['dashboard'])) {
        //redirect to dashboard
            header('Location: stdwelcome.php');

        }
        else if(isset($_REQUEST['resume'])) {
            //test code preparation
                if($r=mysql_fetch_array($result=executeQuery("select testname from test where testid=".$_REQUEST['resume'].";"))) {
                    $_SESSION['testname']=htmlspecialchars_decode($r['testname'],ENT_QUOTES);
                    $_SESSION['testid']=$_REQUEST['resume'];
                }
            }
            else if(isset($_REQUEST['resumetest'])) {
                //Prepare the parameters needed for Test Conducter and redirect to test conducter
                    if(!empty($_REQUEST['tc'])) {
                        $result=executeQuery("select DECODE(testcode,'oespass') as tcode from test where testid=".$_SESSION['testid'].";");

                        if($r=mysql_fetch_array($result)) {
                            if(strcmp(htmlspecialchars_decode($r['tcode'],ENT_QUOTES),htmlspecialchars($_REQUEST['tc'],ENT_QUOTES))!=0) {
                                $display=true;
                                $_GLOBALS['message']="You have entered an Invalid Test Code.Try again.";
                            }
                            else {
                            //now prepare parameters for Test Conducter and redirect to it.

                                $result=executeQuery("select totalquestions,duration from test where testid=".$_SESSION['testid'].";");
                                $r=mysql_fetch_array($result);
                                $_SESSION['tqn']=htmlspecialchars_decode($r['totalquestions'],ENT_QUOTES);
                                $_SESSION['duration']=htmlspecialchars_decode($r['duration'],ENT_QUOTES);
                                $result=executeQuery("select DATE_FORMAT(starttime,'%Y-%m-%d %H:%i:%s') as startt,DATE_FORMAT(endtime,'%Y-%m-%d %H:%i:%s') as endt from studenttest where testid=".$_SESSION['testid']." and stdid=".$_SESSION['stdid'].";");
                                $r=mysql_fetch_array($result);
                                $_SESSION['starttime']=$r['startt'];
                                $_SESSION['endtime']=$r['endt'];
                                $_SESSION['qn']=1;
                                header('Location: testconducter.php');
                            }

                        }
                        else {
                            $display=true;
                            $_GLOBALS['message']="You have entered an Invalid Test Code.Try again.";
                        }
                    }
                    else {
                        $display=true;
                        $_GLOBALS['message']="Enter the Test Code First!";
                    }
                }


?>

<html>
    <head>
        <title>Resume Test</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
        <meta http-equiv="PRAGMA" content="NO-CACHE"/>
        <meta name="ROBOTS" content="NONE"/>

        <link rel="stylesheet" type="text/css" href="css/testhome.css"/>
        <script type="text/javascript" src="validate.js" ></script>
    </head>
    <body >
<?php

if($_GLOBALS['message']) {
    echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
}
?>
        <div id="container">
            <div class="header">
            <div id="logo">
			<div align="center">
    			<img src="images/logo.png" width="274" height="90" />
    online test
  			</div>
 			</div>
            </div>
            <form id="summary" action="resumetest.php" method="post">
                <div class="menubar">
                    <ul id="menu">
        <?php if(isset($_SESSION['stdname'])) {
// Navigations
    ?>
                        <li><input type="submit" value="LogOut" name="logout" class="btn1" title="Log Out"/>&nbsp;&nbsp;&nbsp;</li>
                        <li><input type="submit" value="Home" name="dashboard" class="btn1" title="Dash Board"/></li>

                    </ul>


                </div>
                <div id="secondmain">

    <?php
    if(isset($_REQUEST['resume'])) {
        echo "<div class=\"pmsg\" style=\"text-align:center;\">  </div>";
    }
    else {
        
    }
    ?>
                        <?php

                        if(isset($_REQUEST['resume'])|| $display==true) {
                            ?>
                   <center><h2><?php echo $tc; ?>Resume Test <font color="#1A8CCE" face="Comic Sans MS, cursive"><?php echo $_SESSION['testname']; ?></font></h2><hr><br>
                    <table>
                        <tr>
                            <td>Enter Test Code</td>
                            <td><input type="text" tabindex="1" name="tc" value="" size="16" /></td>
                            <td><div class="help"><b>Note:</b><br/>Quickly enter Test Code and<br/> press Resume button to utilize<br/> Remaining time.</div></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <br/><center><input type="submit" tabindex="3" value="Resume Test" name="resumetest" class="btn" /></center><br/>
                            </td>
                        </tr>
                    </table>
</center>

    <?php
    }
    else {

        $result=executeQuery("select t.testid,t.testname,DATE_FORMAT(st.starttime,'%d %M %Y %H:%i:%s') as startt,sub.subname as sname,TIMEDIFF(st.endtime,CURRENT_TIMESTAMP) as remainingtime from subject as sub,studenttest as st,test as t where sub.subid=t.subid and t.testid=st.testid and st.stdid=".$_SESSION['stdid']." and st.status='inprogress' order by st.starttime desc;");
        if(mysql_num_rows($result)==0) {
            echo"<h3 style=\"color:#0000cc;text-align:center;\">There are no incomplete exams, that needs to be resumed! Please Try Again..!</h3>";
        }
        else {
        //editing components
            ?>
            	<center><h2>Resume Test</h2><hr><br></center>
                    <table width="940">
                        <tr>
                            <th>Date and Time</th>
                            <th>Test</th>
                            <th>Subject</th>
                            <th>Remaining Time</th>
                            <th>Resume</th>
                        </tr>
                                <?php
								$time1=strtotime('00:00:00');
								$set=date('H:i:s',$time1);
								$stt=new Datetime();
                                while($r=mysql_fetch_array($result)) {
                                    $i=$i+1;
                                    if(($r['remainingtime']-$set)<00) {
                //IF Suppose MySQL Event fails for some reasons to change status this condtion becomes true.

                   executeQuery("update studenttest set status='over' where stdid=".$_SESSION['stdid']." and testid=".$r['testid'].";");
                // continue ;
                }

                if($i%2==0) {
                    echo "<tr>";
                                        }
                                        else { echo "<tr>";}
                                        echo "<td>".$r['startt']."</td><td>".htmlspecialchars_decode($r['testname'],ENT_QUOTES)."</td>
<td>".htmlspecialchars_decode($r['sname'],ENT_QUOTES)."</td>
<td>".$r['remainingtime']."</td>";
echo"<td class=\"tddata\"><a title=\"Resume\" href=\"resumetest.php?resume=".$r['testid']."\"><img src=\"images/resume.png\" height=\"30\" width=\"60\" alt=\"Resume\" /></a></td></tr>";
                                    }

                                    ?>

                    </table>
                                <?php
                                }

                            }

                            closedb();
                        }
                        ?>
                        
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

