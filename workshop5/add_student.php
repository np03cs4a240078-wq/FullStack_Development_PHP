<?php require 'header.php'; ?>

<?php
/* ---------- Functions ---------- */

function formatName($name) {
    return ucfirst(strtolower(trim($name)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function cleanSkills($string) {
    $skills = explode(",", $string);
    $clean = [];

    foreach ($skills as $skill) {
        $skill = trim($skill);
        if ($skill != "") {
            $clean[] = $skill;
        }
    }
    return array_unique($clean);
}

function saveStudent($name, $email, $skills) {
    if (file_exists("students.txt")) {
        $data = file("students.txt", FILE_IGNORE_NEW_LINES);
        foreach ($data as $row) {
            $parts = explode("|", $row);
            if ($parts[1] == $email) {
                throw new Exception("Duplicate email found.");
            }
        }
    }

    $line = $name . "|" . $email . "|" . implode(",", $skills) . PHP_EOL;
    file_put_contents("students.txt", $line, FILE_APPEND);
}

/* ---------- Form Handling ---------- */

if (isset($_POST['submit'])) {
    try {
        $name = formatName($_POST['name']);
        $email = trim($_POST['email']);
        $skillsText = $_POST['skills'];

        if ($name == "" || $email == "" || $skillsText == "") {
            throw new Exception("All fields are required.");
        }

        if (!validateEmail($email)) {
            throw new Exception("Invalid email format.");
        }

        $skillsArray = cleanSkills($skillsText);

        saveStudent($name, $email, $skillsArray);

        echo "<p class='success'>Student added successfully!</p>";

    } catch (Exception $e) {
        echo "<p class='error'>" . $e->getMessage() . "</p>";
    }
}
?>

<form method="post">
    <input type="text" name="name" placeholder="Name">
    <input type="email" name="email" placeholder="Email">
    <input type="text" name="skills" placeholder="Skills (comma separated)">
    <input type="submit" name="submit" value="Save Student">
</form>

<?php require 'footer.php'; ?>
