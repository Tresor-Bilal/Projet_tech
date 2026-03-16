
# EventMatch – Project Tech

## Project Overview

EventMatch is a web platform designed to help users discover, explore, and participate in local events. The platform allows users to:

* Browse upcoming events with images and details.
* Register and log in to create a personalized experience.
* View events on a map and access detailed pages.
* Interact with features such as a chatbot for recommendations.

The project is built with **PHP, MySQL, and Bootstrap**, with front-end enhancements for a professional look and responsive design.

---

## Features

* User authentication (register/login)
* Event listings with carousel and cards
* Event details page with Unsplash images
* Interactive map of events
* Contact/chatbot integration
* Responsive, professional UI

---

## Getting Started

### Prerequisites

* PHP >= 7.4
* MySQL or MariaDB
* Apache / Nginx server (MAMP/XAMPP for local setup)
* Composer (optional for future dependency management)

---

### Installation

1. **Clone the repository**

```bash
git clone https://github.com/Tresor-Bilal/Projet_tech.git
cd Projet_tech
```

2. **Import the database**

* Import `db/eventmatch.sql` into your MySQL server:

```bash
# Using MySQL CLI
mysql -u your_user -p your_database < db/eventmatch.sql
```

* Or use **phpMyAdmin / Adminer** to import the SQL file.

3. **Configure database connection**

* Edit `db.php` (or `config.php`) and update your database credentials:

```php
$host = 'localhost';
$db   = 'your_database';
$user = 'your_user';
$pass = 'your_password';
```

4. **Start your local server**

* Open `index.php` in your browser via MAMP/XAMPP or `php -S localhost:8000` in terminal.

---

### Usage

* Register a new account or log in using existing credentials.
* Browse the home page carousel for featured events.
* Click on an event card to view details and map location.
* Explore the chatbot for event recommendations.

---

### Project Structure

```
Projet_tech/
│
├─ CSS/                # Stylesheets
├─ db/                 # Database export (eventmatch.sql)
├─ img/                # Images
├─ includes/           # Header and footer templates
├─ data/               # JSON event data
├─ index.php           # Homepage
├─ events.php          # Event listings
├─ event-details.php   # Event detail page
├─ register.php        # User registration
├─ login.php           # User login
├─ logout.php          # Logout script
└─ README.md           # Project documentation
```

---

### Contributing

We welcome contributions! To contribute:

1. Fork the repository
2. Create a new branch for your feature:

```bash
git checkout -b feature-name
```

3. Make your changes
4. Commit your changes:

```bash
git commit -m "Add description of your feature"
```

5. Push your branch and create a Pull Request

---

### License

This project is open-source. Feel free to reuse and modify according to your needs.

---

### Contact

Author: **Mbungu Tresor Bilal**
GitHub: [https://github.com/Tresor-Bilal](https://github.com/Tresor-Bilal)

---

