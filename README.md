# AlUla Vision 2030 — Tourism Platform

**Course:** IS337 — Application Development
**Institution:** Imam Mohammad Ibn Saud Islamic University (IMSIU)
**College:** Computer and Information Sciences (CCIS)
**Department:** Information Systems
**Semester:** 2nd 2026-27

---

## Project Description

A Vision 2030–themed tourism web application for **AlUla**, one of Saudi Arabia's most important cultural and tourism destinations. The platform allows visitors to:

- Explore historical and natural sites in AlUla
- Register accounts and book guided tours
- View a multimedia gallery
- Submit and read feedback from other visitors
- Get personalized recommendations through an AI-powered chatbot

This project aligns with Saudi Vision 2030 pillars: **Tourism Growth**, **Culture & Heritage Promotion**, and **Digital Transformation**.

---

## Technologies Used

| Layer | Technology |
| --- | --- |
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP |
| Database | MySQL |
| AI Feature | JavaScript-based Bilingual Chatbot |
| Languages | Arabic (RTL) + English with toggle |

---

## Team Members and Task Distribution

| # | Name | Student ID | Responsibilities |
| --- | --- | --- | --- |
| 1 | Abdulaziz Aldhaif | _______ | Project setup, Home page, About page, README, integration |
| 2 | _______________ | _______ | Tourism page, Gallery, Booking page, Slideshow JS |
| 3 | _______________ | _______ | Register, Login, Feedback, Database design, PHP backend |

> Each member is responsible for the AI/Chatbot part on their assigned page. Final integration is done together.

---

## How to Run the Project Locally

1. Install **XAMPP** (Apache + MySQL + PHP) — https://www.apachefriends.org
2. Place this project folder inside `xampp/htdocs/`
3. Open phpMyAdmin → create a new database named `alula_db`
4. Import `database/alula_db.sql` into the database
5. Start Apache and MySQL from XAMPP Control Panel
6. Open browser → `http://localhost/alula-vision2030/
├── index.html        (Home)
├── about.html        (About Us + Slideshow)
├── tourism.html      (AlUla destinations)
├── gallery.html      (Photos & Videos)
├── chatbot.html      (AI assistant)
├── register.php      (Sign up — self-validating)
├── login.php         (Sign in — self-validating)
├── booking.php       (Tour booking — registered users only)
├── feedback.php      (User feedback + live list from DB)
├── logout.php        (End session)
├── css/              (Stylesheets)
├── js/               (JavaScript files)
├── php/              (db_connect + shared header/footer)
├── database/         (SQL schema)
├── images/           (Logos, places, gallery)
└── videos/           (Gallery videos)
```

---

## Plagiarism Declaration

We declare that this project is our own work and we own the copyright of it with no copyright violation or plagiarism from other resources.
