<?php
    error_reporting(0);
    ini_set('display_errors', 0);
    include "db.php";
?>

<?php
    if (isset($_POST['add'])){
        $attRN = $_POST['attRN'];
        $empId = $_POST['empId'];
        $attDate = $_POST['attDate'];
        $attTimeIn = $_POST['attTimeIn'];
        $attTimeOut = $_POST['attTimeOut'];
        $attStat = "Added";

        $conn->query("INSERT INTO attendance (attRN, empId, attDate, attTimeIn, attTimeOut, attStat)
        VALUES ('$attRN', '$empId', '$attDate', '$attTimeIn', '$attTimeOut', '$attStat')")
        or die($conn->error);

        header("Location:recording.php");
        exit();
    }

    if (isset($_GET['delete'])){
        $attRN = $_GET['delete'];
        $conn->query("DELETE FROM attendance WHERE attRN=$attRN");

    header("Location: recording.php");
    exit();
    }

// ===== FETCH =====
$result = $conn->query("SELECT * FROM attendance");
?>

<a href="index.php">Back</a>

<h2>Record Attendance</h2>

<form method="POST">
    Attendance RN: <input type="text" name="attRN" required><br>

    Employee: 
    <select name="empId" required>
        <option value="">--</option>
        <?php
        $emps = $conn->query("SELECT empId, empFName, empLName FROM employees ORDER BY empLName");
        while($emp = $emps->fetch_assoc()){
            echo "<option value='{$emp['empId']}'>{$emp['empFName']} {$emp['empLName']}</option>";
        }
        ?>
    </select><br>

    Date: <input type="date" name="attDate" required><br>
    Time In: <input type="time" name="attTimeIn" required><br>
    Time Out: <input type="time" name="attTimeOut" required><br>

    <button name="add">Add Attendance</button>
</form>

<table border="1">
<tr>
    <th>Record #</th>
    <th>Emp. ID</th>
    <th>Date</th>
    <th>Time In</th>
    <th>Time Out</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['attRN'] ?></td>
    <td><?= $row['empId'] ?></td>
    <td><?= $row['attDate'] ?></td>
    <td><?= date("g:i A", strtotime($row['attTimeIn'])) ?></td>
    <td><?= date("g:i A", strtotime($row['attTimeOut'])) ?></td>

    <td>
        <a href="recording.php?delete=<?= $row['attRN'] ?>">Cancel</a>
    </td>
</tr>
<?php endwhile; ?>
</table>