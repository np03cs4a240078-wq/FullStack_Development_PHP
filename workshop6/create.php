<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['add'])) {
    $stmt = $pdo->prepare(
        "INSERT INTO students (Name, Email, Course) VALUES (?, ?, ?)"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['course']
    ]);
    header("Location: create.php");
    exit;
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM students WHERE Id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: create.php");
    exit;
}

$editStudent = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE Id = ?");
    $stmt->execute([$_GET['edit']]);
    $editStudent = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['update'])) {
    $stmt = $pdo->prepare(
        "UPDATE students SET Name = ?, Email = ?, Course = ? WHERE Id = ?"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['course'],
        $_POST['id']
    ]);
    header("Location: create.php");
    exit;
}

$students = $pdo->query("SELECT * FROM students")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Database</title>
<style>
*{box-sizing:border-box}
body{font-family:Arial;background:#f4f6f8;padding:30px}
form{background:#fff;padding:20px;max-width:450px;border-radius:6px}
input{width:100%;padding:10px;margin-bottom:12px}
button{padding:10px;background:#007bff;color:#fff;border:none}
table{width:100%;margin-top:25px;border-collapse:collapse;background:#fff}
th,td{padding:10px;border:1px solid #ddd;text-align:center}
th{background:#007bff;color:#fff}
a{font-weight:bold;margin:0 5px;text-decoration:none}
</style>
</head>

<body>

<h2><?= $editStudent ? "Edit Student" : "Add Student" ?></h2>

<form method="POST">
    <input type="hidden" name="id" value="<?= $editStudent['Id'] ?? '' ?>">

    <input type="text" name="name" placeholder="Name"
           value="<?= $editStudent['Name'] ?? '' ?>" required>

    <input type="email" name="email" placeholder="Email"
           value="<?= $editStudent['Email'] ?? '' ?>" required>

    <input type="text" name="course" placeholder="Course"
           value="<?= $editStudent['Course'] ?? '' ?>" required>

    <?php if ($editStudent): ?>
        <button name="update">Update Student</button>
        <a href="create.php">Cancel</a>
    <?php else: ?>
        <button name="add">Add Student</button>
    <?php endif; ?>
</form>

<h2>Student List</h2>

<table>
<tr>
<th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Actions</th>
</tr>

<?php foreach ($students as $s): ?>
<tr>
<td><?= $s['Id'] ?></td>
<td><?= $s['Name'] ?></td>
<td><?= $s['Email'] ?></td>
<td><?= $s['Course'] ?></td>
<td>
<a href="?edit=<?= $s['Id'] ?>">Edit</a>
<a href="?delete=<?= $s['Id'] ?>" onclick="return confirm('Delete this student?')">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
