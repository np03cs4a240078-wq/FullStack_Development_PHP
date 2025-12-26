<?php
require 'db.php';
$sql = "SELECT * FROM categories";

$stmt=$pdo->query($sql);

$categories = $stmt->fetchAll();

foreach ($categories as $category) {
	echo"<br>";
	echo $category['name'];
}



?>