# BuyGoods

BuyGoods is a database-driven e-commerce website developed using PHP, MySQL, HTML, CSS, and JavaScript.

## Features

- User registration and login
- Password hashing and verification
- Product listing
- Product images and stock information
- Buy Now functionality
- Billing information collection
- Payment method selection
- Automatic stock reduction after purchase
- Order summary
- Database-driven product management

## Technologies Used

- HTML
- CSS
- JavaScript
- PHP
- MySQL
- XAMPP
- Git & GitHub

## Project Flow

1. User views available products.
2. User selects a product using **Buy Now**.
3. User logs into their account.
4. The selected product is displayed on the billing page.
5. User enters billing information and selects a payment method.
6. The order is stored in the database.
7. The purchased quantity is deducted from product stock.
8. The user is redirected to the order summary.

## Database

The project uses MySQL for storing:

- User information
- Product information
- Billing/order information

## Local Setup

This project is currently designed to run using XAMPP.

Place the project folder inside:

`C:\xampp\htdocs\`

Then start:

- Apache
- MySQL

Open the project through:

`http://localhost/buygoods1/`

## Security

The project uses:

- Prepared SQL statements
- Password hashing with `password_hash()`
- Password verification with `password_verify()`
- Session-based authentication
- `.gitignore` to prevent database connection credentials from being uploaded

## Project Status

The basic e-commerce purchase workflow has been implemented and tested successfully.

More features and improvements will be added in future development.
