<?php

require_once '../../app/config/config.php';
require_once '../../app/config/Database.php';
require_once '../../app/controllers/SlideController.php';

$database = new Database();
$db = $database->connect();

$controller = new SlideController($db);

$message = "";

/* =========================
   FORM SUBMIT LOGIC
========================= */
if (isset($_POST['submit'])) {

    $errors = [];
    $imageName = "";

    // 1. DATA SANITIZATION & EMPTY FIELDS VALIDATION
    $tab_title     = trim($_POST['tab_title'] ?? '');
    $tag_line      = trim($_POST['tag_line'] ?? '');
    $slide_title   = trim($_POST['slide_title'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $button_text   = trim($_POST['button_text'] ?? '');
    $button_link   = trim($_POST['button_link'] ?? '');
    $display_order = trim($_POST['display_order'] ?? 0);

    if (empty($tab_title))   { $errors[] = "Tab Title is required."; }
    if (empty($tag_line))    { $errors[] = "Tag Line is required."; }
    if (empty($slide_title)) { $errors[] = "Slide Title is required."; }

    // 2. SECURE IMAGE UPLOAD VALIDATION
    if (!empty($_FILES['image']['name'])) {
        
        // Check for internal upload errors
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "An error occurred during the image upload.";
        } else {
            // Validate file type (MIME Type) to ensure it's an image
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];
            
            // Using finfo to check actual file content instead of just the extension
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($fileTmpPath);

            if (!in_array($mimeType, $allowedMimeTypes)) {
                $errors[] = "Invalid file format. Only JPG, PNG, and SVG formats are permitted.";
            }

            // Validate file size (optional, e.g., max 3MB)
            $maxFileSize = 3 * 1024 * 1024; // 3 Megabytes
            if ($_FILES['image']['size'] > $maxFileSize) {
                $errors[] = "The uploaded image exceeds the maximum permitted size of 3MB.";
            }
        }

        // If no file errors occurred, generate a sanitized filename and prepare upload path
        if (empty($errors)) {
            // Clean up original file name to avoid path traversal vulnerabilities
            $cleanOriginalName = preg_replace("/[^a-zA-Z0-9\._-]/", "", $_FILES['image']['name']);
            $imageName = time() . '_' . $cleanOriginalName;
            $targetPath = UPLOAD_PATH . $imageName;
        }
    } else {
        $errors[] = "Please upload a slide image asset.";
    }

    // 3. EXECUTION OR ERROR HANDLING
    if (empty($errors)) {
        
        $data = [
            'tab_title'     => $tab_title,
            'tag_line'      => $tag_line,
            'slide_title'   => $slide_title,
            'description'   => $description,
            'button_text'   => $button_text,
            'button_link'   => $button_link,
            'image'         => $imageName,
            'display_order' => intval($display_order) // Force integer data type
        ];

        // Perform final file relocation safely right before database insert
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $result = $controller->store($data);

            if ($result) {
                header("Location: index.php");
                exit;
            } else {
                // If DB storage fails, clean up the newly uploaded file to avoid dangling assets
                if (file_exists($targetPath)) {
                    unlink($targetPath);
                }
                $message = "Failed to write slide entry into the database.";
            }
        } else {
            $message = "File system restriction: Could not transfer uploaded image to the storage directory.";
        }
    } else {
        // Flatten array arrays to build a visual notification block for the dashboard view
        $message = implode("<br>", $errors);
    }
}

?>

<!-- HTML STARTS HERE -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Slide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h2>Add New Slide</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validation Error:</strong><br>
            <?= $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <input type="text" name="tab_title" class="form-control mb-2" placeholder="Tab Title" required>

        <input type="text" name="tag_line" class="form-control mb-2" placeholder="Tag Line" required>

        <input type="text" name="slide_title" class="form-control mb-2" placeholder="Slide Title" required>

        <textarea name="description" class="form-control mb-2"></textarea>

        <input type="text" name="button_text" class="form-control mb-2">

        <input type="text" name="button_link" class="form-control mb-2">

        <input type="number" name="display_order" class="form-control mb-2" value="0">

        <input type="file" name="image" class="form-control mb-3" accept="image/png, image/jpeg, image/jpg, image/svg+xml" required>

        <button type="submit" name="submit" class="btn btn-success">Save</button>

        <a href="index.php" class="btn btn-secondary">Back</a>

    </form>

</div>

</body>
</html>