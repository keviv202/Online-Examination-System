<?php
error_reporting(0);
session_start();
include_once 'database.php';

if(isset($_REQUEST['stdsubmit']))
{
	
	$conn = new Mongo();
    $db = $conn->oes;
    $collection = $db->student;
	   
    $val = $collection->find(array(), array('stdid' => 1))->sort(array('stdid' => -1))->limit(1);

    foreach($val as $x)
	
     if(is_null($x['stdid']))
     $newstd=1;
     else
     $newstd=$x['stdid']+1;
     

    // $_GLOBALS['message']=$newstd;
    if(empty($_REQUEST['cname'])||empty ($_REQUEST['password'])||empty ($_REQUEST['email']))
    {
         $_GLOBALS['message']="Some of the required Fields are Empty";
    }else if(mysql_num_rows($result)>0)
    {
        $_GLOBALS['message']="Sorry the User Name is Not Available Try with Some Other name.";
    }
    else
    {
     $conn = new Mongo('localhost');
        $db = $conn->oes;
       $collection = $db->student;
        $cursor=array(
					'stdid' =>$newstd,
                    'stdname' => $_REQUEST['cname'],
                    'stdpassword' => $_REQUEST['password'],
                    'emailid' => $_REQUEST['email'],
				    'contactno' => $_REQUEST['contactno'],
				    'address'=>$_REQUEST['address'],
					'city'=>$_REQUEST['city'],
					'pincode'=>$_REQUEST['pin']
                    );
     
     if(! $collection->insert( $cursor ))
                $_GLOBALS['message']=mysql_error();
     else
     {
        $success=true;
        $_GLOBALS['message']="Successfully Your Account is Created.Click <a href=\"index.php\">Here</a> to LogIn";
       // header('Location: index.php');
     }
    }
    closedb();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>Registration</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="css/testhome.css"/>
    <link rel="stylesheet" type="text/css" href="css/form.css"/>
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
    
    <div class="menubar">
              <?php if(!$success): ?>

              <h2 style="text-align:center;color:#ffffff; padding-top:5px;">New User Registration			</h2>
              <?php endif; ?>
             
          </div>
      <div id="secondmain">
          <?php
          if($success)
          {
                echo "<h2 style=\"text-align:center;color:#0000ff;\">Thank You For Registering with Online Examination System.<br/><a href=\"index.php\">Login Now</a></h2>";
          }
          else
          {
           /***************************** Step 2 ****************************/
          ?>
          <form id="admloginform"  action="register.php" method="post" onsubmit="return validateform('admloginform');">
                  <center> <table>
              <tr>
                  <td>User Name</td>
                  <td><input type="text" name="cname" value="" size="16" onkeyup="isalphanum(this)"/></td>

              </tr>

                      <tr>
                  <td>Password</td>
                  <td><input type="password" name="password" value="" size="16" onkeyup="isalphanum(this)" /></td>

              </tr>
                      <tr>
                  <td>Re-type Password</td>
                  <td><input type="password" name="repass" value="" size="16" onkeyup="isalphanum(this)" /></td>

              </tr>
              <tr>
                  <td>E-mail ID</td>
                  <td><input type="text" name="email" value="" size="16" /></td>
              </tr>
                       <tr>
                  <td>Contact No</td>
                  <td><input type="text" name="contactno" value="" size="16" onkeyup="isnum(this)"/></td>
              </tr>

                  <tr>
                  <td>Address</td>
                  <td><textarea name="address" cols="20" rows="3"></textarea></td>
              </tr>
                       <tr>
                  <td>City</td>
                  <td><input type="text" name="city" value="" size="16" onkeyup="isalpha(this)"/></td>
              </tr>
                       <tr>
                  <td>PIN Code</td>
                  <td><input type="text" name="pin" value="" size="16" onkeyup="isnum(this)" /></td>
              </tr>
                       
                           
              
            </table></center>
            <br/>
            <input type="submit" name="stdsubmit" value="Register" class="btn" />
                  <input type="reset" name="reset" value="Reset" class="btn"/>
        </form>
        
       <?php } ?>
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

