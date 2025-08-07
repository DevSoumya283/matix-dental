-- Drop existing tables in reverse order due to foreign key dependencies
DROP TABLE IF EXISTS sku_option_values;
DROP TABLE IF EXISTS skus;
DROP TABLE IF EXISTS product_option_values;
DROP TABLE IF EXISTS product_options;
DROP TABLE IF EXISTS products;

CREATE TABLE products (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  matix_id VARCHAR(255),
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
  created_at DATETIME,
  updated_at DATETIME
);





-- 2. product_options table
CREATE TABLE product_options (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    CONSTRAINT fk_product_options_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON DELETE CASCADE
);

-- 3. product_option_values table
CREATE TABLE product_option_values (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT NOT NULL,
    option_id BIGINT NOT NULL,
    value VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    CONSTRAINT fk_option_values_option FOREIGN KEY (option_id)
        REFERENCES product_options(id) ON DELETE CASCADE,
    CONSTRAINT fk_option_values_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON DELETE CASCADE
);


-- 4. skus table
CREATE TABLE skus (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT NOT NULL,
    sku_code VARCHAR(255) NOT NULL UNIQUE,
    price DECIMAL(10,2) NULL,
    retail_price DECIMAL(10,2) NULL,
    stock_quantity INT DEFAULT 0,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    CONSTRAINT fk_skus_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON DELETE CASCADE
);

-- 5. sku_option_values table
CREATE TABLE sku_option_values (
    sku_id BIGINT NOT NULL,
    value_id BIGINT NOT NULL,
    PRIMARY KEY (sku_id, value_id),
    CONSTRAINT fk_sku_option_sku FOREIGN KEY (sku_id)
        REFERENCES skus(id) ON DELETE CASCADE,
    CONSTRAINT fk_sku_option_value FOREIGN KEY (value_id)
        REFERENCES product_option_values(id) ON DELETE CASCADE
);
