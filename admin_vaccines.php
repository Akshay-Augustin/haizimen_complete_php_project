<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

if (($_SESSION['auth']['role'] ?? '') !== 'admin') {
    die('Access denied.');
}

$errors = [];
$success = null;
$editId = (int)($_GET['edit'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    $vaccine_name = trim($_POST['vaccine_name'] ?? '');
    $age_group = trim($_POST['age_group'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = trim($_POST['status'] ?? 'active');

    if ($vaccine_name === '') $errors[] = 'Vaccine name is required.';
    if ($description === '') $errors[] = 'Description is required.';

    if (!$errors) {
        if ($action === 'edit') {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $conn->prepare("UPDATE vaccines SET vaccine_name = ?, age_group = ?, description = ?, status = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $vaccine_name, $age_group, $description, $status, $id);
            $stmt->execute();
            $success = 'Vaccine updated successfully.';
            $editId = 0;
        } else {
            $stmt = $conn->prepare("INSERT INTO vaccines (vaccine_name, age_group, description, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $vaccine_name, $age_group, $description, $status);
            $stmt->execute();
            $success = 'Vaccine added successfully.';
        }
    }
}

$editData = null;
if ($editId > 0) {
    $stmt = $conn->prepare("SELECT * FROM vaccines WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
}

$vaccines = $conn->query("SELECT * FROM vaccines ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin - Vaccines</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f9ff;
    color: #1b2b3a;
    padding: 30px;
}

.wrap {
    max-width: 1150px;
    margin: auto;
}

.card {
    background: #ffffff;
    padding: 25px;
    border-radius: 14px;
    margin-bottom: 24px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

h1, h2 {
    margin-top: 0;
    color: #1b6ec2;
}

/* FORM */
label {
    font-weight: bold;
    display: block;
    margin-top: 10px;
    color: #1b2b3a;
}

input, textarea, select {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    margin-bottom: 14px;
    border-radius: 6px;
    border: 1px solid #cfe3f7;
    background: #f9fcff;
    color: #1b2b3a;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
}

/* BUTTON */
button {
    background: #1b6ec2;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #155a9c;
}

/* TABLE */
.table-wrap {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 14px;
    border-bottom: 1px solid #e6eef7;
    text-align: left;
    vertical-align: top;
    font-size: 14px;
}

th {
    background: #eaf4ff;
    color: #1b6ec2;
    font-weight: bold;
}

tr:hover {
    background: #f4f9ff;
}

/* LINKS */
a {
    color: #1b6ec2;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

/* MESSAGES */
.msg {
    padding: 12px 14px;
    background: rgba(80,200,120,.12);
    border: 1px solid rgba(80,200,120,.35);
    border-radius: 8px;
    margin-bottom: 15px;
    color: #2e7d32;
}

.err {
    padding: 12px 14px;
    background: rgba(255,90,60,.12);
    border: 1px solid rgba(255,90,60,.35);
    border-radius: 8px;
    margin-bottom: 15px;
    color: #c62828;
}

/* BACK BUTTON */
.back {
    margin-top: 15px;
    display: inline-block;
}
    </style>
</head>
<body>
<div class="wrap">

    <div class="card">
        <h1><?php echo $editData ? 'Edit Vaccine' : 'Add Vaccine'; ?></h1>

        <?php if ($success): ?>
            <div class="msg"><?php echo e($success); ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="err">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $editData ? 'edit' : 'add'; ?>">
            <input type="hidden" name="id" value="<?php echo e($editData['id'] ?? ''); ?>">

            <label>Vaccine Name</label>
            <input type="text" name="vaccine_name" value="<?php echo e($editData['vaccine_name'] ?? ''); ?>" required>

            <label>Age Group</label>
            <input type="text" name="age_group" value="<?php echo e($editData['age_group'] ?? ''); ?>">

            <label>Description</label>
            <textarea name="description" rows="4" required><?php echo e($editData['description'] ?? ''); ?></textarea>

            <label>Status</label>
            <select name="status">
                <option value="active" <?php echo (($editData['status'] ?? '') === 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo (($editData['status'] ?? '') === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
            </select>

            <button type="submit"><?php echo $editData ? 'Update Vaccine' : 'Add Vaccine'; ?></button>
        </form>
    </div>

    <div class="card">
        <h2>All Vaccines</h2>
        <div class="table-wrap">
        <table>
            <tr>
                <!-- <th>ID</th> -->
                <th>Name</th>
                <th>Age Group</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while($row = $vaccines->fetch_assoc()): ?>
            <tr>
                <!-- <td><?php echo (int)$row['id']; ?></td> -->
                <td><?php echo e($row['vaccine_name']); ?></td>
                <td><?php echo e($row['age_group']); ?></td>
                <td><?php echo e($row['description']); ?></td>
                <td><?php echo e($row['status']); ?></td>
                <td><a href="admin_vaccines.php?edit=<?php echo (int)$row['id']; ?>">Edit</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        </div>
        <p><a class="back" href="admin_dashboard.php">← Back to Dashboard</a></p>
    </div>

</div>
</body>
</html>