<?php

$dbserver="localhost";

$dbusername="root";

$dbpassword="";

$dbname="oes";

$conn=false;

function executeQuery($query)
{
    global $conn,$dbserver,$dbname,$dbpassword,$dbusername;
    global $message;
    if (!($conn = @mysql_connect ($dbserver,$dbusername,$dbpassword)))
         $message="Cannot connect to server";
    if (!@mysql_select_db ($dbname))
         $message="Cannot select database";

    $result=mysql_query($query);
    if(!$result)
        $message="Error while executing query.<br/>Mysql Error: ".mysql_error();
    else
        return $result;

}
function closedb()
{
    global $conn;
    if(!$conn)
    mysql_close($conn);
}
?>
