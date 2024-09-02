CREATE DATABASE product_db;

-- CREATE TABLE product (
--  id INT NOT NULL AUTO_INCREMENT,
--  name VARCHAR(128) NOT NULL,
--  size INT NOT NULL DEFAULT 0,
--  is_available BOOLEAN NOT NULL DEFAULT FALSE,
--  PRIMARY KEY (id)
-- );

CREATE TABLE categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) NOT NULL UNIQUE,
    created_at TIMESTAMP NULL DEFAULT NULL, 
    updated_at TIMESTAMP NULL DEFAULT NULL
);

INSERT INTO categories (name, created_at, updated_at) VALUES
    ('Electronics', NOW(), NOW()),
    ('Clothing', NOW(), NOW()),
    ('Home Appliances', NOW(), NOW()),
    ('Books', NOW(), NOW()),
    ('Toys', NOW(), NOW()
);


CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(9, 2) NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

INSERT INTO products (name, description, price, category_id, image_url, created_at, updated_at) VALUES
    ('Smartphone', 'Latest model with high-end features', 699.99, 1, 'http://example.com/images/smartphone.jpg', NOW(), NOW()),
    ('T-Shirt', 'Cotton t-shirt available in various colors', 19.99, 2, 'http://example.com/images/tshirt.jpg', NOW(), NOW()),
    ('Washing Machine', 'High-efficiency washing machine with various modes', 499.99, 3, 'http://example.com/images/washing_machine.jpg', NOW(), NOW()),
    ('Mystery Novel', 'A thrilling mystery novel by popular author', 12.99, 4, 'http://example.com/images/mystery_novel.jpg', NOW(), NOW()),
    ('Toy Car', 'Remote-controlled toy car for kids', 29.99, 5, 'http://example.com/images/toy_car.jpg', NOW(), NOW()
);


CREATE TABLE testimonials (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_firstname VARCHAR(255) NOT NULL,
    author_surname VARCHAR(255) NOT NULL,
    company VARCHAR(255) NULL,
    position VARCHAR(255) NULL,
    content TEXT NOT NULL,
    rating INT UNSIGNED DEFAULT 5,
    image_url VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO testimonials (author_firstname, author_surname, company, position, content, rating, image_url, created_at, updated_at) VALUES
('John', 'Doe', 'Tech Innovators Inc.', 'CEO', 'This company is fantastic! They exceeded all my expectations.', 5, 'https://example.com/images/john_doe.jpg', NOW(), NOW()),
('Jane', 'Smith', 'Creative Solutions', 'Marketing Director', 'Amazing service and a great team to work with.', 4, 'https://example.com/images/jane_smith.jpg', NOW(), NOW()),
('Emily', 'Johnson', 'Innovate Corp.', 'Product Manager', 'Their product quality is top-notch, and their support is excellent.', 5, 'https://example.com/images/emily_johnson.jpg', NOW(), NOW()),
('Michael', 'Brown', NULL, 'Freelance Designer', 'I highly recommend them for their creativity and dedication.', 4, 'https://example.com/images/michael_brown.jpg', NOW(), NOW()),
('Sarah', 'Davis', 'Future Tech', 'CTO', 'Working with them was a pleasure. They delivered on time and on budget.', 5, 'https://example.com/images/sarah_davis.jpg', NOW(), NOW());



CREATE TABLE features (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO features (description, created_at, updated_at) VALUES
('Feature 1: Basic functionality', NOW(), NOW()),
('Feature 2: Advanced settings', NOW(), NOW()),
('Feature 3: User management', NOW(), NOW()),
('Feature 4: Reporting and analytics', NOW(), NOW()),
('Feature 5: Integration with third-party services', NOW(), NOW());


CREATE TABLE projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO projects (title, description, image_url, created_at, updated_at) VALUES
('Project Alpha', 'A description for Project Alpha', 'https://example.com/image_alpha.jpg', NOW(), NOW()),
('Project Beta', 'A description for Project Beta', 'https://example.com/image_beta.jpg', NOW(), NOW()),
('Project Gamma', 'A description for Project Gamma', 'https://example.com/image_gamma.jpg', NOW(), NOW()),
('Project Delta', 'A description for Project Delta', 'https://example.com/image_delta.jpg', NOW(), NOW()),
('Project Epsilon', 'A description for Project Epsilon', 'https://example.com/image_epsilon.jpg', NOW(), NOW());

/*
 To test attaching features to the project_features table for the many to many relationship, you
 can use the JSON data for that purpose.

    {
        "title": "Project Zeta",
        "description": "A description for Project Zeta",
        "imageUrl": "https://example.com/image_zeta.jpg",
        "features": [1, 3, 5]
    },
 */


CREATE TABLE project_feature (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED NOT NULL,
    feature_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (feature_id) REFERENCES features(id) ON DELETE CASCADE
);
