<?php require 'header.php'; ?>

<table>
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Skills</th>
</tr>

<?php
if (file_exists("students.txt")) {
    $data = file("students.txt", FILE_IGNORE_NEW_LINES);

    foreach ($data as $row) {
        list($name, $email, $skills) = explode("|", $row);

        $skillsArray = explode(",", $skills);
        $skillsText = implode(", ", $skillsArray);

        echo "<tr>";
        echo "<td>$name</td>";
        echo "<td>$email</td>";
        echo "<td>$skillsText</td>";
        echo "</tr>";
    }
}
?>
</table>

<?php require 'footer.php'; ?>
