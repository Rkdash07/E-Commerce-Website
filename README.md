#Meesho style E-Commerce Website
## ✨ Key Highlights

- 🌐 **Fully Responsive UI** — Works on mobile, tablet & desktop  
- 🛍️ **Complete Shopping Flow** — Browse → Add to Cart → Checkout  
- 🔒 **Secure Authentication** — Login, Register, Role-Based Access  
- 📦 **Admin Dashboard** — Manage Products, Categories, Users, Orders  
- ⚡ **Fast Loading UX** — Optimized components & lightweight assets  
- 💾 **MySQL / JSON Database Support** — Easy to configure & modify  
- 📐 **Clean Modular Codebase** — Easy to understand and extend  
- 🎨 **Modern UI Design** — Attractive layouts, icons & transitions  

---

## 🔥 Additional Features

- 📑 **Product Details Page** with dynamic descriptions  
- ❤️ **Wishlist functionality** (optional / expandable)  
- 🧾 **Order Summary & Invoice view**  
- 🔍 **Search Bar & Filter Options**  
- 🏷️ **Category-wise Product Browsing**  
- 🛠️ **Admin CRUD for Products & Categories**  
- 📊 **Sales Analytics (Admin Panel)** (if available / optional)  
- 🌙 **Dark Mode Support** (you can add later)  
- ✉️ **Contact / Support Page**  

---

## 🧰 Tech Stack

- **Frontend:** HTML, CSS, SCSS, JavaScript (or React depending on your setup)  
- **Backend:** Node.js / Express or PHP (depends on admin folder config)  
- **Database:** MySQL (SQL file available in `/databases/` folder)  
- **Build Tools:** Webpack, Babel (optional)  
- **Version Control:** Git & GitHub  

---

## 🚀 Getting Started

### 1️⃣ Clone the Repository  
```bash
git clone https://github.com/Rkdash07/E-Commerce-Website.git
cd E-Commerce-Website
```

### 2️⃣ Setup Database  
Import the SQL file found in:  
```
/databases/ecommerce.sql
```

Using MySQL/MariaDB:
```bash
mysql -u your_user -p your_database < databases/ecommerce.sql
```

Update DB credentials in your backend config (`.env` or `config.php`).

---

## ⚙️ Backend Setup

### 3️⃣ Install Dependencies  
If backend is Node.js:
```bash
cd admin
npm install
```

If backend is PHP:  
Make sure you have XAMPP/WAMP/Laragon installed.

### 4️⃣ Start the Backend Server  

Node.js:
```bash
npm start
```

PHP:
```bash
php -S localhost:5000 -t public
```

---

## 🎨 Frontend Setup

### 5️⃣ Install Frontend Dependencies  
```bash
cd ../client
npm install
npm run dev     # or npm start
```

### 6️⃣ Open the App  
Frontend:  
```
http://localhost:3000
```

Admin Panel:  
```
http://localhost:5000/admin
```

---
