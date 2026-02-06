# Setup Instructions for Auto Service Booking System

## ⚠️ IMPORTANT FIRST STEP

Before running migrations, you MUST configure your MySQL password in the `.env` file!

### Step 1: Configure Database Password

Open `.env` file and update this line:

```env
DB_PASSWORD=your_mysql_password_here
```

Replace `your_mysql_password_here` with your actual MySQL root password.

### Step 2: Run Migrations

```bash
php artisan migrate:fresh --seed
```

This will:
- Create all database tables
- Seed roles (it_admin, business_owner, staff, customer)
- Create test users
- Add 31 services across 4 categories
- Add 11 inventory items

### Step 3: Start the Server

```bash
php artisan serve
```

Visit: http://localhost:8000

### Step 4: Login

Use these credentials to test:

**IT Admin**
- Email: admin@autoservice.com
- Password: admin123

**Business Owner**
- Email: owner@autoservice.com  
- Password: owner123

**Staff**
- Email: staff@autoservice.com
- Password: staff123

**Customer**
- Email: customer@autoservice.com
- Password: customer123

## Routes Available

### Customer (after logging in as customer)
- `/customer/dashboard` - Main dashboard
- `/customer/booking` - Create new booking
- `/customer/tracker` - Track bookings
- `/customer/history` - Service history

### Staff (after logging in as staff)
- `/staff/dashboard` - Booking calendar

### Business Owner (after logging in as owner)
- `/owner/dashboard` - Analytics

### IT Admin (after logging in as admin)
- `/admin/dashboard` - System overview
- `/admin/users` - User management
- `/admin/services` - Service management
- `/admin/monitoring` - System monitoring

## Quick Test Flow

1. Login as **customer@autoservice.com**
2. Go to "New Booking"
3. Add a vehicle
4. Select service category
5. Choose services
6. Submit booking

7. Logout and login as **staff@autoservice.com**
8. View the booking in the calendar

## Troubleshooting

**Problem**: "Access denied for user 'root'@'localhost'"
**Solution**: Update DB_PASSWORD in `.env` file

**Problem**: "Database 'shop_booking_system' doesn't exist"
**Solution**: Create the database first:
```sql
CREATE DATABASE shop_booking_system;
```

**Problem**: Frontend styles not loading
**Solution**: Run `npm run build`

## What's Implemented

✅ Complete database schema (12 tables)
✅ All Eloquent models with relationships
✅ Role-based authentication system
✅ Customer booking form (fully functional)
✅ Customer dashboard layout
✅ Staff booking calendar
✅ Admin dashboard
✅ Seeders with 31 services
✅ Tailwind CSS responsive design
✅ Laravel Breeze authentication
✅ Route structure with middleware

## What Needs Completion

⏳ Remaining Livewire component implementations
⏳ Business Owner revenue reports
⏳ Admin user management CRUD
⏳ Staff booking detail view
⏳ Invoice PDF generation
⏳ Booking tracker component
⏳ Email/SMS notifications

## Development Commands

```bash
# Clear caches
php artisan optimize:clear

# Run migrations from scratch
php artisan migrate:fresh --seed

# Build frontend
npm run build

# Watch for changes
npm run dev

# Start server
php artisan serve
```

---

The system is ready for development! Configure your database password and run migrations to get started.
