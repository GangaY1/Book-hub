# **Book Hub Management System**

## **Overview**
The **Book Hub Management System** is a web-based application designed to streamline the management of books, users, and orders for a book store or library. It provides functionalities for admins to manage books and users, allows customers to browse books and place orders, and provides delivery personnel with tools to track and update deliveries. Built with **HTML**, **CSS**, **JavaScript**, **PHP**, and **MySQL**, this system offers an efficient and user-friendly experience for all stakeholders.

---

## **Features**

### **Admin Dashboard**
- Manage books (CRUD operations: Create, Read, Update, Delete).
- Manage users (customers and staff).
- Track order statuses and shipment schedules.
- Generate reports on sales, orders, and inventory.
- View real-time data on user activities and book orders.

### **Customer Dashboard**
- Browse and search for books by categories, authors, or titles.
- Place orders for books.
- Track order status and view order history.
- Manage profile and contact information.
- View book details, including prices and availability.

### **Delivery Personnel Dashboard**
- View assigned deliveries.
- Update delivery statuses (pending/delivered).
- Track the progress of deliveries.

---

## **Technologies Used**
- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Tools**: XAMPP/WAMP for local server setup

---

## **Database Setup**
1. Open **phpMyAdmin** and create a new database named `book_hub_management`.
2. Import the `database.sql` file from the project folder into the newly created database.

## **Database Configuration**
1. Open the `config.php` file located in the root directory of the project.
2. Update the database connection details to match your local environment:
   ```php
   $host = 'localhost';
   $username = 'root';  // Your MySQL username
   $password = '';      // Your MySQL password (leave blank for XAMPP default)
   $database = 'book_hub_management';
