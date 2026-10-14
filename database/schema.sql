CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user'
);

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL DEFAULT 'General',
    copies INT NOT NULL DEFAULT 1
);

CREATE TABLE borrows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    borrowed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    due_at DATE NULL,
    returned_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);


INSERT INTO users (username, password, role) VALUES
('admin', '$2y$12$LRo2Esfd9MzrsBbkPRMWzesgLZyHBN/bBD/vDySIdbsBUPbIkKUze', 'admin'),
('maria', '$2y$12$6UccfWGz17TXyVNkECTLseIwc9Imt0NFEHfQs1OQMZ3zbkOI2ocKG', 'user');

INSERT INTO books (title, author, category, copies) VALUES
('Noli Me Tangere', 'Jose Rizal', 'Filipino Literature', 3),
('El Filibusterismo', 'Jose Rizal', 'Filipino Literature', 2),
('Introduction to Algorithms', 'Thomas H. Cormen', 'Computer Science', 1),
('Clean Code', 'Robert C. Martin', 'Computer Science', 2),
('The Pragmatic Programmer', 'Andrew Hunt', 'Computer Science', 2),
('Database System Concepts', 'Abraham Silberschatz', 'Computer Science', 1),
('Principles of Marketing', 'Philip Kotler', 'Business', 2),
('A Brief History of Time', 'Stephen Hawking', 'Science', 2),
('Sapiens', 'Yuval Noah Harari', 'History', 3),
('Atomic Habits', 'James Clear', 'Self-Development', 2);
