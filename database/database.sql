CREATE TABLE slides (

    id INT AUTO_INCREMENT PRIMARY KEY,

    tab_title VARCHAR(255) NOT NULL,

    tag_line VARCHAR(255) NOT NULL,

    slide_title VARCHAR(255) NOT NULL,

    description TEXT NOT NULL,

    button_text VARCHAR(100) NOT NULL,

    button_link VARCHAR(255) NOT NULL,

    image VARCHAR(255) NOT NULL,

    display_order INT DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);