
DROP TABLE IF EXISTS apiproducts;
CREATE DATABASE apiproducts CHARACTER SET utf8 COLLATE utf8_general_ci;

USE apiproducts;

DROP TABLE IF EXISTS `stores`;
CREATE TABLE `stores` (
	store_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    store_email VARCHAR(256) NOT NULL,
    store_password VARCHAR(128) NOT NULL,
    store_status TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0{Deleted}; 1{Active}; 2{Blocked};',
    store_register DATETIME NOT NULL DEFAULT NOW(),
    
    PRIMARY KEY (store_id)
);

ALTER TABLE `stores`
ADD INDEX `idx_email_pass` (store_email, store_password);

DROP TABLE IF EXISTS `stores_informations`;
CREATE TABLE `stores_informations` (
	store_info_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    store_info_store_id INT UNSIGNED  NOT NULL,
	store_name VARCHAR(256) NOT NULL,
    store_email VARCHAR(256),
    store_path_logo VARCHAR(256),
    store_cnpj VARCHAR(24),
    store_coordinate POINT NOT NULL,
	
    PRIMARY KEY (store_info_id),
    SPATIAL KEY `store_coordinate` (store_coordinate),
    CONSTRAINT fk_store_info_store_id FOREIGN KEY (store_info_store_id) REFERENCES stores(store_id)
);

DROP TABLE IF EXISTS `stores_products`;
CREATE TABLE `stores_products` (
	product_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_store_id INT UNSIGNED NOT NULL,
    product_views INT DEFAULT 0,
    product_name_pt VARCHAR(256) NOT NULL,
    product_name_en VARCHAR(256) NOT NULL,
    product_name_es VARCHAR(256) NOT NULL,
    product_sku VARCHAR(256) NOT NULL,
    product_path_image VARCHAR(256),
    product_status TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0{deleted}; 1{active}',
    
    PRIMARY KEY (product_id),
    CONSTRAINT fk_product_store_id FOREIGN KEY (product_store_id) REFERENCES stores(store_id)
);

ALTER TABLE `stores_products`
ADD INDEX `idx_pId_ps_id_ps` (product_id, product_store_id, product_status);
