<?php
require_once '../../app/config/config.php';
require_once '../../app/config/Database.php';
require_once '../../app/controllers/SlideController.php';

$database = new Database();
$db = $database->connect();

$controller = new SlideController($db);
$slides = $controller->index();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Slides</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between mb-3">
        <h2>Slides</h2>
        <a href="create.php" class="btn btn-primary">+ Add Slide</a>
    </div>

    <table class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>ID</th>
                <th>Tab Title</th>
                <th>Slide Title</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($slides as $slide): ?>

            <tr>
                <td><?= $slide['id']; ?></td>
                <td><?= $slide['tab_title']; ?></td>
                <td><?= $slide['slide_title']; ?></td>
                <td>
                    <img src="<?= UPLOAD_URL . $slide['image']; ?>" width="80">
                </td>
                <td>
                    <a href="edit.php?id=<?= $slide['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?= $slide['id']; ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this slide? This action cannot be undone.')">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>