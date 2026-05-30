<?php

require_once '../../app/config/config.php';
require_once '../../app/config/Database.php';
require_once '../../app/controllers/SlideController.php';

$database = new Database();
$db = $database->connect();

$controller = new SlideController($db);

/*
|--------------------------------------------------------------------------
| GET ID
|--------------------------------------------------------------------------
*/

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| GET SINGLE RECORD
|--------------------------------------------------------------------------
*/

$slide = $controller->getById($id);

if (!$slide) {
    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE IMAGE FROM FOLDER
|--------------------------------------------------------------------------
*/

$imagePath = "../../uploads/" . $slide['image'];

if (!empty($slide['image']) && file_exists($imagePath)) {
    unlink($imagePath);
}

/*
|--------------------------------------------------------------------------
| DELETE FROM DATABASE
|--------------------------------------------------------------------------
*/

$result = $controller->delete($id);

if ($result) {
    header("Location: index.php");
    exit;
} else {
    echo "Failed to delete record";
}