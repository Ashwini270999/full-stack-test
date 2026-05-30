<?php

require_once '../../app/config/config.php';
require_once '../../app/config/Database.php';
require_once '../../app/controllers/SlideController.php';

$database = new Database();
$db = $database->connect();

$controller = new SlideController($db);

/*
|--------------------------------------------------------------------------
| GET ID FROM URL
|--------------------------------------------------------------------------
*/

$id = $_GET['id'] ?? null;

if (!$id) {

    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| FETCH EXISTING SLIDE DATA
|--------------------------------------------------------------------------
*/

$slide = $controller->getById($id);

/*
|--------------------------------------------------------------------------
| UPDATE FORM SUBMIT
|--------------------------------------------------------------------------
*/

if (isset($_POST['update'])) {

    $errors = [];
    $imageName = $slide['image']; // Default to keeping the old image name

    // 1. DATA SANITIZATION & EMPTY REQUIRED FIELDS VALIDATION
    $tab_title     = trim($_POST['tab_title'] ?? '');
    $tag_line      = trim($_POST['tag_line'] ?? '');
    $slide_title   = trim($_POST['slide_title'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $button_text   = trim($_POST['button_text'] ?? '');
    $button_link   = trim($_POST['button_link'] ?? '');
    $display_order = trim($_POST['display_order'] ?? 0);

    if (empty($tab_title))   { $errors[] = "Tab Title cannot be left empty."; }
    if (empty($tag_line))    { $errors[] = "Tag Line cannot be left empty."; }
    if (empty($slide_title)) { $errors[] = "Slide Title cannot be left empty."; }

    // 2. OPTIONAL NEW IMAGE UPLOAD SECURITY CHECK
    $newImageUploaded = false;
    if (!empty($_FILES['image']['name'])) {
        
        // Validate internal upload parameters
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "An error occurred during the new image upload loop.";
        } else {
            // Confirm the underlying binary structure is a safe graphic format
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];
            
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($fileTmpPath);

            if (!in_array($mimeType, $allowedMimeTypes)) {
                $errors[] = "Invalid file format. Only JPG, PNG, and SVG elements are permitted.";
            }

            // Size Constraint Enforcement (Max 3MB)
            $maxFileSize = 3 * 1024 * 1024;
            if ($_FILES['image']['size'] > $maxFileSize) {
                $errors[] = "The uploaded file exceeds the 3MB storage configuration limit.";
            }
        }

        // Prepare file details if no errors occurred
        if (empty($errors)) {
            $cleanOriginalName = preg_replace("/[^a-zA-Z0-9\._-]/", "", $_FILES['image']['name']);
            $imageName = time() . '_' . $cleanOriginalName;
            $targetPath = UPLOAD_PATH . $imageName;
            $newImageUploaded = true;
        }
    }

    // 3. DATABASE TRANSACTION & ATOMIC FILE SWAPPING
    if (empty($errors)) {
        
        $data = [
            'id'            => $id,
            'tab_title'     => $tab_title,
            'tag_line'      => $tag_line,
            'slide_title'   => $slide_title,
            'description'   => $description,
            'button_text'   => $button_text,
            'button_link'   => $button_link,
            'image'         => $imageName,
            'display_order' => intval($display_order) // Hard-cast to dynamic integer properties
        ];

        // Process file move if a brand new file payload was delivered
        if ($newImageUploaded) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                
                // Execute database update layer via Controller
                $result = $controller->update($data);

                if ($result) {
                    // CRUCIAL: Only delete the older asset if the new file is saved and DB transaction succeeds
                    $oldImagePath = UPLOAD_PATH . $slide['image'];
                    if (!empty($slide['image']) && file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                    
                    header("Location: index.php");
                    exit;
                } else {
                    // Fallback cleanup if query fails: remove dangling upload to save server space
                    if (file_exists($targetPath)) {
                        unlink($targetPath);
                    }
                    $message = "Database Error: Could not overwrite slide properties record.";
                }
            } else {
                $message = "File system restriction: Write access denied on uploads directory destination.";
            }
        } else {
            // Simple plain update routine execution when retaining original graphic structures
            $result = $controller->update($data);
            if ($result) {
                header("Location: index.php");
                exit;
            } else {
                $message = "Database Error: Slide record values could not be transformed.";
            }
        }
    } else {
        // Flatten array parameters out to pass to your Bootstrap alerting block placeholder
        $message = implode("<br>", $errors);
    }
}
?>


<!DOCTYPE html>
<html>

<head>

    <title>Edit Slide</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <h2>Edit Slide</h2>
   <?php if (!empty($message)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Update Rejected:</strong><br>
        <?= $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

    <div class="mb-3">
        <label class="form-label fw-bold">Tab Title</label>
        <input
            type="text"
            name="tab_title"
            class="form-control"
            value="<?= $slide['tab_title']; ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Tag Line</label>
        <input
            type="text"
            name="tag_line"
            class="form-control"
            value="<?= $slide['tag_line']; ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Slide Title</label>
        <input
            type="text"
            name="slide_title"
            class="form-control"
            value="<?= $slide['slide_title']; ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea
            name="description"
            class="form-control"
            rows="3"
        ><?= $slide['description']; ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Button Text</label>
        <input
            type="text"
            name="button_text"
            class="form-control"
            value="<?= $slide['button_text']; ?>"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Button Link</label>
        <input
            type="text"
            name="button_link"
            class="form-control"
            value="<?= $slide['button_link']; ?>"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Display Order</label>
        <input
            type="number"
            name="display_order"
            class="form-control"
            value="<?= $slide['display_order']; ?>"
        >
    </div>

    <div class="mb-3">
        <label class="form-label d-block">Current Image</label>
        <img
            src="<?= UPLOAD_URL . $slide['image']; ?>"
            class="img-thumbnail"
            width="120"
            alt="Current Slide Graphic"
        >
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Upload New Image (Optional)</label>
        <input
            type="file"
            name="image"
            class="form-control"
            accept="image/png, image/jpeg, image/jpg, image/svg+xml"
        >
        <div class="form-text text-muted">Leave empty to retain the current image. Only JPG, PNG, and SVG are supported.</div>
    </div>

    <div class="mt-4">
        <button
            type="submit"
            name="update"
            class="btn btn-primary px-4"
        >
            Update Slide
        </button>

        <a
            href="index.php"
            class="btn btn-secondary px-4"
        >
            Back
        </a>
    </div>

</form>
</div>

</body>
</html>