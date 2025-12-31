<?php
    
    require 'db.php';

try{    if($_SERVER['REQUEST_METHOD']==="POST"){

        $student_id = $_POST['student_id'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM students
                 WHERE student_id=?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$student_id]);

        $student = $stmt->fetch();

        if(!$student){
            echo "Register First!";
            header("Refresh:1, url=register.php");
            exit;
        }
            $hashedPassword = $student['password_hash'];
            $isPasswordValid = password_verify($password,$hashedPassword );
            if(!$isPasswordValid){
                echo "Invalid Password! Please Try Again";
                exit;
            }
            echo "Successfully Logged In!";
            session_start();
            $_SESSION['logged_in']=true;
            $_SESSION['username']=$student['full_name'];
            header("Refresh:1, url=dashboard.php");
        
    }}
    catch(PDOException $e){
        die("Database Error: ".$e->getMessage() );
    }
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
</head>
<body>
    <form method="POST">
        <label for="student_id">Student Id</label> <input type="text" name="student_id" required>
        Password: <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>