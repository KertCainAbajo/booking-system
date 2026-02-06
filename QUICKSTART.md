# Quick Start Guide - Auto Service Booking System

## 🚀 Get Started in 3 Steps

### Step 1: Configure Database
Open `.env` and set your MySQL password:
```env
DB_PASSWORD=your_password_here
```

### Step 2: Setup Database
```bash
php artisan migrate:fresh --seed
```

### Step 3: Run Server
```bash
php artisan serve
```

Visit: **http://localhost:8000**

## 🔐 Login Accounts

| Role | Email | Password |
|------|-------|----------|
| Customer | customer@autoservice.com | customer123 |
| Staff | staff@autoservice.com | staff123 |
| Owner | owner@autoservice.com | owner123 |
| Admin | admin@autoservice.com | admin123 |

## 🎯 Test the Booking Flow

1. Login as **customer@autoservice.com** (customer123)
2. Click **"Book Service"**
3. Click **"+ Add Vehicle"**
4. Fill in vehicle details:
   - Make: Toyota
   - Model: Vios
   - Year: 2020
   - Plate: ABC1234
5. Click **"Save Vehicle"**
6. Select **"Preventive Maintenance"**
7. Check **"Engine oil change"** and **"Oil filter replacement"**
8. Choose date (tomorrow) and time
9. Click **"Submit Booking"**

Your booking is created! 🎉

## 📁 Key Files

### Models
- `app/Models/Booking.php` - Booking model
- `app/Models/Service.php` - Services
- `app/Models/Customer.php` - Customers

### Components
- `app/Livewire/Customer/BookingForm.php` - Booking logic
- `app/Livewire/Staff/BookingCalendar.php` - Staff calendar

### Views
- `resources/views/livewire/customer/booking-form.blade.php`
- `resources/views/customer/dashboard.blade.php`

### Database
- `database/seeders/ServiceSeeder.php` - 31 services
- `database/migrations/*_create_bookings_table.php`

## 🛠️ Common Commands

```bash
# Clear caches
php artisan optimize:clear

# Rebuild database
php artisan migrate:fresh --seed

# Build frontend
npm run build

# Watch changes
npm run dev
```

## 📊 What's Included

- ✅ **31 Services** across 4 categories
- ✅ **11 Inventory items**
- ✅ **4 User roles**
- ✅ **Complete booking system**
- ✅ **Responsive UI** with Tailwind CSS
- ✅ **Laravel Pulse** monitoring
- ✅ **Role-based security**

## 🔍 Database Tables

- users, roles, customers
- vehicles, bookings, booking_services
- service_categories, services, service_inventory
- inventory_items, payments, booking_status_logs

## 📍 Routes

**Customer:**
- `/customer/booking` - New booking
- `/customer/tracker` - Track services
- `/customer/history` - Past bookings

**Staff:**
- `/staff/dashboard` - Calendar view

**Owner:**
- `/owner/dashboard` - Analytics

**Admin:**
- `/admin/dashboard` - System overview
- `/admin/users` - Manage users
- `/admin/services` - Manage services

## ⚠️ Troubleshooting

**Error: "Access denied"**
→ Update DB_PASSWORD in `.env`

**Error: "Database not found"**
→ Create database: `CREATE DATABASE shop_booking_system;`

**Error: "Frontend not loading"**
→ Run: `npm run build`

## 📝 Next Steps

Complete these component views:
1. Booking tracker
2. Service history
3. Staff booking details
4. Invoice generator
5. Business owner reports
6. Admin panels

All backend logic is ready - just need the frontend templates!

---

**You're all set! Happy coding! 🚀**
