<div align="center">

# 🍽️ Lazzat (لذّت)
### Pakistani Recipe & Meal Planning Web Application

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-3.x-003B57?style=for-the-badge&logo=sqlite&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

*A full-stack web application for discovering Pakistani recipes, planning weekly meals, managing grocery lists, and getting AI-powered cooking assistance.*

**Course:** CSC336 Web Technologies &nbsp;|&nbsp; **COMSATS University Islamabad, Vehari Campus**  
**Student:** Muniba Ishfaq — CIIT/SP24-BSSE-024/VHR &nbsp;|&nbsp; **Instructor:** Yasmeen Jana &nbsp;|&nbsp; **Semester:** Spring 2026

</div>

---

## 📖 About

**Lazzat** (Urdu: لذّت — meaning *taste* or *flavour*) is a bilingual (English/Urdu) web application built to address the lack of dedicated digital platforms for Pakistani cuisine. It combines recipe discovery, weekly meal planning, grocery list management, and AI-assisted cooking guidance in one place.

---

## ✨ Features

| Feature | Description |
|---|---|
| 🔍 **Recipe Discovery** | Fetches 500+ Pakistani recipes from TheMealDB & Spoonacular APIs |
| 🗓️ **Weekly Meal Planner** | Assign recipes to 7-day breakfast / lunch / dinner grid |
| 🛒 **Grocery List** | Auto-generated from meal plan, with PDF export |
| 🤖 **Lazzat AI (4-in-1)** | Ask recipes, get meal plans, suggest by ingredient, nutrition help |
| 🌐 **Bilingual** | Full English ↔ Urdu toggle with RTL text rendering |
| 🔐 **Authentication** | Register, login, session-based auth with bcrypt password hashing |

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11.x (PHP 8.2+), MVC architecture
- **Database:** SQLite 3.x
- **Frontend:** Laravel Blade, Vanilla JavaScript (ES2020+), Custom CSS
- **APIs:** TheMealDB, Spoonacular, Gemini (gemini-2.5-flash)
- **Tools:** Composer, Git, Visual Studio Code

---

## ⚙️ Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Git

### Step 1 — Clone the Repository
```bash
git clone https://github.com/Muniba-ishfaq33/Lazzat-Recipe-and-Meal-Planner-Laravel-WebApp.git
cd Lazzat-Recipe-and-Meal-Planner-Laravel-WebApp
```

### Step 2 — Install PHP Dependencies
```bash
composer install
```

### Step 3 — Create Environment File
```bash
cp .env.example .env
```

### Step 4 — Generate Application Key
```bash
php artisan key:generate
```

### Step 5 — Add Your API Keys
Open the `.env` file and fill in:
```env
GEMINI_API_KEY=your_api_key_here
GEMINI_MODEL=gemini-2.5-flash
```

### Step 6 — Set Up the Database
```bash
touch database/database.sqlite
php artisan migrate
```

### Step 7 — Run the Development Server
```bash
php artisan serve
```

Open your browser at **http://localhost:8000** 🎉

---

## 📁 Project Structure
