# PC-Adviser – Detailed System Documentation

Developed by **Malek Ahmad Abdel Hadi Wahdan**  
Final Project – Orange Coding Academy

PC-Adviser is an AI-integrated e-commerce platform developed as a final project at Orange Coding Academy. The platform not only offers full e-commerce functionality but also helps users choose the perfect PC setup using a chatbot powered by Hugging Face AI.

---

## Home Page

The main landing page includes:
- Featured products and categories  
- Promotional banners  
- Quick access to the AI chatbot  
- About section describing the platform's mission  
- Authentication links (Login/Register)

---

## Authentication System

### Role-Based Access Control
Authentication is handled using Laravel Guards and middleware with three distinct user roles:
- **User**: Regular customer  
- **Admin**: Store manager with control over inventory and orders  
- **Super Admin**: Has complete system control including admin management  

### Login Access
- **User Login**: `/login`  
- **Admin/Super Admin Login**: `/admin/login`  

Users are redirected to their respective dashboards. Middleware protects all routes according to role.

---

## User Dashboard

### My Profile
- View and update name, email, password, phone, address, city  

### Orders
- View past and current orders with status indicators  

### Cart & Wishlist
- Manage items before checkout  
- Persistent cart with quantity control  

### Checkout
- Stripe-powered payment system  
- Choose from available shipping methods  

### AI Chatbot (PC Adviser)
- Accessible from home or dashboard  
- Asks questions to understand user needs  
- Recommends PCs based on preferences

---

## Admin Dashboard

1. **Dashboard Overview**  
  

2. **Product Management**  
   - Add/edit/delete products  
   - Manage stock, pricing, brand/category, and status  
   - Upload thumbnail and additional images  

3. **Category Management**  
   - Add/edit/delete categories  
   - Add images  
   - Mark featured categories  

4. **Brand Management**  
   - Add/edit/delete brands  
  

5. **Order Management**  
   - View all orders  
   - Update order shipping statuses  
   - View order items 

6. **Shipping Methods**  
   - Add/edit/delete shipping methods  
   - Set price and estimated delivery time  

7. **Reviews**  
   - View and moderate product reviews  
   - Approve or reject submissions  

---

## Super Admin Capabilities

- Full Admin privileges  
- Manage Admin accounts (create, update, delete)  


---

## Technical Stack

- **Frontend**: Blade, HTML, CSS, JavaScript  
- **Backend**: Laravel (PHP)  
- **Database**: MySQL  
- **Payment Integration**: Stripe API  
- **AI Integration**: Hugging Face API  
- **Security**: hashed passwords, CSRF, middleware  

---

## Database Schema Overview

### Core Tables
- **users**: Customer details and credentials  
- **admins**: Admin/super admin accounts with role control  
- **categories**: Product categories  
- **brands**: Product brand information  
- **products**: Product listing with stock and status  
- **product_images**: Multiple images per product  
- **orders**: Order summary, billing, shipping  
- **order_items**: Individual items in each order  
- **carts**: User’s current cart with quantity  
- **wishlists**: User’s wishlist (composite key for uniqueness)  
- **reviews**: User-submitted product reviews  
- **payments**: Tracks payment method and status  
- **shipping_methods**: Delivery options and pricing  

### Key Relationships
- **products** → categories, brands  
- **order_items** → orders, products  
- **orders** → users, shipping_methods  
- **payments** → orders  
- **reviews** → users, products  
- **carts & wishlists** → Composite keys: (user_id, product_id)

---



---

## How to Start Using the PC-Adviser Platform

1. Clone the repo from GitHub  
2. Run `composer install` to install dependencies  
3. Run `php artisan migrate` to create tables  
4. Add your `.env` file and configure DB and Stripe keys  
5. Run `php artisan serve` to start the server  
6. Access the platform at [http://localhost:8000](http://localhost:8000)  
7. Admin login page: [http://localhost:8000/admin/login](http://localhost:8000/admin/login)

---

## Project Credits

This platform was designed and developed by **Malek Ahmad Abdel Hadi Wahdan** as part of the Orange Coding Academy final project.

---

## Figma link :
https://www.figma.com/design/FLmkuhKJrTE5zhJ5eOhjc7/Untitled?node-id=0-1&t=051EaTbZFbExMB4F-1
---
## Schema : 
https://dbdiagram.io/d/68236ff95b2fc4582f6da3a6
