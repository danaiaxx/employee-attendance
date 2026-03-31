<?php
    error_reporting(0);
    ini_set('display_errors', 0);
    include "db.php";
?>

<?php
    if (isset($_POST['add'])){
        $depCode = $_POST['depCode'];
        $depName = $_POST['depName'];
        $depHead = $_POST['depHead'];
        $depTelNo = $_POST['depTelNo'];

        $conn->query("INSERT INTO departments (depCode, depName, depHead, depTelNo)
        VALUES ('$depCode', '$depName', '$depHead', '$depTelNo')");

        header("Location:departments.php");
        exit();
    }

    if (isset($_GET['delete'])){
        $depCode = $_GET['delete'];
        $conn->query("DELETE FROM departments WHERE depCode=$depCode");

    header("Location: departments.php");
    exit();
    }

    $editData = null;
    if (isset($_GET['edit'])){
        $depCode = $_GET['edit'];
        $res = $conn->query("SELECT * FROM departments WHERE depCode=$depCode");
        $editData = $res->fetch_assoc();
    }

    if (isset($_POST['update'])){
    $depCode = $_POST['id']; //original id

    $newdepCode = $_POST['depCode'];
    $depName = $_POST['depName'];
    $depHead = $_POST['depHead'];
    $depTelNo = $_POST['depTelNo'];

    $check = $conn->query("SELECT * FROM departments WHERE depCode='$newdepCode' AND depCode != '$depCode'");

    if ($check->num_rows > 0) {
        echo "<p style='color:red;'>ID already exists. Please use another department code.</p>";
    } else {
        $conn->query("UPDATE departments SET 
            depCode='$newdepCode',
            depName='$depName',
            depHead='$depHead',
            depTelNo='$depTelNo'
            WHERE depCode='$depCode'");

        header("Location: departments.php");
        exit();
    }
}
// ===== FETCH =====
$result = $conn->query("SELECT * FROM departments");
?>

<a href="index.php">Back</a>

<h2>Manage Department</h2>

<form method="POST">
<input type="hidden" name="id" value="<?= $editData['depCode'] ?? '' ?>">

Department Code: <input type="text" name="depCode" value="<?= $editData['depCode'] ?? '' ?>" required><br>
Department Name: <input type="text" name="depName" value="<?= $editData['depName'] ?? '' ?>" required><br>
Department Head: <input type="text" name="depHead" value="<?= $editData['depHead'] ?? '' ?>" required><br>
Department Tel No.: <input type="text" name="depTelNo" value="<?= $editData['depTelNo'] ?? '' ?>" required><br>

<?php if ($editData): ?>
    <button name="update">Update</button>
<?php else: ?>
    <button name="add">Add</button>
<?php endif; ?>
</form>

<table border="1">
<tr>
    <th>Code</th>
    <th>Name</th>
    <th>Head</th>
    <th>Tel No.</th>
    <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['depCode'] ?></td>
    <td><?= $row['depName'] ?></td>
    <td><?= $row['depHead'] ?></td>
    <td><?= $row['depTelNo'] ?></td>
    
    <td>
        <a href="departments.php?edit=<?= $row['depCode'] ?>">Edit</a> |
        <a href="departments.php?delete=<?= $row['depCode'] ?>">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

