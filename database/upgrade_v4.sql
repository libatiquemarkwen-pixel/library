ALTER TABLE books   ADD COLUMN category VARCHAR(50) NOT NULL DEFAULT 'General' AFTER author;
ALTER TABLE borrows ADD COLUMN due_at DATE NULL AFTER borrowed_at;

UPDATE books SET category = 'Filipino Literature' WHERE author = 'Jose Rizal';
UPDATE books SET category = 'Computer Science'
 WHERE title IN ('Introduction to Algorithms', 'Clean Code', 'The Pragmatic Programmer', 'Database System Concepts');

UPDATE borrows SET due_at = DATE_ADD(DATE(borrowed_at), INTERVAL 14 DAY) WHERE due_at IS NULL;

INSERT INTO books (title, author, category, copies) VALUES
('Principles of Marketing', 'Philip Kotler', 'Business', 2),
('A Brief History of Time', 'Stephen Hawking', 'Science', 2),
('Sapiens', 'Yuval Noah Harari', 'History', 3),
('Atomic Habits', 'James Clear', 'Self-Development', 2);
