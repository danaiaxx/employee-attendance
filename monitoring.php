<?php
error_reporting(0);
ini_set('display_errors', 0);
include "db.php";
date_default_timezone_set('Asia/Manila');

// ===== HANDLE SEARCH =====
$empId = $_POST['empId'] ?? '';
$dateFrom = $_POST['dateFrom'] ?? '';
$dateTo = $_POST['dateTo'] ?? '';

$whereClauses = [];
if ($empId !== '') {
    $whereClauses[] = "empId='$empId'";
}
if ($dateFrom !== '' && $dateTo !== '') {
    $whereClauses[] = "attDate BETWEEN '$dateFrom' AND '$dateTo'";
} elseif ($dateFrom !== '') {
    $whereClauses[] = "attDate = '$dateFrom'";
} elseif ($dateTo !== '') {
    $whereClauses[] = "attDate = '$dateTo'";
}

$whereSQL = '';
if (!empty($whereClauses)) {
    $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);
}

// ===== FETCH ATTENDANCE RECORDS =====
$records = $conn->query("
    SELECT a.*, e.depCode, e.empFName, e.empLName, e.empRPH, d.depName
    FROM attendance a
    JOIN employees e ON a.empId = e.empId
    JOIN departments d ON e.depCode = d.depCode
    $whereSQL
    ORDER BY a.attDate, a.attTimeIn
");

// ===== PROCESS RECORDS =====
$recordsArray = [];
$summary = []; // for total hours per employee
if ($records->num_rows > 0) {
    while ($row = $records->fetch_assoc()) {
        $timeIn = strtotime($row['attTimeIn']);
        $timeOut = strtotime($row['attTimeOut']);
        $hours = max(0, ($timeOut - $timeIn) / 3600);
        $row['totalHours'] = $hours;
        $recordsArray[] = $row;

        // Build summary per employee
        $eId = $row['empId'];
        if (!isset($summary[$eId])) {
            $empRow = $conn->query("SELECT empFName, empLName, depCode, empRPH FROM employees WHERE empId='$eId'")->fetch_assoc();
            $summary[$eId] = [
                'name' => $empRow['empFName'] . ' ' . $empRow['empLName'],
                'rate' => $empRow['empRPH'],
                'depCode' => $row['depCode'],
                'depName' => $row['depName'],
                'hours' => 0,
            ];
        }
        $summary[$eId]['hours'] += $hours;
    }
}
?>

<a href="index.php">Back</a>
<h2>Monitor Attendance</h2>

<form method="POST">
    Employee:
    <select name="empId">
        <option value="">-- All Employees --</option>
        <?php
        $emps = $conn->query("SELECT empId, empFName, empLName FROM employees ORDER BY empLName");
        while ($emp = $emps->fetch_assoc()) {
            $selected = ($empId == $emp['empId']) ? "selected" : "";
            echo "<option value='{$emp['empId']}' $selected>{$emp['empFName']} {$emp['empLName']}</option>";
        }
        ?>
    </select><br>

    Date From: <input type="date" name="dateFrom" value="<?= $dateFrom ?>"><br>
    Date To: <input type="date" name="dateTo" value="<?= $dateTo ?>"><br>

    <button type="submit" name="search">Search Attendance</button>
</form>

<?php if (!empty($recordsArray)): ?>
    <h3>Attendance Records</h3>
    <table border="1">
        <tr>
            <th>Record #</th>
            <th>Employee ID</th>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Total Hours</th>
        </tr>
        <?php foreach ($recordsArray as $i => $rec): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= $rec['empId'] ?></td>
            <td><?= $rec['depCode'] ?></td>
            <td><?= $rec['depName'] ?></td>
            <td><?= $rec['attDate'] ?></td>
            <td><?= date("h:i A", strtotime($rec['attTimeIn'])) ?></td>
            <td><?= date("h:i A", strtotime($rec['attTimeOut'])) ?></td>
            <td><?= number_format($rec['totalHours'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php foreach ($summary as $s): ?>
        <p>
            <b>Employee:</b> <?= $s['name'] ?> |
            <b>Department:</b> <?= $s['depCode'] ?> (<?= $s['depName'] ?>) |
            <b>Rate per Hour:</b> <?= number_format($s['rate'], 2) ?> |
            <b>Total Hours:</b> <?= number_format($s['hours'], 2) ?> |
            <b>Salary:</b> <?= number_format($s['hours'] * $s['rate'], 2) ?>
        </p>
    <?php endforeach; ?>

    <p><b>Date Generated:</b> <?= date("F d, Y h:i A") ?></p>
<?php else: ?>
    <p>No attendance records found.</p>
<?php endif; ?>