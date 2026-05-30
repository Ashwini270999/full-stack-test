<?php

require_once __DIR__ . '/../models/Slide.php';

class SlideController
{
    private $slideModel;

    public function __construct($db)
    {
        $this->slideModel = new Slide($db);
    }

    // GET ALL SLIDES (for frontend or admin list)
    public function index()
    {
        return $this->slideModel->getAllSlides();
    }

    // CREATE SLIDE
    public function store($data)
    {
        return $this->slideModel->createSlide($data);
    }

    public function getById($id)
    {
        return $this->slideModel->getSlideById($id);
    }

    public function update($data)
    {
        return $this->slideModel->updateSlide($data);
    }

    public function delete($id)
    {
        return $this->slideModel->deleteSlide($id);
    }
}