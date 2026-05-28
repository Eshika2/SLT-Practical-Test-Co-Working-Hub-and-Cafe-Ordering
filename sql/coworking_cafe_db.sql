CREATE DATABASE coworking_cafe_db;

USE coworking_cafe_db;

CREATE TABLE user_types (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_type VARCHAR(255),
	is_active INT NULL DEFAULT '1',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO user_types (user_type) VALUES ('admin');
INSERT INTO user_types (user_type) VALUES ('user');

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type_id int(1) DEFAULT 2,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_type_id) REFERENCES user_types(id)
);


CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    space_name VARCHAR(100) NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    is_active INT NULL DEFAULT '1',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE cafe_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    is_active INT NULL DEFAULT '1',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO cafe_items (name, price, image) VALUES
('Coffee', 450.00, 'coffee.jpg'),
('Tea', 250.00, 'tea.jpg'),
('Sandwich', 650.00, 'sandwich.jpg'),
('Cup Cake', 300.00, 'cupcake.jpg'),
('Burger', 900.00, 'burger.jpg');

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (item_id) REFERENCES cafe_items(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (item_id) REFERENCES cafe_items(id)
);