-- Run once on an existing PreLoved database to enable customer-owned stores.
CREATE TABLE IF NOT EXISTS stores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  name VARCHAR(140) NOT NULL,
  description TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_stores_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

ALTER TABLE products ADD COLUMN store_id INT UNSIGNED DEFAULT NULL;
ALTER TABLE products ADD CONSTRAINT fk_products_store
  FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE SET NULL;
CREATE INDEX idx_products_store ON products(store_id);
