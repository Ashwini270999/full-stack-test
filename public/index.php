<?php

require_once '../app/config/config.php';
require_once '../app/config/Database.php';
require_once '../app/controllers/SlideController.php';

$database = new Database();
$db = $database->connect();

$controller = new SlideController($db);

$slides = $controller->index();

/*
GROUP TABS
*/
$tabs = [];
foreach ($slides as $index => $slide) {
    if (!isset($tabs[$slide['tab_title']])) {
        $tabs[$slide['tab_title']] = $index;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WPoets Frontend</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">
    <link rel="stylesheet" href="<?=SITE_URL?>assets/css/style.css">
</head>
<body>

<section class="main-section">
    <div class="custom-container">

        <div class="main-heading">
            <h1>DelphianLogic in Action</h1>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo</p>
        </div>

        <div class="desktop-view">
            <div class="main-wrapper">

                <div class="tab-wrapper">
                    <?php
                    $count = 0;
                    foreach($tabs as $tab => $index):
                        $icon_filename = 'DL-learning.svg';
                        if (strpos(strtolower($tab), 'tech') !== false) {
                            $icon_filename = 'DL-technology.svg';
                        } elseif (strpos(strtolower($tab), 'comm') !== false) {
                            $icon_filename = 'DL-communication.svg';
                        }
                    ?>
                        <div class="tab-item <?= $count == 0 ? 'active' : '' ?>" data-slide="<?= $index ?>">
                            <img src="<?=SITE_URL?>assets/images/<?= $icon_filename ?>" class="tab-icon" alt="Tab Icon">
                            <span class="tab-text"><?= $tab ?></span>
                        </div>
                    <?php
                    $count++;
                    endforeach;
                    ?>
                </div>

                <div class="content-wrapper">
                    <div class="content-slider">
                        <?php foreach($slides as $slide): ?>
                            <div>
                                <div class="slide-box">
                                    <h5><?= $slide['tag_line'] ?></h5>
                                    <h2><?= $slide['slide_title'] ?></h2>
                                    <a href="<?= $slide['button_link'] ?>" class="learn-more-link">
                                        <?= $slide['button_text'] ?> &rarr;
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="image-wrapper">
                    <div class="image-slider">
                        <?php foreach($slides as $slide): ?>
                            <div>
                                <img src="<?= UPLOAD_URL . $slide['image'] ?>" alt="Slide Production Showcase">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="mobile-view">
            <?php foreach($slides as $index => $slide): 
                $mobile_icon_filename = 'DL-learning.svg';
                if (strpos(strtolower($slide['tab_title']), 'tech') !== false) {
                    $mobile_icon_filename = 'DL-technology.svg';
                } elseif (strpos(strtolower($slide['tab_title']), 'comm') !== false) {
                    $mobile_icon_filename = 'DL-communication.svg';
                }
            ?>
                <div class="mobile-card">
                    <div class="mobile-tab" data-slide="<?= $index ?>">
                        <div class="mobile-tab-left">
                            <img src="<?=SITE_URL?>assets/images/<?= $mobile_icon_filename ?>" alt="Category Icon">
                            <h3 class="mobile-title"><?= $slide['tab_title'] ?></h3>
                        </div>
                        
                        <div class="mobile-icon" data-minus-src="<?=SITE_URL?>assets/images/minus-01.svg" data-plus-src="<?=SITE_URL?>assets/images/plus-01.svg">
                            <img src="<?=SITE_URL?>assets/images/<?= $index == 0 ? 'minus-01.svg' : 'plus-01.svg' ?>" alt="Toggle State Indicator">
                        </div>
                    </div>

                    <div class="mobile-content" <?= $index != 0 ? 'style="display:none"' : '' ?>>
                        <div class="mobile-slide" style="background-image: url('<?= UPLOAD_URL . $slide['image'] ?>');">
                            <h5><?= $slide['tag_line'] ?></h5>
                            <h2><?= $slide['slide_title'] ?></h2>
                            <a href="<?= $slide['button_link'] ?>" class="learn-more-link">
                                <?= $slide['button_text'] ?> &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<script src="<?=SITE_URL?>assets/js/main.js"></script>

</body>
</html>