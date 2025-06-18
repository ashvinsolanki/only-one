# 🔐 Only One – Laravel Encrypted One-to-One Chat System

**Only One** is a secure, one-to-one encrypted chat system built using Laravel. This application allows users to connect and communicate privately with a single user by username. All messages are end-to-end encrypted and decrypted only on click, ensuring maximum privacy.

> 📌 Each user can connect with **only one other user** – no groups, no distractions, just focused, private conversation.

---

## ✨ Features

- 🔐 **End-to-End Encryption** – Messages are stored encrypted in the database
- 👁️ **Click to Decrypt** – Messages are shown encrypted until clicked
- 🧍 **One-to-One Connection** – Each user can chat with only one assigned partner
- 🔒 **Privacy-First** – No third-party tracking, open-source, and self-hosted
- 📩 **Live Messaging** – Real-time Laravel chat (Laravel Echo / Pusher optional)

---

## 🛠️ Tech Stack

- **Laravel 9+**
- **PHP 8.0+**
- **MySQL**
- **Blade (or optional Vue.js/Livewire for front-end)**
- **Encryption** using Laravel's built-in Crypt facade

---

## 🚀 Setup Instructions

```bash
git clone https://github.com/ashvinsolanki/only-one-chat.git
cd only-one-chat
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
