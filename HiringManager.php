<h1>Hiring Manager</h1>
<h4>Insert HM ID, Name, and Department into tab below:</h4>
<p>
    HM ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Department
</p>
<form method="POST" action="HiringManager.php">
    <!-- refreshes page when submitted -->

    <p><input type="text" name="hrid" size="12">
        <input type="text" name="hrname" size="24">
        <input type="text" name="department" size="24">
        <!-- Define two variables to pass values. -->
        <input type="submit" value="insert" name="inserthr"></p>
</form>


<!-- Create a form to pass the values.
     See below for how to get the values. -->

<h4> Create offer below: </h4>
<p>
    HR ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    HM ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Applicant ID&nbsp;
    Offer ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Job Details
</p>

<form method="POST" action="HiringManager.php">
    <!-- refreshes page when submitted -->

    <p><input type="text" name="ohrid" size="12">
        <input type="text" name="hmid" size="12">
        <input type="text" name="oaid" size="12">
        <input type="text" name="oid" size="12">
        <input type="text" name="jobdetails" size="60">
        <input type="submit" value="insert" name="insertoffer">
        <!-- Define two variables to pass values. -->

    </p>
</form>

<h4> Update offer below: </h4>
<p>
    HR ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Offer ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Job Details
</p>
<form method="POST" action="HiringManager.php">
    <p>
        <input type="text" name="HRid" size="12">
        <input type="text" name="oid" size="12">
        <input type="text" name="newjobdetails" size="60">
        <input type="submit" value="update" name="updatesubmit">
    </p>
</form>


<h4> Delete offer below</h4>
<p>
    Offer ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<form method="POST" action="HiringManager.php">
    <!-- refreshes page when submitted -->

    <p><input type="text" name="Oid" size="12">
        <!-- Define two variables to pass values. -->

        <input type="submit" value="delete" name="deleteOffer"></p>
</form>

<h4> Schedule interview time: </h4>
<p>
    HM ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Applicant ID&nbsp;&nbsp;&nbsp;&nbsp;
    Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Location
</p>
<form method="POST" action="HiringManager.php">
    <!-- refreshes page when submitted -->

    <p> <input type="text" name="HMid" size="12">
        <input type="text" name="Aid" size="12">
        <input type="text" name="time" size="12">
        <input type="text" name="location" size="60">
        <!-- Define two variables to pass values. -->

        <input type="submit" value="schedule" name="scheduleInterview"></p>
</form>


<html>
<style>
    table {
        width: 20%;
        border: 1px solid black;
    }

    th {
        font-family: Arial, Helvetica, sans-serif;
        font-size: .7em;
        background: #666;
        color: #FFF;
        padding: 2px 6px;
        border-collapse: separate;
        border: 1px solid #000;
    }

    td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: .7em;
        border: 1px solid #DDD;
        color: black;
    }
</style>
</html>


<?php

$success = True;
$db_conn = OCILogon("ora_darvey6", "a16444144",
    "dbhost.students.cs.ubc.ca:1522/stu");

function executePlainSQL($cmdstr)
{
    // Take a plain (no bound variables) SQL command and execute it.
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);
    // There is a set of comments at the end of the file that
    // describes some of the OCI specific functions and how they work.

    if (!$statement) {
        echo "<br>Cannot parse this command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        // For OCIParse errors, pass the connection handle.
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute this command: " . $cmdstr . "<br>";
        $e = oci_error($statement);
        // For OCIExecute errors, pass the statement handle.
        echo htmlentities($e['message']);
        $success = False;
    } else {

    }
    return $statement;

}

function executeBoundSQL($cmdstr, $list)
{
    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse this command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            OCIBindByName($statement, $bind, $val);
            unset ($val); // Make sure you do not remove this.
            // Otherwise, $val will remain in an
            // array object wrapper which will not
            // be recognized by Oracle as a proper
            // datatype.
        }
        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute this command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement);
            // For OCIExecute errors pass the statement handle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }

}

function printResult($result)
{ //prints results from a select statement
    echo "<br>Got data from table tab1:<br>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["NID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
    }
    echo "</table>";
}


