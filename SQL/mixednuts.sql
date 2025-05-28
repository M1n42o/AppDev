CREATE DATABASE mixednuts;
USE mixednuts;


CREATE TABLE login(
  id int(11)AUTO_INCREMENT,
  username varchar(50),
  password varchar(255),
  PRIMARY KEY(id)
);

-- ==========================
-- Table: users
-- ==========================

CREATE TABLE users(
  user_id INT(11) AUTO_INCREMENT,
  login_id int(11),
  firstname VARCHAR(200),
  lastname VARCHAR(255),
  image varchar(255),
  email VARCHAR(255),
  contact_no VARCHAR(10),
  registered_at DATE DEFAULT CURRENT_DATE,
  isAdmin TINYINT(1) NOT NULL DEFAULT 0,
  user_address VARCHAR(255),
  PRIMARY KEY(user_id),
  FOREIGN KEY (login_id) REFERENCES login(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- ==========================
-- Table: admin
-- ==========================

CREATE TABLE admin (
  admin_id INT(11) AUTO_INCREMENT,
  username VARCHAR(50) UNIQUE NOT NULL,  
  password VARCHAR(255) NOT NULL,  
  role VARCHAR(50) NOT NULL DEFAULT 'Admin',  
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,  
  PRIMARY KEY (admin_id) 
);



-- ==========================
-- Table: category
-- ==========================

CREATE TABLE category(
  category_id INT(11) AUTO_INCREMENT,
  category_name VARCHAR(150) UNIQUE NOT NULL,
  PRIMARY KEY(category_id)
);

-- ==========================
-- Table: product
-- ==========================

CREATE TABLE product(
  product_id INT(11) AUTO_INCREMENT,
  product_name VARCHAR(200),
  product_desc TEXT,
  product_image VARCHAR(255),
  price INT(11),
  category_id INT(11),
  uploaded_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(product_id),
  FOREIGN KEY(category_id) REFERENCES category(category_id) ON DELETE CASCADE
);

-- ==========================
-- Table: sizes
-- ==========================

CREATE TABLE sizes(
  size_id INT(11) AUTO_INCREMENT,
  size_name VARCHAR(100),
  PRIMARY KEY(size_id)
);

-- ==========================
-- Table: product_size_variation
-- ==========================

CREATE TABLE product_size_variation(
  variation_id INT(11) AUTO_INCREMENT,
  product_id INT(11),
  size_id INT(11),
  quantity_in_stock INT(11),
  PRIMARY KEY (variation_id),
  UNIQUE KEY uc_ps (product_id, size_id),
  FOREIGN KEY (product_id) REFERENCES product(product_id) ON DELETE CASCADE,
  FOREIGN KEY (size_id) REFERENCES sizes(size_id) ON DELETE CASCADE
);

-- ==========================
-- Table: cart
-- ==========================

CREATE TABLE cart (
  cart_id INT(11) AUTO_INCREMENT,
  user_id INT(11),
  variation_id INT(11),
  quantity INT(11),
  price INT(11),
  PRIMARY KEY(cart_id),
  UNIQUE KEY uc_cart (user_id, variation_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (variation_id) REFERENCES product_size_variation(variation_id) ON DELETE CASCADE
);

-- ==========================
-- Table: orders
-- ==========================

CREATE TABLE orders(
  order_id INT(11) AUTO_INCREMENT,
  user_id INT(11),
  delivered_to VARCHAR(150),
  phone_no VARCHAR(10),
  deliver_address VARCHAR(255),
  pay_method VARCHAR(50),
  pay_status INT(11),
  order_status INT(11),
  order_date DATE DEFAULT CURRENT_DATE,
  PRIMARY KEY(order_id),
  FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- ==========================
-- Table: order_details
-- ==========================

CREATE TABLE order_details(
  detail_id INT(11) AUTO_INCREMENT,
  order_id INT(11),
  variation_id INT(11),
  quantity INT(11),
  price INT(11),
  PRIMARY KEY(detail_id),
  FOREIGN KEY(order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
  FOREIGN KEY(variation_id) REFERENCES product_size_variation(variation_id) ON DELETE CASCADE
);

-- ==========================
-- Table: review
-- ==========================

CREATE TABLE review (
  review_id INT(11) AUTO_INCREMENT,
  user_id INT(11),
  product_id INT(11),
  review_desc TEXT,
  PRIMARY KEY(review_id),
  FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY(product_id) REFERENCES product(product_id) ON DELETE CASCADE
);

-- ==========================
-- Table: wishlist
-- ==========================

CREATE TABLE wishlist (
  wish_id INT(11) AUTO_INCREMENT,
  user_id INT(11),
  product_id INT(11),
  PRIMARY KEY (wish_id),
  UNIQUE KEY uc_wish (user_id, product_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES product(product_id) ON DELETE CASCADE
);