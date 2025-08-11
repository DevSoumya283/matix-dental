-- Drop old tables if they exist
DROP TABLE IF EXISTS sku_option_values;
DROP TABLE IF EXISTS skus;
DROP TABLE IF EXISTS product_option_values;
DROP TABLE IF EXISTS product_options;
DROP TABLE IF EXISTS products;

-- Products table
CREATE TABLE products (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,       -- Internal auto-increment ID
  matix_id VARCHAR(255) UNIQUE,               -- External unique product ID (now UNIQUE so can be used in FK)
  mpn VARCHAR(255),
  parent_product_id BIGINT NULL DEFAULT NULL,
  item_code VARCHAR(255), 
  name VARCHAR(255) NOT NULL,
  description TEXT,
  extended_description TEXT, 
  keywords VARCHAR(500),
  manufacturer VARCHAR(255),
  shipping_restrictions TEXT, 
  brand VARCHAR(255), 
  license_required VARCHAR(20), 
  category_id VARCHAR(255),
  base_price DECIMAL(10,2),
  active ENUM('1', '0') DEFAULT '1',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Product options table (linked via matix_id)
CREATE TABLE product_options (
  option_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  option_type VARCHAR(255) NOT NULL,
  option_code VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE product_option_values (
  value_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  option_id BIGINT NOT NULL,
  product_id VARCHAR(255) NOT NULL,
  value VARCHAR(255) NOT NULL,
  FOREIGN KEY (option_id) REFERENCES product_options(option_id) ON DELETE CASCADE
);


-- SKUs table (linked via internal product ID)
CREATE TABLE skus (
  sku_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  product_id VARCHAR(255) NOT NULL,
  sku_code VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) DEFAULT 0.00,
  stock_quantity INT DEFAULT 0,
  status ENUM('active', 'inactive') DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(matix_id) ON DELETE CASCADE
);

-- SKU option values mapping table
CREATE TABLE sku_option_values (
  sku_id BIGINT NOT NULL,
  value_id BIGINT NOT NULL,
  PRIMARY KEY (sku_id, value_id),
  FOREIGN KEY (sku_id) REFERENCES skus(sku_id) ON DELETE CASCADE,
  FOREIGN KEY (value_id) REFERENCES product_option_values(value_id) ON DELETE CASCADE
);
