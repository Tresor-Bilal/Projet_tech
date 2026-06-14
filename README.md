# 🎉 EventMatch – Local Event Discovery Platform

## Project Overview

EventMatch is a web platform designed to help users discover, explore, and participate in local events through an intuitive and responsive interface.

The platform allows users to:

- Browse upcoming events with images and descriptions
- Register and log in to personalize their experience
- View event details and locations
- Discover events through an integrated chatbot
- Manage their user profile

The project is built using **PHP**, **MySQL**, and modern front-end technologies to provide a clean and user-friendly experience.

---

## ✨ Features

- User authentication (registration and login)
- Event listing system with cards and featured sections
- Event detail pages with images and descriptions
- Interactive event discovery
- Chatbot integration for recommendations
- User profile management
- Eventbrite API integration
- Responsive and modern UI

---

## 🛠️ Technologies Used

- PHP
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- Eventbrite API
- Apache / Nginx
- Git & GitHub

---

## 🚀 Getting Started

### Prerequisites

Before running the project, make sure you have:

- PHP >= 7.4
- MySQL or MariaDB
- Apache or Nginx server
- XAMPP / MAMP / Laragon (recommended for local development)
- A modern web browser

---

## ⚙️ Installation

### 1. Clone the repository

```bash
git clone https://github.com/Tresor-Bilal/EventMatch.git
cd EventMatch
```

---

### 2. Create the database

Open your MySQL server and create a database:

```sql
CREATE DATABASE eventmatch;
```

---

### 3. Import the SQL file

Import the database file located in:

```text
db/eventmatch.sql
```

Using MySQL CLI:

```bash
mysql -u your_user -p eventmatch < db/eventmatch.sql
```

Or import it manually using **phpMyAdmin** or **Adminer**.

---

### 4. Configure database connection

Edit the configuration file:

```text
config.php
```

Update your database credentials:

```php
$host = 'localhost';
$db   = 'eventmatch';
$user = 'your_user';
$pass = 'your_password';
```

---

### 5. Run the project

Place the project inside your web server directory:

- `htdocs/` for XAMPP
- `www/` for WAMP
- `Sites/` for MAMP

Then open:

```text
http://localhost/Projet_tech
```

---

## 📌 Usage

1. Register a new account or log in.
2. Browse featured events from the homepage.
3. Open event details pages for more information.
4. Explore recommendations using the chatbot.
5. Update your profile information.

---

## 📂 Project Structure

```text
Projet_tech/
│
├── CSS/                  # Stylesheets
├── data/                 # JSON event data
├── db/                   # Database export
├── img/                  # Images and assets
├── includes/             # Reusable templates
│
├── index.php             # Homepage
├── events.php            # Event listings
├── event-details.php     # Event details page
├── profile.php           # User profile
├── register.php          # User registration
├── login.php             # User login
├── logout.php            # Logout script
├── chatbot.html          # Chatbot interface
├── eventbrite.php        # Eventbrite integration
├── config.php            # Application configuration
├── db.php                # Database connection
├── functions.php         # Helper functions
└── README.md             # Project documentation
```

---

## 🔒 Security Notes

- User authentication system implemented
- Database credentials centralized in configuration files
- Sensitive API keys should be stored securely
- SQL database integration handled through PHP

---

## 🤝 Contributing

Contributions are welcome!

To contribute:

1. Fork the repository
2. Create a new branch

```bash
git checkout -b feature-name
```

3. Make your changes
4. Commit your work

```bash
git commit -m "Add new feature"
```

5. Push your branch

```bash
git push origin feature-name
```

6. Open a Pull Request

---

## 👨‍💻 Author

**Mbungu Tresor Bilal**

GitHub: https://github.com/Tresor-Bilal

---

## 📄 License

This project was developed for educational purposes.

