CREATE DATABASE IF NOT EXISTS meusistema CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE meusistema;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) DEFAULT NULL,
  status ENUM('Inativo','Ativo') DEFAULT 'Ativo',
  purchases_count INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  img VARCHAR(255) DEFAULT NULL,
  price_kg DECIMAL(12,2) DEFAULT NULL,
  price_monte DECIMAL(12,2) DEFAULT NULL,
  price_copo DECIMAL(12,2) DEFAULT NULL,
  price_unidade DECIMAL(12,2) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total DECIMAL(12,2) NOT NULL,
  status ENUM('Pendente','Pago','Cancelado') DEFAULT 'Pendente',
  payment_method VARCHAR(100) DEFAULT NULL,
  payment_reference VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  unit ENUM('kg','monte','copo','unidade') NOT NULL,
  qty DECIMAL(12,3) NOT NULL,
  price DECIMAL(12,2) NOT NULL,
  subtotal DECIMAL(12,2) NOT NULL,
  CONSTRAINT fk_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Inserindo produtos com imagens corrigidas (sem quebra de linha e com caminho unificado)
INSERT INTO products (name, img, price_kg, price_monte, price_copo, price_unidade) VALUES
('Arroz', '/fai2/assets/img/arroz.png', 1500.00, 1000.00, 250.00, 150.00),
('Feijão', '/fai2/assets/img/feijao.png', 2000.00, 1400.00, 350.00, 200.00),
('Óleo', '/fai2/assets/img/oleo.png', NULL, 3500.00, 700.00, 3500.00),
('Sal', '/fai2/assets/img/sal.png', 800.00, 600.00, 150.00, 90.00);

-- Usuário admin
INSERT IGNORE INTO users (name,email,status,purchases_count) 
VALUES ('Admin','admin@example.com','Ativo',0);
