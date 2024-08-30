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



