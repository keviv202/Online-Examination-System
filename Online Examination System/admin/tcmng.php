<?php
error_reporting(0);
session_start();
include_once '../database.php';
/* * ************************ Step 1 ************************ */
if (!isset($_SESSION['admname'])) {
    $_GLOBALS['message'] = "Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
} else if (isset($_REQUEST['logout'])) {
    /*     * ************************ Step 2 - Case 1 ************************ */
    //Log out and redirect login page
    unset($_SESSION['admname']);
    header('Location: index.php');
} else if (isset($_REQUEST['dashboard'])) {
    /*     * ************************ Step 2 - Case 2 ************************ */
    //redirect to dashboard

    header('Location: admwelcome.php');
} else if (isset($_REQUEST['delete'])) {
    /*     * ************************ Step 2 - Case 3 ************************ */
    //deleting the selected test conductors
    unset($_REQUEST['delete']);
    $hasvar = false;
    foreach ($_REQUEST as $variable) {

        if (is_numeric($variable)) { //it is because, some session values are also passed with request
            $hasvar = true;

            if (!@executeQuery("delete from testconductor where tcid=$variable")){
               if (mysql_errno () == 1451) //Children are dependent value
                    $_GLOBALS['message'] = "Too Prevent accidental deletions, system will not allow propagated deletions.<br/><b>Help:</b> If you still want to delete this user, then first manually delete all the records that are associated with this user.";
                else
                    $_GLOBALS['message'] = mysql_errno();
            }
            // $_GLOBALS['message']=$_GLOBALS['message'].$variable;
        }
    }
    if (!isset($_GLOBALS['message']) && $hasvar == true)
        $_GLOBALS['message'] = "Selected Test Conductor/s are successfully Deleted";
    else if (!$hasvar) {
        $_GLOBALS['message'] = "First Select the Test Conductors to be Deleted.";
    }
} else if (isset($_REQUEST['savem'])) {
    /*     * ************************ Step 2 - Case 4 ************************ */
    //updating the modified values
    if (empty($_REQUEST['cname']) || empty($_REQUEST['password']) || empty($_REQUEST['email'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty.Therefore Nothing is Updated";
    } else {
        $query = "update testconductor set tcname='" . htmlspecialchars($_REQUEST['cname'],ENT_QUOTES) . "', tcpassword=ENCODE('" . htmlspecialchars($_REQUEST['password'],ENT_QUOTES) . "','oespass'),emailid='" . htmlspecialchars($_REQUEST['email'],ENT_QUOTES) . "',contactno='" . htmlspecialchars($_REQUEST['contactno'],ENT_QUOTES) . "',address='" .htmlspecialchars($_REQUEST['address'],ENT_QUOTES) . "',city='" . htmlspecialchars($_REQUEST['city'],ENT_QUOTES) . "',pincode='" . htmlspecialchars($_REQUEST['pin'],ENT_QUOTES) . "' where tcid='" . $_REQUEST['tc'] . "';";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "Test Conductor Information is Successfully Updated.";
    }
    closedb();
}
else if (isset($_REQUEST['savea'])) {
    /*     * ************************ Step 2 - Case 5 ************************ */
    //Add the new test conductor information in the database
    $result = executeQuery("select max(tcid) as tc from testconductor");
    $r = mysql_fetch_array($result);
    if (is_null($r['tc']))
        $newstd = 1;
    else
        $newstd=$r['tc'] + 1;

    $result = executeQuery("select tcname as tc from testconductor where tcname='" . htmlspecialchars($_REQUEST['cname'],ENT_QUOTES) . "';");


    if (empty($_REQUEST['cname']) || empty($_REQUEST['password']) || empty($_REQUEST['email'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty";
    } else if (mysql_num_rows($result) > 0) {
        $_GLOBALS['message'] = "Sorry User Already Exists.";
    } else {
        $query = "insert into testconductor values($newstd,'" . htmlspecialchars($_REQUEST['cname'],ENT_QUOTES) . "',ENCODE('" . htmlspecialchars($_REQUEST['password'],ENT_QUOTES) . "','oespass'),'" . htmlspecialchars($_REQUEST['email'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['contactno'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['address'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['city'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['pin'],ENT_QUOTES) . "')";
        if (!@executeQuery($query)) {
            if(mysql_errno ()==1062) //duplicate value
            $_GLOBALS['message'] = "Given Test Conductor Name voilates some constraints, please try with some other name.";
            else
            $_GLOBALS['message'] = mysql_error();
        }
        else
            $_GLOBALS['message'] = "Successfully New Test Conductor is Created.";
    }
    closedb();
}
?>
<html>
    <head>
        <title>Manage Test Conductors</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="../css/testhome.css"/>
        <script type="text/javascript" src="../validate.js" ></script>
    </head>
    <body>
<?php
if ($_GLOBALS['message']) {
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
            <form name="tcmng" action="tcmng.php" method="post">
                <div class="menubar">


                    <ul>
        <?php
        if (isset($_SESSION['admname'])) {
// Navigations
        ?>
                            <li><input type="submit" value="LogOut" name="logout" class="btn1" title="Log Out"/>&nbsp;&nbsp;</li>
                            <li><input type="submit" value="Home" name="dashboard" class="btn1" title="Dash Board"/>&nbsp;&nbsp;</li>

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
                    echo "<div class=\"pmsg\" style=\"text-align:center;\">Test Conductors Management </div>";
                    if (isset($_REQUEST['add'])) {
                        /*                         * ************************ Step 3 - Case 1 ************************ */
                        //Form for the new Test Conductor
?>
					<hr><br/>
                    <center><table width="38%">
                        <tr>
                            <td>TC Name</td>
                            <td><input type="text" name="cname" value="" size="16" onKeyUp="isalphanum(this)"/></td>

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

                    </table>
                    <br/>
                    <?php
            //navigation for Add option
            if (isset($_REQUEST['add'])) {
?>
                                <input type="submit" value="Cancel" name="cancel" class="btn" title="Cancel"/>&nbsp;&nbsp;
                                <input type="submit" value="Save" name="savea" class="btn" onClick="validateform('usermng')" title="Save the Changes"/>&nbsp;&nbsp;

                        <?php
                    }?></center><br/>

<?php
                    } else if (isset($_REQUEST['edit'])) {
                        /*                         * ************************ Step 3 - Case 2 ************************ */
                        // To allow Editing Existing Test Conductor Information
                        $result = executeQuery("select tcid,tcname,DECODE(tcpassword,'oespass') as tcpass ,emailid,contactno,address,city,pincode from testconductor where tcname='" . htmlspecialchars($_REQUEST['edit'],ENT_QUOTES) . "';");
                        if (mysql_num_rows($result) == 0) {
                            header('Location: tcmng.php');
                        } else if ($r = mysql_fetch_array($result)) {

                            //editing components
?>
                            <table  >
                                <tr>
                                    <td>TC Name</td>
                                    <td><input type="text" name="cname" value="<?php echo htmlspecialchars_decode($r['tcname'],ENT_QUOTES); ?>" size="16" onKeyUp="isalphanum(this)"/></td>

                                </tr>

                                <tr>
                                    <td>Password</td>
                                    <td><input type="text" name="password" value="<?php echo htmlspecialchars_decode($r['tcpass'],ENT_QUOTES); ?>" size="16" onKeyUp="isalphanum(this)" /></td>

                                </tr>

                                <tr>
                                    <td>E-mail ID</td>
                                    <td><input type="text" name="email" value="<?php echo htmlspecialchars_decode($r['emailid'],ENT_QUOTES); ?>" size="16" /></td>
                                </tr>
                                <tr>
                                    <td>Contact No</td>
                                    <td><input type="text" name="contactno" value="<?php echo htmlspecialchars_decode($r['contactno'],ENT_QUOTES); ?>" size="16" onKeyUp="isnum(this)"/></td>
                                </tr>

                                <tr>
                                    <td>Address</td>
                                    <td><textarea name="address" cols="20" rows="3"><?php echo htmlspecialchars_decode($r['address'],ENT_QUOTES); ?></textarea></td>
                                </tr>
                                <tr>
                                    <td>City</td>
                                    <td><input type="text" name="city" value="<?php echo htmlspecialchars_decode($r['city'],ENT_QUOTES); ?>" size="16" onKeyUp="isalpha(this)"/></td>
                                </tr>
                                <tr>
                                    <td>PIN Code</td>
                                    <td><input type="hidden" name="tc" value="<?php echo htmlspecialchars_decode($r['tcid'],ENT_QUOTES); ?>"/><input type="text" name="pin" value="<?php echo htmlspecialchars_decode($r['pincode'],ENT_QUOTES); ?>" size="16" onKeyUp="isnum(this)" /></td>
                                </tr>

                            </table>
<?php
                            closedb();
                        }
                    } else {
                        /*                         * ************************ Step 3 - Case 3 ************************ */
                        // Defualt Mode: Displays the Existing Test Conductors, If any.
                        $result = executeQuery("select * from testconductor order by tcid;");
                        if (mysql_num_rows($result) == 0) {
                            echo "<h3 style=\"color:#0000cc;text-align:center;\">No Test Conductors Yet..!</h3>";
                        } else {
                            $i = 0;
?>
                            <table width="940" border="1">
                                <tr bgcolor="#99EEFD">
                                    <th>&nbsp;</th>
                                    <th>TC Name</th>
                                    <th>Email-ID</th>
                                    <th>Contact Number</th>
                                    <th>Edit</th>
                                </tr>
                    <?php
                            while ($r = mysql_fetch_array($result)) {
                                $i = $i + 1;
                                if ($i % 2 == 0)
                                    echo "<tr bgcolor='#CDF7FE'>";
                                else
                                    echo "<tr>";
                                echo "<td style=\"text-align:center;\"><input type=\"checkbox\" name=\"d$i\" value=\"" . $r['tcid'] . "\" /></td><td>" . htmlspecialchars_decode($r['tcname'],ENT_QUOTES)
                                . "</td><td>" . htmlspecialchars_decode($r['emailid'],ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['contactno'],ENT_QUOTES) . "</td>"
                                . "<td class=\"tddata\"><a title=\"Edit " . htmlspecialchars_decode($r['tcname'],ENT_QUOTES) . "\"href=\"tcmng.php?edit=" . htmlspecialchars_decode($r['tcname'],ENT_QUOTES) . "\"><img src=\"../images/edit.png\" height=\"30\" width=\"40\" alt=\"Edit\" /></a></td></tr>";
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

