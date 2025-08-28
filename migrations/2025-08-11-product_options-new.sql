-- -----------------------------------------------------------------------
-- Drop old tables if they exist
DROP TABLE IF EXISTS sku_option_values;
DROP TABLE IF EXISTS skus;
DROP TABLE IF EXISTS product_option_values;
DROP TABLE IF EXISTS product_options;

-- -----------------------------------------------------------------------
UPDATE products
SET matix_id = CONCAT('p-', id);

-- 1. Modify the column first
ALTER TABLE products 
MODIFY matix_id VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

-- 2. Then add a unique key
ALTER TABLE products 
ADD UNIQUE KEY uq_matix_id (matix_id);
----------------------------------------------------------------------------
-- Products table
-- CREATE TABLE products2 (
--   id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,       -- Internal auto-increment ID
--   matix_id VARCHAR(191) UNIQUE,                        -- External unique product ID
--   mpn VARCHAR(255),
--   parent_product_id BIGINT UNSIGNED NULL DEFAULT NULL,
--   item_code VARCHAR(255), 
--   name VARCHAR(255) NOT NULL,
--   description TEXT,
--   extended_description TEXT, 
--   keywords VARCHAR(255),
--   manufacturer VARCHAR(255),
--   shipping_restrictions TEXT, 
--   brand VARCHAR(255), 
--   license_required VARCHAR(20), 
--   category_id VARCHAR(255),
--   base_price DECIMAL(10,2),
--   active TINYINT(1) DEFAULT 1,
--   created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
--   updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--   CONSTRAINT fk_products_parent
--     FOREIGN KEY (parent_product_id) REFERENCES products2(id) ON DELETE SET NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------------------
-- Product options table
CREATE TABLE product_options (
  option_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  option_type VARCHAR(255) NOT NULL,
  option_code VARCHAR(191) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Option values (linked to product via matix_id)
CREATE TABLE product_option_values (
  value_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  option_id BIGINT UNSIGNED NOT NULL,
  product_id VARCHAR(191) NOT NULL, -- must match products.matix_id
  value VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_pov_option
    FOREIGN KEY (option_id) REFERENCES product_options(option_id) ON DELETE CASCADE,
  CONSTRAINT fk_pov_product
    FOREIGN KEY (product_id) REFERENCES products(matix_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SKUs table (linked via products.matix_id)
CREATE TABLE skus (
  sku_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id VARCHAR(191) NOT NULL, -- must match products.matix_id
  parent_product_id VARCHAR(191) NULL,
  sku_code VARCHAR(191) NOT NULL,
  vendor_id VARCHAR(191) NOT NULL,
  vendor_product_id VARCHAR(191) NOT NULL,
  price DECIMAL(10,2) DEFAULT 0.00,
  retail_price DECIMAL(10,2) DEFAULT 0.00,
  stock_quantity INT DEFAULT 0,
  status ENUM('active', 'inactive') DEFAULT 'active',
  exclude_from_whitelabels_1 INT(11) NULL,
  exclude_from_whitelabels_2 INT(11) NULL,
  exclude_from_marketplace   INT(11) NULL,
  minimum_threshold          INT(11) NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_sku_code (sku_code),
  CONSTRAINT fk_skus_product
    FOREIGN KEY (product_id) REFERENCES products(matix_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SKU option values mapping table
CREATE TABLE sku_option_values (
  sku_id BIGINT UNSIGNED NOT NULL,
  value_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (sku_id, value_id),
  CONSTRAINT fk_sov_sku
    FOREIGN KEY (sku_id) REFERENCES skus(sku_id) ON DELETE CASCADE,
  CONSTRAINT fk_sov_value
    FOREIGN KEY (value_id) REFERENCES product_option_values(value_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
