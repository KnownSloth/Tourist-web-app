# Travel Planning Web Application

## 📌 Project Overview
This project is a web application prototype designed to assist users in planning travels. 
It was developed as a Bachelor's Thesis project for a Database Administration specialization. 
The application allows users to search for tourist attractions and accommodations using geographical data from the public API and manage their travel plans.

## 🛠 Tech Stack
* **Backend:** PHP 8.0+
* **Database:** PostgreSQL
* **Frontend:** HTML5, CSS3 (Custom styles)
* **External APIs:** Overpass API (OpenStreetMap) for geographical data extraction

## ✨ Key Features
* **User Authentication:** Secure registration and login system (`login.php`, `rejestracja.php`).
* **Geographical Data Integration:** Fetches real-time data about attractions (`atrakcje.php`) and accommodations (`noclegi.php`) via OpenStreetMap.
* **Travel Planning:** Tools to organize and save custom travel itineraries (`plan.php`).
* **Interactive Elements:** Integrated commenting system and data fetching (`api_comments.php`).

## 🚀 Local Setup Instructions

### Prerequisites
1.  **XAMPP** (with PHP 8.0 or higher)
2.  **PostgreSQL** and **pgAdmin 4**
3.  Active Internet connection (required for fetching map data)

### Step 1: Database Setup
1. Open **pgAdmin 4**.
2. Create a new database named: `turystyka`.
3. Right-click on the newly created database and select **Restore**.
4. Select the `database.sql` file provided in the repository and confirm.

### Step 2: Project Configuration
1. Clone or copy the project folder into your XAMPP htdocs directory: `C:\xampp\htdocs\turystyka`
2. Open the `db.php` file in your code editor.
3. Update the `$password` variable with your local PostgreSQL database password.

### Step 3: PHP Configuration (Crucial)
By default, XAMPP has the PostgreSQL extension disabled. You must enable it manually:
1. In the XAMPP Control Panel, click **Config** next to the Apache module and select **PHP (php.ini)**.
2. Press `Ctrl+F` and search for the following line: `;extension=pdo_pgsql`
3. **Remove the semicolon (;)** at the beginning of the line to uncomment it. It should look like this: `extension=pdo_pgsql`
4. Save the file and restart the Apache server (Stop -> Start).

## 🔐 Test Accounts
You can use the following pre-configured accounts to test the application functionalities:

| Role | Email | Password |
| **Admin** | admin@example.com | password |
| **Student** | student@example.com | password |
| **Reviewer** | recenzent@example.com | password |

## ⚠️ Troubleshooting & API Limitations

**Issue: Empty search results for Attractions or Accommodations.**
*Solution:* Refresh the page (F5) or try again in a few moments.

*Technical Note:* This application relies on the free, public Overpass API (OpenStreetMap). 
During periods of high load on external servers, requests may be temporarily rejected or return an empty list. 
This is not a bug in the application logic, but a strict limitation of the public API's rate limits.
