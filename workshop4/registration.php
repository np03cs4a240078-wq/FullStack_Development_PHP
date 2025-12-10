<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>User Registration</title>
<link rel="stylesheet" href="style.css">
</head>
<?php
// Initialize variables
$name = $email = $password = $confirm_password = "";
$nameErr = $emailErr = $passwordErr = $confirmErr = "";
$successMsg = "";
$fileError = "";

// Show success message if ?success=1
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successMsg = "Registration successful!";

    // Redirect immediately to remove ?success=1 from URL to prevent it showing again
    $url = strtok($_SERVER['REQUEST_URI'], '?'); // current page without query
    header("Refresh:3; url=$url"); // redirect after 3 seconds
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Trim inputs
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    // Name validation
    if ($name === "") {
        $nameErr = "Name is required";
    }

    // Email validation
    if ($email === "") {
        $emailErr = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }

    // Password validation
    if ($password === "") {
        $passwordErr = "Password is required";
    } elseif (strlen($password) < 8) {
        $passwordErr = "Password must be at least 8 characters long";
    } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $passwordErr = "Password must contain at least one special character";
    }

    // Confirm Password validation
    if ($confirm_password === "") {
        $confirmErr = "Please confirm your password";
    } elseif ($password !== $confirm_password) {
        $confirmErr = "Passwords do not match";
    }

    // If no validation errors, proceed
    if (!$nameErr && !$emailErr && !$passwordErr && !$confirmErr) {
        $file = "users.json";

        // Read existing users
        if (file_exists($file)) {
            $jsonData = file_get_contents($file);
            if ($jsonData === false) {
                $fileError = "Failed to read user data file.";
            } else {
                $users = json_decode($jsonData, true);
                if (!is_array($users)) {
                    $users = [];
                }
            }
        } else {
            $users = [];
        }

        if (!$fileError) {
            // Check for duplicate name or email
            foreach ($users as $user) {
                if (strtolower($user['name']) === strtolower($name)) {
                    $nameErr = "Username already exists";
                }
                if (strtolower($user['email']) === strtolower($email)) {
                    $emailErr = "Email already registered";
                }
            }
        }

        // If still no errors, save new user
        if (!$fileError && !$nameErr && !$emailErr) {
            $newUser = [
                "name" => $name,
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT)
            ];

            $users[] = $newUser;

            $jsonEncoded = json_encode($users, JSON_PRETTY_PRINT);
            if ($jsonEncoded === false) {
                $fileError = "Failed to encode user data.";
            } else {
                $writeResult = file_put_contents($file, $jsonEncoded);
                if ($writeResult === false) {
                    $fileError = "Failed to write user data file.";
                } else {
                    // Redirect to same page with success flag
                    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
                    exit();
                }
            }
        }
    }
}
?>
<body>

<form method="POST" action="">
    <h2>User Registration</h2>

    <?php if ($successMsg): ?>
        <div class="success"><?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>

    <?php if ($fileError): ?>
        <div class="file-error"><?= htmlspecialchars($fileError) ?></div>
    <?php endif; ?>

    <label for="name">Full Name:</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" />
    <span class="error"><?= $nameErr ?></span>

    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" />
    <span class="error"><?= $emailErr ?></span>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" />
    <span class="error"><?= $passwordErr ?></span>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" />
    <span class="error"><?= $confirmErr ?></span>

    <input type="submit" value="Submit" />
</form>

</body>
</html>
