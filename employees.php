<?php
    error_reporting(0);
    ini_set('display_errors', 0);
    include "db.php";
?>

<?php
    if (isset($_POST['add'])){
        $empId = $_POST['empId'];
        $depCode = $_POST['depCode'];
        $empFName = $_POST['empFName'];
        $empLName = $_POST['empLName'];
        $empRPH = $_POST['empRPH'];

        $conn->query("INSERT INTO employees (empId, depCode, empFName, empLName, empRPH)
        VALUES ('$empId', '$depCode', '$empFName', '$empLName', $empRPH)")
        or die($conn->error);

        header("Location:employees.php");
        exit();
    }

    if (isset($_GET['delete'])){
        $empId = $_GET['delete'];
        $conn->query("DELETE FROM employees WHERE empId=$empId");

    header("Location: employees.php");
    exit();
    }

    $editData = null;
    if (isset($_GET['edit'])){
        $empId = $_GET['edit'];
        $res = $conn->query("SELECT * FROM employees WHERE empId=$empId");
        $editData = $res->fetch_assoc();
    }

    if (isset($_POST['update'])){
    $empId = $_POST['id']; //original id

    $newempId = $_POST['empId'];
    $depCode = $_POST['depCode'];
    $empFName = $_POST['empFName'];
    $empLName = $_POST['empLName'];
    $empRPH = $_POST['empRPH'];

    $check = $conn->query("SELECT * FROM employees WHERE empId='$newempId' AND empId != '$empId'");

    if ($check->num_rows > 0) {
        echo "<p style='color:red;'>ID already exists. Please use another employee Id.</p>";
    } else {
        $conn->query("UPDATE employees SET 
            empId='$newempId',
            depCode='$depCode',
            empFName='$empFName',
            empLName='$empLName',
            empRPH='$empRPH'
            WHERE empId='$empId'");

        header("Location: employees.php");
        exit();
    }
}
// ===== FETCH =====
$result = $conn->query("SELECT * FROM employees");
?>

<a href="index.php">Back</a>

<h2>Manage Employees</h2>

<form method="POST">
<input type="hidden" name="id" value="<?= $editData['empId'] ?? '' ?>">

Employee Id: <input type="text" name="empId" value="<?= $editData['empId'] ?? '' ?>" required><br>
Department Code: <select name="depCode" required>
<?php
$deps = $conn->query("SELECT depCode, depName FROM departments");
while ($d = $deps->fetch_assoc()) {
    $selected = ($editData && $editData['depCode'] == $d['depCode']) ? "selected" : "";
    echo "<option value='{$d['depCode']}' $selected>{$d['depName']} ({$d['depCode']})</option>";
}
?>
</select>
<br>
Employee First Name: <input type="text" name="empFName" value="<?= $editData['empFName'] ?? '' ?>" required><br>
Employee Last Name: <input type="text" name="empLName" value="<?= $editData['empLName'] ?? '' ?>" required><br>
Employee Rate per Hour: <input type="text" name="empRPH" value="<?= $editData['empRPH'] ?? '' ?>" required><br>

<?php if ($editData): ?>
    <button name="update">Update</button>
<?php else: ?>
    <button name="add">Add</button>
<?php endif; ?>
</form>

<table border="1">
<tr>
    <th>ID</th>
    <th>Dept</th>
    <th>Last Name</th>
    <th>First Name</th>
    <th>Rate/Hour</th>
    <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['empId'] ?></td>
    <td><?= $row['depCode'] ?></td>
    <td><?= $row['empLName'] ?></td>
    <td><?= $row['empFName'] ?></td>
    <td><?= $row['empRPH'] ?></td>
    
    <td>
        <a href="employees.php?edit=<?= $row['empId'] ?>">Edit</a> |
        <a href="employees.php?delete=<?= $row['empId'] ?>">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>