function printTable($resultFromSQL, $namesOfColumnsArray)
{
    echo "<table>";
    echo "<tr>";
    // iterate through the array and print the string contents
    foreach ($namesOfColumnsArray as $name) {
        echo "<th>$name</th>";
    }
    echo "</tr>";

    while ($row = OCI_Fetch_Array($resultFromSQL, OCI_BOTH)) {
        echo "<tr>";
        $string = "";

        // iterates through the results returned from SQL query and
        // creates the contents of the table
        for ($i = 0; $i < sizeof($namesOfColumnsArray); $i++) {
            $string .= "<td>" . $row["$i"] . "</td>";
        }
        echo $string;
        echo "</tr>";
    }
    echo "</table>";
}


// Connect Oracle...
if ($db_conn) {
    if (array_key_exists('inserthr', $_POST)) {
        // Get values from the user and insert data into
        // the table.
        $tuple = array(
            ":bind1" => $_POST['hrid'],
            ":bind2" => $_POST['hrname'],
            ":bind3" => $_POST['department'],
        );
        $alltuples = array(
            $tuple
        );
        executeBoundSQL("insert into HM values (:bind1, :bind2, :bind3)", $alltuples);
        OCICommit($db_conn);

    } else
        if (array_key_exists('updatesubmit', $_POST)) {
            // Update tuple using data from user
            $tuple = array(
                ":bind1" => $_POST['HRid'],
                ":bind2" => $_POST['oid'],
                ":bind3" => $_POST['newjobdetails'],
            );
            $alltuples = array(
                $tuple
            );
            executeBoundSQL("update Offer set Jobdetails=:bind3, HRid=:bind1 where Oid=:bind2", $alltuples);

            OCICommit($db_conn);

        } else
            if (array_key_exists('deleteOffer', $_POST)) {
                // Update tuple using data from user
                $tuple = array(
                    ":bind1" => $_POST['Oid'],
                );
                $alltuples = array(
                    $tuple
                );
                executeBoundSQL("delete from Offer where Oid=:bind1", $alltuples);

                OCICommit($db_conn);
            } else
                if (array_key_exists('insertoffer', $_POST)) {
                    // Get values from the user and insert data into
                    // the table.
                    $tuple = array(
                        ":bind1" => $_POST['ohrid'],
                        ":bind2" => $_POST['hmid'],
                        ":bind3" => $_POST['oaid'],
                        ":bind4" => $_POST['oid'],
                        ":bind5" => $_POST['jobdetails'],
                    );
                    $alltuples = array(
                        $tuple
                    );
                    executeBoundSQL("insert into Offer values (:bind1, :bind2, :bind3, :bind4, :bind5)", $alltuples);
                    OCICommit($db_conn);

                } else
                    if (array_key_exists('scheduleInterview', $_POST)) {
                        // Get values from the user and insert data into
                        // the table.
                        $tuple = array(
                            ":bind1" => $_POST['HMid'],
                            ":bind2" => $_POST['Aid'],
                            ":bind3" => $_POST['time'],
                            ":bind4" => $_POST['location'],
                        );
                        $alltuples = array(
                            $tuple
                        );
                        executeBoundSQL("insert into Interview values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
                        OCICommit($db_conn);
                    }


    if ($_POST && $success) {
        //POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
        header("location: HiringManager.php");
    } else {
        // Select data...
        echo "<h5>Hiring Managers:<br>";
        $result = executePlainSQL("select * from HM");
        /*printResult($result);*/
        /* next two lines from Raghav replace previous line */
        $columnNames = array("HM ID#", "HM Name", "Department");
        printTable($result, $columnNames);

        echo "<h5>Offers:<br>";
        $result = executePlainSQL("select * from Offer");
        $columnNames = array("HR ID", "HM ID", "Applicant ID", "Offer ID", "Offer Details");
        printTable($result, $columnNames);

        echo "<h5>Interviews:<br>";
        $result = executePlainSQL("select * from Interview");
        $columnNames = array("HM ID", "Applicant ID", "Time", "Location");
        printTable($result, $columnNames);
    }


    //Commit to save changes...
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
?>
