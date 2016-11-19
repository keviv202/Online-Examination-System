<?php

session_start();
include_once '../database.php';
/* * ************************ Step 1 ************************ */
if (!isset($_SESSION['admname'])) 
{
    $_GLOBALS['message'] = "Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
} 
else if (isset($_REQUEST['logout']))
 {
    /*     * ************************ Step 2 - Case 1 ************************ */
    //Log out and redirect login page
    unset($_SESSION['admname']);
    header('Location: index.php');
 } 
 else if (isset($_REQUEST['dashboard']))
 {
    header('Location: admwelcome.php');
 } 
 else if (isset($_REQUEST['tcmng'])) 
 {
    header('Location: tcmng.php');
 } 
 else if (isset($_REQUEST['delete'])) 
 {
    /*unset($_REQUEST['delete']);*/
    $hasvar = false;
    foreach ($_REQUEST as $variable) 
	{
		
        if (is_numeric($variable)) 
		{ 
        $hasvar = true;
        $conn = new Mongo();
        $db = $conn->oes;
        $collection = $db->student;
		$pro = array(
                        'stdid' => $variable,
                        );
           if (!$collection->remove($pro)) 		   			
			{
                if (mysql_errno () == 1451) //Children are dependent value
                    $_GLOBALS['message'] = "Too Prevent accidental deletions, system will not allow propagated deletions.<br/><b>Help:</b> If you still want to delete this user, then first manually delete all the records that are associated with this user.";
                else
                    $_GLOBALS['message'] = mysql_errno();
            }
        }
    }
    if (!isset($_GLOBALS['message']) && $hasvar == true)
        $_GLOBALS['message'] = "Selected User/s are successfully Deleted";
    else if (!$hasvar) {
        $_GLOBALS['message'] = "First Select the users to be Deleted.";
    }
} else if (isset($_REQUEST['savem'])) 
{
    /*     * ************************ Step 2 - Case 4 ************************ */
    //updating the modified values
    if (empty($_REQUEST['cname']) || empty($_REQUEST['password']) || empty($_REQUEST['email'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty.Therefore Nothing is Updated";
    } else {
		$conn = new Mongo('localhost');
        $db = $conn->oes;
       $collection = $db->student;
       $product_array = array(
                        'stdid'=>$_REQUEST['student'],
                        );
        $document = $collection->findOne( $product_array );

        $document['stdname'] = $_REQUEST['cname'];
        $document['stdpassword'] = $_REQUEST['password'];
        $document['emailid'] = $_REQUEST['email'];
		$document['contactno'] =$_REQUEST['contactno'];
		$document['address'] =$_REQUEST['address'];
		$document['city'] =$_REQUEST['city'];
		$document['pincode'] =$_REQUEST['pin'];

        
        if (!$collection->save( $document ))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "User Information is Successfully Updated.";
    }
    closedb();
}
else if (isset($_REQUEST['savea'])) {
    /*     * ************************ Step 2 - Case 5 ************************ */
    //Add the new user information in the database
    $conn = new Mongo();
    $db = $conn->oes;
    $collection = $db->student;
	   
    $val = $collection->find(array(), array('stdid' => 1))->sort(array('stdid' => -1))->limit(1);

    foreach($val as $x)
	
     if(is_null($x['stdid']))
     $newstd=1;
     else
     $newstd=$x['stdid']+1;

    
    if (empty($_REQUEST['cname']) || empty($_REQUEST['password']) || empty($_REQUEST['email'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty";
    } else {
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
     
     if(! $collection->insert( $cursor )){
            if (mysql_errno () == 1062) //duplicate value
                $_GLOBALS['message'] = "Given User Name voilates some constraints, please try with some other name.";
            else
                $_GLOBALS['message'] = mysql_error();
        }
        else
            $_GLOBALS['message'] = "Successfully New User is Created.";
    }
    closedb();
}
?>
<html>
    <head>
        <title>Manage Users</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="../css/testhome.css"/>
         <link rel="stylesheet" type="text/css" href="../css/form.css"/>
        <script type="text/javascript" src="../validate.js" ></script>
    </head>
    <body>
<?php
if (isset($_GLOBALS['message'])) {
    echo "<div class=\"message\">" . $_GLOBALS['message'] . "</div>";
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
            <form name="usermng" action="usermng.php" method="post">
                <div class="menubar">


                    <ul>
<?php
if (isset($_SESSION['admname'])) {
// Navigations
?>
                        <li><input type="submit" value="LogOut" name="logout" class="btn1" title="Log Out"/>&nbsp;&nbsp;</li>
                        <li><input type="submit" value="Home" name="dashboard" class="btn1" title="Dash Board"/>&nbsp;&nbsp;</li>
                        <li><input type="submit" value="Test Conductors" name="tcmng" class="btn1" title="Test Conductors Management"/>&nbsp;&nbsp;</li>

<?php
    //navigation for Add option
    if (isset($_REQUEST['add'])) {
?>
                        <li><input type="submit" value="Cancel" name="cancel" class="btn1" title="Cancel"/>&nbsp;&nbsp;</li>
                        <li><input type="submit" value="Save" name="savea" class="btn1" onClick="validateform('usermng')" title="Save the Changes"/>&nbsp;&nbsp;</li>

<?php
    } else if (isset($_REQUEST['edit'])) { //navigation for Edit option
?>
                        <li><input type="submit" value="Cancel" name="cancel" class="btn1" title="Cancel"/>&nbsp;&nbsp;</li>
                        <li><input type="submit" value="Save" name="savem" class="btn1" onClick="validateform('usermng')" title="Save the changes"/>&nbsp;&nbsp;</li>

<?php
    } else {  //navigation for Default
?>
                        <li><input type="submit" value="Delete" name="delete" class="btn1" title="Delete"/>&nbsp;&nbsp;</li>
                        <li><input type="submit" value="Add" name="add" class="btn1" title="Add"/>&nbsp;&nbsp;</li>
<?php }
} ?>
                    </ul>

                </div>
                <div id="secondmain">
<?php
if (isset($_SESSION['admname'])) {
    echo "<div class='title' style='text-align:center;'><h2>Students Management</h2> </div>
	<hr><br>
	";
    if (isset($_REQUEST['add'])) {
        /*         * ************************ Step 3 - Case 1 ************************ */
        //Form for the new user
?>
<center>
<table width="38%">
                        <tr>
                            <td width="38%" align="right"> User Name</td>
                          <td width="62%"><input type="text" name="cname" value="" size="16" onKeyUp="isalphanum(this)"/></td>

                        </tr>

                        <tr>
                            <td>Password</td>
                            <td><input type="password" name="password" value="" size="16" onKeyUp="isalphanum(this)" /></td>

                        </tr>
                        <tr>
                            <td>Re-type Password</td>
                            <td><input type="password" name="repass" value="" size="16" onKeyUp="isalphanum(this)" /></td>

                        </tr>
                        <tr>
                            <td>E-mail ID</td>
                            <td><input type="text" name="email" value="" size="16" /></td>
                        </tr>
                        <tr>
                            <td>Contact No</td>
                            <td><input type="text" name="contactno" value="" size="16" onKeyUp="isnum(this)"/></td>
                        </tr>

                        <tr>
                            <td>Address</td>
                            <td><textarea name="address" cols="20" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td>City</td>
                            <td><input type="text" name="city" value="" size="16" onKeyUp="isalpha(this)"/></td>
                        </tr>
                        <tr>
                            <td>PIN Code</td>
                            <td><input type="text" name="pin" value="" size="16" onKeyUp="isnum(this)" /></td>
                        </tr>
        </table><br>
                        <?php
    //navigation for Add option
    if (isset($_REQUEST['add'])) {
?>
  <input type="submit" value="Cancel" name="cancel" class="btn" title="Cancel"/>&nbsp;&nbsp;
                        <input type="submit" value="Save" name="savea" class="btn" onClick="validateform('usermng')" title="Save the Changes"/>
                        
<?php
	}?>
</center>
<?php
    } else if (isset($_REQUEST['edit'])) {
        /*         * ************************ Step 3 - Case 2 ************************ */
        // To allow Editing Existing User Information
        $conn = new Mongo('localhost');
        $db = $conn->oes;
	 $collection = $db->student;
    $product_array = array(
                        'stdname'=>$_REQUEST['edit'],
                        );
        $document = $collection->find( $product_array );

  /*  $num_docs = $document->count();*/
	
                        if(!$document) {
            header('Location: usermng.php');
        } 
		else 
		 {
			foreach($document as $x)
            //editing components
?>
                   
<center><table width="38%">
                        <tr>
                            <td width="126">User Name</td>
                            <td width="213"><input type="text" name="cname" value="<?php echo $x['stdname']; ?>" size="16" onKeyUp="isalphanum(this)"/></td>

                        </tr>

                        <tr>
                            <td>Password</td>
                            <td><input type="text" name="password" value="<?php echo $x['stdpass']; ?>" size="16" onKeyUp="isalphanum(this)" /></td>

                        </tr>

                        <tr>
                            <td>E-mail ID</td>
                            <td><input type="text" name="email" value="<?php echo $x['emailid']; ?>" size="16" /></td>
                        </tr>
                        <tr>
                            <td>Contact No</td>
                            <td><input type="text" name="contactno" value="<?php echo $x['contactno']; ?>" size="16" onKeyUp="isnum(this)"/></td>
                        </tr>

                        <tr>
                            <td>Address</td>
                            <td><textarea name="address" cols="20" rows="3"><?php echo $x['address']; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>City</td>
                            <td><input type="text" name="city" value="<?php echo $x['city']; ?>" size="16" onKeyUp="isalpha(this)"/></td>
                        </tr>
                        <tr>
                            <td>PIN Code</td>
                            <td><input type="hidden" name="student" value="<?php echo $x['stdid']; ?>"/><input type="text" name="pin" value="<?php echo $x['pincode']; ?>" size="16" onKeyUp="isnum(this)" /></td>
                        </tr>

                    </table><br></center>
         <?php
		 if (isset($_REQUEST['edit'])) { ?>
         <center><input type="submit" value="Cancel" name="cancel" class="btn" title="Cancel"/>&nbsp;&nbsp;
                        <input type="submit" value="Save" name="savem" class="btn" onClick="validateform('usermng')" title="Save the changes"/>
         </center>
         <?php } ?>
<?php
                    closedb();
                }
            } else {
                /*                 * ************************ Step 3 - Case 3 ************************ */
                // Defualt Mode: Displays the Existing Users, If any.
		$conn = new Mongo();
        $db = $conn->oes;
       	$collection = $db->student;
		$val = $collection->find(); 
		               
		if (!$val) {
                    echo "<h3 style=\"color:#0000cc;text-align:center;\">No Users Yet..!</h3>";
                } else {
                    $i = 0;
?>
                    <table width="930" border="1">
                        <tr bgcolor="#33CCFF">
                            <th>&nbsp;</th>
                            <th>User Name</th>
                            <th>Email-ID</th>
                            <th>Contact Number</th>
                            <th>Edit</th>
                        </tr>
<?php
                    foreach ($val as $m) {
                        $i = $i + 1;
                        if ($i % 2 == 0)
                            echo "<tr bgcolor='#C4F8FD'>";
                        else
                            echo "<tr>";
                        echo "<td style=\"text-align:center;\"><input type=\"checkbox\" name=\"d$i\" value=\"" . $m['stdid'] . "\" /></td><td>" .$m['stdname']
                        . "</td><td>" . $m['emailid'] . "</td><td>" . $m['contactno'] . "</td>"
                        . "<td class=\"tddata\"><a title=\"Edit " . $m['stdname']. "\"href=\"usermng.php?edit=" .$m['stdname'] . "\"><img src=\"../images/edit.png\" height=\"30\" width=\"40\" alt=\"Edit\" /></a></td></tr>";
                     }
?>
                    </table>
<?php
                }
                closedb();
            }
        }
?>

              </div>
            </form>
           <div id="footer">
  
  <center>
  <h3>Design By CapsLOCK<br /></h3>
    <font color="#fffff" face="Comic Sans MS, cursive">copyright © 2013 All Right Reserved</font>  
  </center>
</div>

        </div>
    </body>
</html>

