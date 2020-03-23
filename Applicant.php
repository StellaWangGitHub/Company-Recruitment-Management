<h1>Applicant</h1>

<h4>Register as a new applicant or update your info:</h4>
<p>
    Aid&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Phone number &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Address</p>
<form method="POST" action="Applicant.php">
    <!-- refreshes page when submitted -->

    <p><input type="text" name="aid" size="6">
        <input type="text" name="aname" size="18">
        <input type="text" name="anumber" size="18">
        <input type="text" name="aaddress" size="18">
        <!-- Define two variables to pass values. -->
        <input type="submit" value="insert" name="insertapplicant">
        <input type="submit" value="update" name="updateapplicant"></p>

</form>

<!-- Create a form to pass the values.
     See below for how to get the values. -->

<h4> Apply to a job below: </h4>
<p>
    Aid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Jid</p>
<form method="POST" action="Applicant.php">
    <!-- refreshes page when submitted -->

    <p><input type="text" name="applyaid" size="8">
        <input type="text" name="applyjid" size="8">
        <!-- Define two variables to pass values. -->

        <input type="submit" value="apply" name="apply"></p>
    <input type="submit" value="run hardcoded queries" name="dostuff"></p>
</form>

<h4> Delete your account: </h4>
<p>
    Aid&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<form method="POST" action="Applicant.php">
    <!-- refreshes page when submitted -->

    <p><input type="text" name="deleteaid" size="18">
        <!-- Define two variables to pass values. -->

        <input type="submit" value="delete" name="deletesubmit"></p>
    </p>
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

/* This tells the system that it's no longer just parsing
   HTML; it's now parsing PHP. */

// keep track of errors so it redirects the page only if
// there are no errors
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
    /* Sometimes the same statement will be executed several times.
        Only the value of variables need to be changed.
       In this case, you don't need to create the statement several
        times.  Using bind variables can make the statement be shared
        and just parsed once.
        This is also very useful in protecting against SQL injection
        attacks.  See the sample code below for how this function is
        used. */

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

    if (array_key_exists('insertapplicant', $_POST)) {
        // Get values from the user and insert data into
        // the table.
        $tuple = array(
            ":bind1" => $_POST['aid'],
            ":bind2" => $_POST['aname'],
            ":bind3" => $_POST['anumber'],
            ":bind4" => $_POST['aaddress'],
        );
        $alltuples = array(
            $tuple
        );
        executeBoundSQL("insert into Applicant values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
        OCICommit($db_conn);

    } else
        if (array_key_exists('updateapplicant', $_POST)) {
            // Update tuple using data from user
            $tuple = array(
                ":bind1" => $_POST['aid'],
                ":bind2" => $_POST['aname'],
                ":bind3" => $_POST['anumber'],
                ":bind4" => $_POST['aaddress'],
            );
            $alltuples = array(
                $tuple
            );
            executeBoundSQL("update Applicant set Name=:bind2, Phone=:bind3m, Address=:bind4
                                                            where Aid=:bind1", $alltuples);
            OCICommit($db_conn);


        } else
            if (array_key_exists('apply', $_POST)) {
                // Update tuple using data from user
                $tuple = array(
                    ":bind1" => $_POST['applyaid'],
                    ":bind2" => $_POST['applyjid'],
                );
                $alltuples = array(
                    $tuple
                );
                executeBoundSQL("insert into Apply values (:bind1, :bind2)", $alltuples);
                OCICommit($db_conn);

            } else
                if (array_key_exists('deleteapplicant', $_POST)) {
                    // Update tuple using data from user
                    $tuple = array(
                        ":bind1" => $_POST['deleteaid'],
                    );
                    $alltuples = array(
                        $tuple
                    );
                    executeBoundSQL("delete from Applicant where Aid=:bind1", $alltuples);

                    OCICommit($db_conn);
                }

    if ($_POST && $success) {
        //POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
        header("location: Applicant.php");
    } else {
        // Select data...
        echo "<br>Applicants:<br>";
        $result = executePlainSQL("select * from Applicant");
        $columnNames = array("Applicant ID", "Name", "Phone Number", "Address");
        printTable($result, $columnNames);

        echo "<h5>Application:<br>";
        $result = executePlainSQL("select * from Apply");
        $columnNames = array("Applicant ID", "Job ID");
        printTable($result, $columnNames);

        echo "<h5>Screen tests:<br>";
        $result = executePlainSQL("select * from Screentest");
        $columnNames = array("Screen test ID", "Applicant ID");
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
