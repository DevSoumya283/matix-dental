-- Drop existing tables in reverse order due to foreign key dependencies
DROP TABLE IF EXISTS sku_option_values;
DROP TABLE IF EXISTS skus;
DROP TABLE IF EXISTS product_option_values;
DROP TABLE IF EXISTS product_options;
DROP TABLE IF EXISTS products;


CREATE TABLE products (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    matix_id TEXT,
    sku TEXT,
    mpn TEXT,
    item_code TEXT,
    name TEXT NOT NULL,
    description TEXT,
    extended_description TEXT,
    keywords TEXT,
    manufacturer TEXT,
    product_procedures TEXT,
    shipping_restrictions TEXT,
    brand TEXT,
    category_code TEXT,
    arch TEXT,
    weight TEXT,
    size TEXT,
    weight_type TEXT,
    license_required BOOLEAN DEFAULT FALSE,
    category_id BIGINT,
    color TEXT,
    msds_location TEXT,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    unit_of_measure_selling TEXT,
    manufacturer_item_no TEXT,
    manufacturer_ins_sheet TEXT,
    quantity_per_box INT DEFAULT 0,
    previous_item_no TEXT,
    sample BOOLEAN DEFAULT FALSE,
    ship_weight TEXT,
    fluoride TEXT,
    flavor TEXT,
    shade TEXT,
    grit TEXT,
    set_rate TEXT,
    viscosity TEXT,
    firmness TEXT,
    handle_size TEXT,
    handle_finish TEXT,
    tip_finish TEXT,
    tip_diameter TEXT,
    tip_material TEXT,
    head_diameter TEXT,
    head_length TEXT,
    diameter TEXT,
    shaft_dimensions TEXT,
    shaft_description TEXT,
    blade_description TEXT,
    anatomic_use TEXT,
    instrument_description TEXT,
    palm_thickness TEXT,
    finger_thickness TEXT,
    texture TEXT,
    delivery_system TEXT,
    volume TEXT,
    dimensions TEXT,
    stone_type TEXT,
    stone_separation_time TEXT,
    setting_time TEXT,
    band_thickness TEXT,
    contents TEXT,
    returnable BOOLEAN DEFAULT FALSE,
    tax_per_state TEXT,
    average_rating DECIMAL(3,2),
    active ENUM('active','inactive') DEFAULT 'active'
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
    option_id BIGINT NOT NULL,
    value VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    CONSTRAINT fk_option_values_option FOREIGN KEY (option_id)
        REFERENCES product_options(id) ON DELETE CASCADE
);

-- 4. skus table
CREATE TABLE skus (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT NOT NULL,
    sku_code VARCHAR(255) NOT NULL UNIQUE,
    price DECIMAL(10,2) NULL,
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
