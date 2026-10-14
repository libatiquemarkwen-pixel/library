# Library of the PCNL

Philippine College of Northwestern Luzon

A simple library system for my Web Application Development final project.
Students can browse and borrow books, and the librarian (admin) manages the books.

- **Live site:** https://[firstname-lastname].infinityfreeapp.com
- **GitHub:** https://github.com/[your-username]/pcnl-library
- **Student:** [Firstname Lastname]

## Features
- Register, login, logout (passwords hashed with `password_hash()`)
- Two roles: **admin** (librarian) and **user** (student)
- **Admin:** add, edit, delete books (CRUD) and view all borrow records
- **User:** search books, borrow a book, see "My Books", return a book
- Number of copies goes down when borrowed and up when returned
- Borrow button works with JavaScript fetch (no page reload)
- Live search on the books page
- Validation on the client (JavaScript / HTML5) and on the server (PHP)
- Responsive, professional navy-and-gold design using Bootstrap 5
- Landing page, login/register screens and library imagery (`assets/img/`)

## Technologies
HTML5, CSS3, Bootstrap 5, JavaScript, PHP, MySQL

## Security
- Prepared statements (PDO) to stop SQL injection
- `htmlspecialchars()` on output to stop XSS
- Passwords hashed, session ID regenerated at login
- Admin pages check the role in PHP, and users can only return their own books

## Test accounts

| Role  | Username | Password |
|-------|----------|----------|
| Admin | admin    | admin123 |
| User  | maria    | user123  |

## Upgrading an existing database (v4)
Version 4 adds book **categories** and loan **due dates** (14 days). If you already imported the old `schema.sql`, run `database/upgrade_v4.sql` once in phpMyAdmin. For a brand-new install just import `database/schema.sql`.

## How to set up
1. Create a MySQL database (phpMyAdmin).
2. Import `database/schema.sql`.
3. Open `config.php` and type your database host, name, username and password.
4. Upload all files to the `htdocs` folder of your hosting (or XAMPP `htdocs` for local testing).
5. Open the site and login.

## Database
- `users` (id, username, password, role)
- `books` (id, title, author, copies)
- `borrows` (id, user_id, book_id, borrowed_at, returned_at)

One user can have many borrows and one book can have many borrows.
`returned_at` is NULL while the book is still borrowed.
Deleting a user or a book also deletes its borrow records (ON DELETE CASCADE).

## Files
```
index.php            home (guests)          dashboard.php   member / librarian dashboard
about.php            about the library      books.php       catalog (book cards)
login.php register.php logout.php           book_form.php book_delete.php   (admin)
borrow.php return.php my_books.php records.php
includes/            config.php, header.php, footer.php
assets/css|js|img/   style.css, script.js, logo and images
database/schema.sql  tables and sample data
```
Contact details (address, hours, email, phone) are set in `includes/config.php` under `$library`; they appear on the About page and footer once filled in.

## Using your own photos / official logo
- Save a real library photo as `assets/img/library-hero.jpg` and it will automatically replace the illustration (hero, login, register, home page).
- To use the official PCNL seal, replace `assets/img/pcnl-logo.png` with your logo (keep the name, or update the `<img src>` in `header.php` / `footer.php` / `login.php` / `register.php`).
