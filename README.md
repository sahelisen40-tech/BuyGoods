# BuyGoods

BuyGoods is a PHP and MySQL based e-commerce web application developed as a practical web development project.

The application allows users to register and log in, browse available products, view product details, purchase products, enter billing information, and generate an order summary.

## Features

- User registration and login
- Password hashing and password verification
- Session-based user authentication
- Product listing
- Product images
- Product price and stock display
- Product availability checking
- Billing information form
- Payment method selection
- Automatic stock reduction after purchase
- Order summary generation
- MySQL database integration
- Prepared SQL statements for database operations

## Technology Stack

- **Frontend:** HTML, CSS
- **Backend:** PHP
- **Database:** MySQL
- **Server:** Apache (XAMPP)
- **Database Management:** phpMyAdmin
- **Version Control:** Git and GitHub

## Project Structure

```text
BuyGoods/
│
├── billing.php
├── billing_insert.php
├── database.sql
├── login.php
├── loginpage.php
├── logout.php
├── main_body.php
├── menuhead.php
├── order_summary.php
├── picture_insert.php
├── register.php
├── resetpassword.php
├── side_menu.php
│
├── css/
│   └── Bootstrap CSS files
│
├── js/
│   └── Bootstrap JavaScript files
│
└── photo/
    └── Product images
```

Database

The project uses a MySQL database named:

buygoods1

Main tables include:

register

Stores registered user information.

Column Description
id Unique user ID
username User name
email User email
password Hashed password
products

Stores product information.

Column Description
id Unique product ID
name Product name
price Product price
image Product image filename
quantity Available stock
state Product state/location
billing

Stores completed order and billing information.

Column Description
id Unique order ID
client_id ID of the logged-in customer
fullname Customer name
email Customer email
address Delivery address
city Customer city
state Customer state
payment Selected payment method
date Order date
price Product price
item Purchased product
quantity Purchased quantity
How the Application Works
The user opens the BuyGoods product page.
Available products and their stock quantities are displayed.
The user selects Buy Now for a product.
If the user is not logged in, they are redirected to the login page.
After successful authentication, the selected product is passed to the billing page.
The user enters their billing information and selects a payment method.
The application retrieves the actual product information from the database.
The application checks whether sufficient stock is available.
The order is stored in the billing table.
The purchased quantity is deducted from the product stock.
The user is redirected to the order summary page.
Security Measures

The project includes several basic security practices:

Passwords are stored using PHP password_hash().
Passwords are verified using password_verify().
Prepared statements are used for database queries.
User identity is maintained using PHP sessions.
Product information such as price and name is retrieved from the database instead of trusting form values.
Database connection details are kept outside the GitHub repository using .gitignore.
Running the Project Locally
Requirements

Install:

XAMPP
PHP
MySQL
phpMyAdmin
Git
Setup
Clone the repository:
git clone https://github.com/sahelisen40-tech/BuyGoods.git
Copy the project into:
C:\xampp\htdocs\
Start Apache and MySQL from XAMPP Control Panel.
Create a MySQL database named:
buygoods1
Import the provided database.sql file into phpMyAdmin.
Create your local db_connection.php file with your MySQL configuration.
Open the project in your browser:
http://localhost/buygoods1/main_body.php
Important Note

The database connection file is intentionally excluded from GitHub because it contains local database configuration.

Create your own:

db_connection.php

with your local MySQL settings.

Future Improvements

Possible future improvements include:

Shopping cart with multiple products
Multiple-item checkout
Online payment gateway integration
Order history
Admin dashboard
Product search and filtering
Product categories
User profile management
Improved responsive design
Order status tracking
Author

Saheli Sen

B.Tech in Information Technology
Indian Institute of Engineering Science and Technology, Shibpur
