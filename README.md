<div align="center">

# ✦ Dialo

### *Your everyday AI chat companion — powered by Google Gemini*

![HTML](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Gemini](https://img.shields.io/badge/Google%20Gemini-4285F4?style=for-the-badge&logo=google&logoColor=white)

> 💬 Ask it anything. Write better. Think clearer. Dialo is your personal AI assistant — elegant, fast, and always ready to chat.

</div>

---

## 📋 Table of Contents

- [✨ About](#-about)
- [🎯 Features](#-features)
- [🖼️ Project Structure](#️-project-structure)
- [⚙️ Setup & Installation](#️-setup--installation)
- [🔑 API Key Configuration](#-api-key-configuration)
- [🗄️ Database Setup](#️-database-setup)
- [🚀 Usage](#-usage)
- [🛡️ Quota & Rate Limiting](#️-quota--rate-limiting)
- [🎨 Tech Stack](#-tech-stack)
- [💡 GitHub Description Suggestions](#-github-description-suggestions)

---

## ✨ About

**Dialo** is a full-stack AI chat web application built with vanilla HTML, CSS, JavaScript and PHP. It connects to **Google's Gemini API** to deliver intelligent, conversational AI responses in a beautifully designed interface.

Built as a learning project, Dialo demonstrates how to integrate a modern LLM API into a complete web app — complete with user authentication, session management, persistent chat history, dark/light mode, and graceful error handling.

---

## 🎯 Features

| Feature | Description |
|---|---|
| 🤖 **AI Chat** | Real conversations powered by Google Gemini |
| 🔐 **Auth System** | Secure signup & login with bcrypt-hashed passwords |
| 💾 **Chat History** | Conversations saved locally per browser session |
| 🌙 **Dark / Light Mode** | Smooth theme toggle with system preference detection |
| 📱 **Fully Responsive** | Works on mobile, tablet and desktop |
| ⚡ **Model Fallbacks** | Auto-retries with backup models if one is overloaded |
| ⏳ **Quota Banner** | Friendly message with reset time when API limit is hit |
| 💬 **Multi-turn Memory** | Keeps last 20 messages as context per conversation |
| ✨ **Micro-animations** | Typing indicators, smooth transitions & entrance effects |
| 🎨 **Premium Design** | Custom color palette, Google Fonts & glassmorphism |

---

## 🖼️ Project Structure

```
Dialo/
│
├── 📄 index.html          # Landing page — hero, features & CTA
├── 📄 chat.html           # Main chat interface (requires login)
├── 📄 login-signup.html   # Auth page — login & register forms
│
├── 🐘 chat_api.php        # API handler — calls Gemini, returns reply
├── 🐘 login.php           # Login endpoint
├── 🐘 signup.php          # Signup endpoint
├── 🐘 logout.php          # Session destroy
├── 🐘 whoami.php          # Returns logged-in user info (JSON)
│
├── 🐘 config.php          # MySQL connection + session_start()
├── 🐘 api_config.php      # ⭐ PUT YOUR GEMINI API KEY HERE
│
└── 🗄️ opal_mind.sql       # Database schema — run once to set up
```

---

## ⚙️ Setup & Installation

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8+)
- A free [Google Gemini API key](https://aistudio.google.com/app/apikey)

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/YOUR_USERNAME/dialo.git
```

**2. Move to your XAMPP web root**
```
Copy the project folder into:  C:\xampp\htdocs\Dialo\
```

**3. Import the database**

Open **phpMyAdmin** → click **Import** → select `opal_mind.sql` → click **Go**.

Or run via terminal:
```bash
mysql -u root < opal_mind.sql
```

**4. Add your Gemini API key**

Open `api_config.php` and paste your key:
```php
define('GEMINI_API_KEY', 'YOUR_API_KEY_HERE');
```

**5. Start XAMPP**

Launch **Apache** and **MySQL** from the XAMPP Control Panel.

**6. Open in browser**
```
http://localhost/Dialo/
```

---

## 🔑 API Key Configuration

All API settings live in one file: **`api_config.php`**

```php
// Your Google Gemini API key — get one free at:
// https://aistudio.google.com/app/apikey
define('GEMINI_API_KEY', 'YOUR_KEY_HERE');

// The model to use (auto-fallback is built in)
define('GEMINI_MODEL', 'gemini-flash-lite-latest');
```

> ⚠️ **Never commit your API key to GitHub.** Add `api_config.php` to your `.gitignore`.

**Recommended `.gitignore`:**
```gitignore
api_config.php
config.php
```

---

## 🗄️ Database Setup

The SQL file creates one database and one table:

```sql
CREATE DATABASE IF NOT EXISTS opal_mind;

CREATE TABLE users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  full_name     VARCHAR(120)  NOT NULL,
  email         VARCHAR(190)  NOT NULL UNIQUE,
  password_hash VARCHAR(255)  NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Passwords are stored as **bcrypt hashes** — never plain text.

---

## 🚀 Usage

1. Visit `http://localhost/Dialo/`
2. Click **Get started** → create a free account
3. Log in and start chatting with Dialo 🎉
4. Use the **sidebar** to start new chats or revisit old ones
5. Toggle **🌙 dark mode** from the top-right button
6. Press **Enter** to send · **Shift + Enter** for a new line

---

## 🛡️ Quota & Rate Limiting

Dialo handles Google API limits gracefully:

- 🔄 **Auto-fallback** — if the primary model is overloaded or unavailable, it automatically tries backup Gemini models
- ⏳ **Quota banner** — when the daily free quota is exhausted, a friendly banner appears above the chat box showing exactly **when the quota resets** (Google resets free-tier limits at midnight Pacific Time daily)

**Fallback model chain:**
```
gemini-flash-lite-latest  →  gemini-3.6-flash  →  gemini-3.5-flash
```

---

## 🎨 Tech Stack

| Layer | Technology |
|---|---|
| 🖼️ **Frontend** | HTML5, Vanilla CSS, Vanilla JavaScript |
| 🐘 **Backend** | PHP 8+ |
| 🗄️ **Database** | MySQL via XAMPP |
| 🤖 **AI Model** | Google Gemini API (v1beta) |
| 🔤 **Fonts** | Fraunces · Plus Jakarta Sans (Google Fonts) |
| 🖥️ **Server** | Apache (XAMPP) |

---

## 💡 GitHub Description Suggestions

Pick one for your repo's **About** description:

| # | Suggestion |
|---|---|
| 1 | 💬 A sleek AI chat app powered by Google Gemini, built with PHP & vanilla JS |
| 2 | 🤖 Full-stack AI chatbot with auth, dark mode & smart model fallbacks |
| 3 | ✨ Your personal AI companion — elegant design meets Google Gemini intelligence |
| 4 | 🧠 Chat with Gemini AI through a beautiful, responsive PHP web app |
| 5 | 🌙 Dark-mode-ready AI chat platform with user accounts & conversation history |
| 6 | 🚀 Minimal, fast AI chatbot with PHP backend and Gemini API integration |
| 7 | 💡 Ask anything — a full-stack AI chat app built for students & creators |
| 8 | 🎨 Premium-designed AI chat web app with login system & Gemini API |
| 9 | 🔐 Secure, authenticated AI chat app — Google Gemini + PHP + MySQL |
| 10 | ⚡ AI chatbot with auto model fallbacks, quota handling & smooth animations |
| 11 | 🌐 A self-hosted AI chat assistant built on XAMPP with Google Gemini |
| 12 | 📚 Learning project — full-stack AI chat with auth, Gemini API & local history |
| 13 | 💬 Conversational AI web app with multi-turn memory & responsive UI |
| 14 | 🤝 Chat smarter — Gemini-powered assistant with beautiful PHP web frontend |
| 15 | 🛡️ Production-ready AI chat app with quota detection & graceful error handling |
| 16 | 🎯 Simple, beautiful AI chat interface with user login & Google Gemini backend |
| 17 | 🌟 Full-stack chatbot project — PHP sessions, MySQL auth & Gemini API |
| 18 | 🔥 Modern AI chat webapp: dark mode, animations, fallback models & quota alerts |
| 19 | 🧩 Plug-and-play AI chatbot — add your Gemini key and start chatting instantly |
| 20 | 💎 Polished AI chat application with premium design & real Gemini AI replies |

---

<div align="center">

Made with ❤️ &nbsp;·&nbsp; Powered by [Google Gemini](https://ai.google.dev/) &nbsp;·&nbsp; Built on [XAMPP](https://www.apachefriends.org/)

</div>
