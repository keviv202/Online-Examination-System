<?php

error_reporting(0);
session_start();
include_once 'database.php';
$final=false;

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

    }else if(isset($_REQUEST['next']) || isset($_REQUEST['summary']) || isset($_REQUEST['viewsummary']))
    {
        //next question
        $answer='unanswered';
        if(time()<strtotime($_SESSION['endtime']))
        {
            if(isset($_REQUEST['markreview']))
            {
                $answer='review';
            }
            else if(isset($_REQUEST['answer']))
            {
                $answer='answered';
            }
            else
            {
                $answer='unanswered';
            }
            if(strcmp($answer,"unanswered")!=0)
            {
                if(strcmp($answer,"answered")==0)
                {
                    $query="update studentquestion set answered='answered',stdanswer='".htmlspecialchars($_REQUEST['answer'],ENT_QUOTES)."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                else
                {
                    $query="update studentquestion set answered='review',stdanswer='".htmlspecialchars($_REQUEST['answer'],ENT_QUOTES)."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                if(!executeQuery($query))
                {
                // to do
                $_GLOBALS['message']="Your previous answer is not updated.Please answer once again";
                }
                closedb();
            }
            if(isset($_REQUEST['viewsummary']))
            {
                 header('Location: summary.php');
            }
            if(isset($_REQUEST['summary']))
             {
                     //summary page
                     header('Location: summary.php');
             }
        }
        if((int)$_SESSION['qn']<(int)$_SESSION['tqn'])
        {
        $_SESSION['qn']=$_SESSION['qn']+1;
       
        }
        if((int)$_SESSION['qn']==(int)$_SESSION['tqn'])
        {
           $final=true;
        }

    }
    else if(isset($_REQUEST['previous']))
    {
    // Perform the changes for current question
        $answer='unanswered';
        if(time()<strtotime($_SESSION['endtime']))
        {
            if(isset($_REQUEST['markreview']))
            {
                $answer='review';
            }
            else if(isset($_REQUEST['answer']))
            {
                $answer='answered';
            }
            else
            {
                $answer='unanswered';
            }
            if(strcmp($answer,"unanswered")!=0)
            {
                if(strcmp($answer,"answered")==0)
                {
                    $query="update studentquestion set answered='answered',stdanswer='".htmlspecialchars($_REQUEST['answer'],ENT_QUOTES)."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                else
                {
                    $query="update studentquestion set answered='review',stdanswer='".htmlspecialchars($_REQUEST['answer'],ENT_QUOTES)."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                if(!executeQuery($query))
                {
                // to do
                $_GLOBALS['message']="Your previous answer is not updated.Please answer once again";
                }
                closedb();
            }
        }
        //previous question
        if((int)$_SESSION['qn']>1)
        {
            $_SESSION['qn']=$_SESSION['qn']-1;
        }

    }
    else if(isset($_REQUEST['fs']))
    {
        //Final Submission
        header('Location: testack.php');
    }
?>
<?php
header("Cache-Control: no-cache, must-revalidate");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>Test Conducter</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
    <meta http-equiv="PRAGMA" content="NO-CACHE"/>
    <meta name="ROBOTS" content="NONE"/>
    <link rel="stylesheet" type="text/css" href="css/testhome.css"/>
    <script type="text/javascript" src="validate.js" ></script>
    <script type="text/javascript" src="cdtimer.js" ></script>
    
    <script language="javascript">
document.onmousedown=disableclick;
status="Right Click Disabled";
function disableclick(e)
{
  if(event.button==2)
   {
     alert(status);
     return false;  
   }
}

window.onkeydown = function(e){
  if(e.ctrlKey == true){
    return false;
  }
};
</script>
    
     <script type="text/javascript" >
	  function countDown() {
      sec--;
  if (sec == -01) {
    sec = 59;
    min = min - 1;
    if(min==-01)
        {
            min=59;
            hour=hour-1;
        }else
            {
                hour=hour;
            }
  } else {
   min = min;
  }
if (sec<=9) { sec = "0" + sec; }

  time = (hour<=9 ? "0" + hour : hour) + " : " + (min<=9 ? "0" + min : min) + " : " + sec + "";
if (document.getElementById) { document.getElementById('timer').innerHTML = time; 
<?php
 setcookie('TC',time(),time()+3600*24); 
?>
}
  SD=window.setTimeout("countDown();", 1000);
if (hour=='00'&& min == '00' && sec == '00') { sec = "00";min="00"; window.clearTimeout(SD); window.location=document.forms[0].action+"?fs=yes";}
}

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

addLoadEvent(function() {
  countDown();
});
	 </script>
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
  <body onunload="aaa()" onBeforeUnload="aaa()" >
      <noscript><h2>For the proper Functionality, You must use Javascript enabled Browser</h2></noscript>
       <?php

        if($_GLOBALS['message']) {
            echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
        }
        ?>
      <div id="container_1">
        <form id="testconducter" action="testconducter.php" method="post">
          <div class="menubar" style="text-align:center;">
              <h2 style="padding-top:15px;">Test Conducter</h2>
          </div>
      <div id="main_1">
          <?php
         
          if(isset($_SESSION['stdname']))
          {
                $result=executeQuery("select stdanswer,answered from studentquestion where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";");
                $r1=mysql_fetch_array($result);
                $result=executeQuery("select * from question where testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";");
                $r=mysql_fetch_array($result);
          ?>
          <div class="tc">

              <table border="0" width="100%">
                  <tr>
                      <th width="20%"><h3><span id="timer" class="timerclass"></span></h3></th>
                      <th width="60%"><h4 style="color: #af0a36; text-align:center;">Question No: <?php echo $_SESSION['qn']; ?> </h4></th>
                      <th width="20%"><h4 style="color: #af0a36;"><input type="checkbox" name="markreview" value="mark"> Mark for Review</input></h4></th>
                  </tr>
              </table>
            <!-- <textarea cols="100" rows="10" name="question" readonly style="width:99%;text-align:left;margin-top:2px;font-size:120%;font-weight:bold;margin-bottom:0;color:#0000ff;padding:2px 2px 2px 2px;"></textarea>-->
            
            <div class="question_text" style="border:#000 thick;">
            <?php echo htmlspecialchars_decode($r['question'],ENT_QUOTES); ?>
            
            </div>
            <table border="0" width="100%" style="text-align:left">
              <tr><td>&nbsp;</td></tr>
                  <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;1. <input type="radio" name="answer" value="optiona" <?php if((strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"review")==0 ||strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"answered")==0)&& strcmp(htmlspecialchars_decode($r1['stdanswer'],ENT_QUOTES),"optiona")==0 ){echo "checked";} ?>> <?php echo htmlspecialchars_decode($r['optiona'],ENT_QUOTES); ?></input></td></tr>
                  <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;2. <input type="radio" name="answer" value="optionb" <?php if((strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"review")==0 ||strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"answered")==0)&& strcmp(htmlspecialchars_decode($r1['stdanswer'],ENT_QUOTES),"optionb")==0 ){echo "checked";} ?>> <?php echo htmlspecialchars_decode($r['optionb'],ENT_QUOTES); ?></input></td></tr>
                  <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;3. <input type="radio" name="answer" value="optionc" <?php if((strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"review")==0 ||strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"answered")==0)&& strcmp(htmlspecialchars_decode($r1['stdanswer'],ENT_QUOTES),"optionc")==0 ){echo "checked";} ?>> <?php echo htmlspecialchars_decode($r['optionc'],ENT_QUOTES); ?></input></td></tr>
                  <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;4. <input type="radio" name="answer" value="optiond" <?php if((strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"review")==0 ||strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"answered")==0)&& strcmp(htmlspecialchars_decode($r1['stdanswer'],ENT_QUOTES),"optiond")==0 ){echo "checked";} ?>> <?php echo htmlspecialchars_decode($r['optiond'],ENT_QUOTES); ?></input></td></tr>
                  
                  <tr><td>&nbsp;</td></tr>
                  <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="clear" /></td></tr>
            </table><br /><br />
            
            <?php
			if(isset($_REQUEST['clear']))
			{
				$sql = executeQuery("UPDATE `oes`.`studentquestion` SET `answered` = \'unanswered\', `stdanswer` = \'NULL\' WHERE stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";");
			}
			?>
            
            <hr />
                 <table width="450" style="float:right;">
                  <tr>
                      <td width="150"><input type="submit" name="previous" value="Previous" class="btn"/></td>
                      <td width="150"><input type="submit" name="summary" value="Summary" class="btn" /></td>
                      <td width="150"><input type="submit" name="<?php if($final==true){ echo "viewsummary" ;}else{ echo "next";} ?>" value="<?php if($final==true){ echo "View Summary" ;}else{ echo "Next";} ?>" class="btn"/></td>
                  </tr>
                  
            </table>
			<br /><br />              

          </div>
          <?php
          closedb();
          }
          ?>
      </div>

           </form>
      <div id="footer1">
<center>
  <h3>Design By <a href="http://capslock.co.in/">CapsLOCK</a><br /></h3>
    <font color="#fffff" face="Comic Sans MS, cursive">copyright © 2013 All Right Reserved</font>  
  </center>
      </div>
      </div>
      
      <script type="text/javascript">
	  window.onbeforeunload = aaa;
	  function aaa()
	   {
		  alert("hi");
		  <?php
		  $sql = executQuery("UPDATE `oes`.`studenttest` SET `closetime` =".time()." WHERE stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']."");
		   ?>
  //Place an ajax request here  
}
      </script>
  </body>
</html>

