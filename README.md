# 🛒 Greeny — The Online Grocery Shop

> A full-stack online grocery shopping web application built with PHP, MySQL, and CSS.

---

## 📌 About the Project

**Greeny** is an online grocery store where users can browse products, manage their cart, and place orders. It includes a dedicated admin panel for managing products and orders, along with a clean and responsive user interface.

---

## 🚀 Features

- 🏠 **Home Page** — Attractive landing page with product highlights using Swiper.js carousel
- 🔐 **User Authentication** — Register, Login, and Logout functionality with session management
- 🛍️ **Product Browsing** — Browse and view grocery products
- 🗂️ **Admin Panel** — Manage products, orders, and store data
- 📦 **Database Integration** — MySQL database with PHP backend
- 🎨 **Responsive Design** — Custom CSS with Google Fonts and Swiper.js for smooth UI

---

## 🛠️ Tech Stack

| Technology | Usage |
|------------|-------|
| PHP | Backend logic & session handling |
| MySQL | Database |
| CSS | Styling & layout |
| HTML | Markup |
| Swiper.js | Product carousels / sliders |
| Google Fonts | Typography (Inter, Playfair Display, etc.) |
| Composer | PHP dependency management |

---

## 📁 Project Structure

```
GREENY---THE-ONLINE-GROCERY-SHOP/
│
├── admin/          # Admin panel pages
├── api/            # API endpoints
├── assets/         # Images and static assets
├── config/         # Database connection (db_connect.php)
├── css/            # Stylesheets
├── database/       # SQL schema / seed files
├── includes/       # Reusable PHP components (header, footer, etc.)
├── others/         # Miscellaneous files
├── pages/          # Page-specific PHP files (home, products, etc.)
│
├── index.php       # Main entry point
├── login.php       # User login
├── register.php    # User registration
├── logout.php      # Session logout
├── import.php      # Data import utility
├── composer.json   # PHP dependencies
├── .env.example    # Environment variable template
└── .gitignore
```

---

## 🔑 Usage

- Visit the home page to browse available grocery products
- Register a new account or log in with existing credentials
- Add products to your cart and proceed to checkout
- Admin users can manage inventory and orders via the `/admin` panel

---

## 🌐 Pages Overview

| Page | File | Description |
|------|------|-------------|
| Home | `index.php` | Landing page with featured products |
| Login | `login.php` | User login |
| Register | `register.php` | New user registration |
| Logout | `logout.php` | Session termination |
| Admin | `admin/` | Admin dashboard |

---

## 👩‍💻 Author

**Tanisha Vaghani (0408-tannu)**

- GitHub: [@0408-tannu](https://github.com/0408-tannu)

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

<p align="center">Made with 💚 by Tanisha</p>
