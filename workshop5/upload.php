<?php require 'header.php'; ?>

<?php
function uploadPortfolioFile($file) {
    $allowed = ["pdf", "jpg", "png"];
    $maxSize = 2 * 1024 * 1024;

    if ($file['error'] != 0) {
        throw new Exception("File upload error.");
    }

    if ($file['size'] > $maxSize) {
        throw new Exception("File too large. Max 2MB allowed.");
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        throw new Exception("Only PDF, JPG, PNG allowed.");
    }

    if (!is_dir("uploads")) {
        throw new Exception("Uploads folder not found.");
    }

    $newName = time() . "_" . str_replace(" ", "_", $file['name']);
    $destination = "uploads/" . $newName;

    move_uploaded_file($file['tmp_name'], $destination);
}

if (isset($_POST['upload'])) {
    try {
        uploadPortfolioFile($_FILES['portfolio']);
        echo "<p class='success'>File uploaded successfully!</p>";
    } catch (Exception $e) {
        echo "<p class='error'>" . $e->getMessage() . "</p>";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="portfolio">
    <input type="submit" name="upload" value="Upload File">
</form>

<?php require 'footer.php'; ?>
