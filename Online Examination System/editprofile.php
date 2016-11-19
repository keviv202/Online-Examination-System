<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');


include_once 'database.php';
/************************** Step 1 *************************/
if(!isset($_SESSION['stdname'])) {
    $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
}
else if(isset($_REQUEST['logout']))
{
    unset($_SESSION['stdname']);
    header('Location: index.php');

}
else if(isset($_REQUEST['dashboard'])){
     
	 header('Location: stdwelcome.php');

    }else if(isset($_REQUEST['savem']))
{
	
	
/************************** Step 2 - Case 3 *************************/
                //updating the modified values
				
				
    if(empty($_REQUEST['cname'])||empty ($_REQUEST['password'])||empty ($_REQUEST['email']))
    {
         $_GLOBALS['message']="Some of the required Fields are Empty.Therefore Nothing is Updated";
    }
    else
    {
		 $conn = new Mongo('localhost');
        $db = $conn->oes;
       $collection = $db->student;
       $product_array = array(
                        'stdid'=>$_SESSION['stdid'],
                        );
        $document = $collection->findOne( $product_array );

        $document['stdname'] = $_REQUEST['cname'];
        $document['stdpassword'] = $_REQUEST['password'];
        $document['emailid'] = $_REQUEST['email'];
		$document['contactno'] =$_REQUEST['contactno'];
		$document['address'] =$_REQUEST['address'];
		$document['city'] =$_REQUEST['city'];
		$document['pincode'] =$_REQUEST['pin'];

       
		
     
     if(! $collection->save( $document ))
        $_GLOBALS['message']=mysql_error();
     else
        $_GLOBALS['message']="Your Profile is Successfully Updated.";
    }
    

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>Online Test</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="css/testhome.css"/>
        <link rel="stylesheet" type="text/css" href="css/form.css"/>

    <script type="text/javascript" src="validate.js" ></script>
    </head>
  <body >
       <?php

        if(isset($_GLOBALS['message'])) {
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
           <form id="editprofile" action="editprofile.php" method="post">
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
                       
 /************************** Step 3 - Case 1 *************************/
        // Default Mode - Displays the saved information.
	
	 $conn = new Mongo('localhost');
        $db = $conn->oes;
	 $collection = $db->student;
	 $pro = array(
                        'stdid'=>$_SESSION['stdid'],
                        );
        $doc = $collection->find( $pro );
		
                        if(!$doc) {
                           header('Location: stdwelcome.php');
                        }
                        else
                        {
							foreach($doc as $x)					
                           //editing components
                 ?>
        <center>
        <h2>Edit Profile</h2><hr /><br />
           <table>
              <tr>
                  <td>User Name</td>
                  <td><input type="text" name="cname" value="<?php echo $x['stdname']; ?>" size="16" onkeyup="isalphanum(this)"/></td>

              </tr>

                      <tr>
                  <td>Password</td>
                  <td><input type="password" name="password" value="<?php echo $x['stdpassword']; ?>" size="16" onkeyup="isalphanum(this)" /></td>
                 
              </tr>

              <tr>
                  <td>E-mail ID</td>
                  <td><input type="text" name="email" value="<?php echo $x['emailid']; ?>" size="16" /></td>
              </tr>
                       <tr>
                  <td>Contact No</td>
                  <td><input type="text" name="contactno" value="<?php echo $x['contactno']; ?>" size="16" onkeyup="isnum(this)"/></td>
              </tr>

                  <tr>
                  <td>Address</td>
                  <td><textarea name="address" cols="20" rows="3"><?php echo $x['address']; ?></textarea></td>
              </tr>
                       <tr>
                  <td>City</td>
                  <td><input type="text" name="city" value="<?php echo $x['city']; ?>" size="16" onkeyup="isalpha(this)"/></td>
              </tr>
                       <tr>
                  <td>PIN Code</td>
                  <td><input type="hidden" name="student" value="<?php echo $x['stdid']; ?>"/><input type="text" name="pin" value="<?php echo $x['pincode']; ?>" size="16" onkeyup="isnum(this)" /></td>
              </tr>

            </table><br />
            <input type="submit" value="Save" name="savem" class="btn" onclick="validateform('editprofile')" title="Save the changes"/></center>
<?php
                        
                        }
                        
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
