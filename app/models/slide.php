<?php

class Slide
{
    private $connection;
    private $table = "slides";

    public function __construct($database)
    {
        $this->connection = $database;
    }

    // Get all slides
    public function getAllSlides()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY display_order ASC";

        $statement = $this->connection->prepare($query);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create slide
    public function createSlide($data)
    {
        $query = "INSERT INTO {$this->table}
        (
            tab_title,
            tag_line,
            slide_title,
            description,
            button_text,
            button_link,
            image,
            display_order
        )
        VALUES
        (
            :tab_title,
            :tag_line,
            :slide_title,
            :description,
            :button_text,
            :button_link,
            :image,
            :display_order
        )";

        $statement = $this->connection->prepare($query);

        return $statement->execute([

            ':tab_title'     => $data['tab_title'],
            ':tag_line'      => $data['tag_line'],
            ':slide_title'   => $data['slide_title'],
            ':description'   => $data['description'],
            ':button_text'   => $data['button_text'],
            ':button_link'   => $data['button_link'],
            ':image'         => $data['image'],
            ':display_order' => $data['display_order']

        ]);
    }

    public function getSlideById($id)
    {
        $query = "SELECT * FROM slides WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateSlide($data)
    {
        $query = "UPDATE slides SET
            tab_title = :tab_title,
            tag_line = :tag_line,
            slide_title = :slide_title,
            description = :description,
            button_text = :button_text,
            button_link = :button_link,
            image = :image,
            display_order = :display_order
            WHERE id = :id";

        $stmt = $this->connection->prepare($query);

        return $stmt->execute([
            ':tab_title'     => $data['tab_title'],
            ':tag_line'      => $data['tag_line'],
            ':slide_title'   => $data['slide_title'],
            ':description'   => $data['description'],
            ':button_text'   => $data['button_text'],
            ':button_link'   => $data['button_link'],
            ':image'         => $data['image'],
            ':display_order' => $data['display_order'],
            ':id'            => $data['id']
        ]);
    }

    public function deleteSlide($id)
    {
        $query = "DELETE FROM slides WHERE id = :id";

        $stmt = $this->connection->prepare($query);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}