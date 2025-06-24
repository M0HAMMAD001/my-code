CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    category VARCHAR(50),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample products
INSERT INTO products (name, description, price, image_url, category, stock) VALUES
('Gaming PC Pro', 'High-performance gaming PC with RTX 3080', 1999.99, 'p4.png', 'Core Systems', 10),
('4K Gaming Monitor', '27-inch 4K HDR Gaming Monitor', 499.99, 'p5.png', 'Visuals Hub', 15),
('Mechanical Keyboard', 'RGB Mechanical Gaming Keyboard', 129.99, 'p6.png', 'Control Center', 20),
('Gaming Headset', '7.1 Surround Sound Gaming Headset', 89.99, 'p7.png', 'Sound Zone', 25),
('Gaming Chair', 'Ergonomic Gaming Chair with Lumbar Support', 299.99, 'p2.png', 'Comfort Corner', 8),
('Gaming Mouse Pad', 'Extended RGB Gaming Mouse Pad', 29.99, 'p8.png', 'Gear Up', 30); 