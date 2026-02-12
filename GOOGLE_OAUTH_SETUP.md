# Google OAuth Setup Guide

## Overview
The login pages have been updated to remove the "Book as Guest" option and replace it with:
- **Create an Account** link - directs users to the registration page
- **Sign in with Google** button - allows users to create an account or login using their Google account

## What's Been Implemented

### 1. Login Pages Updated
- Both [login.blade.php](resources/views/livewire/pages/auth/login.blade.php) and [customer-login.blade.php](resources/views/livewire/pages/auth/customer-login.blade.php) now have:
  - "Create an Account" link instead of "Book as Guest"
  - "Sign in with Google" button for OAuth authentication

### 2. Registration Page Updated
- [register.blade.php](resources/views/livewire/pages/auth/register.blade.php) now includes:
  - "Sign up with Google" button for OAuth registration
  - Traditional email/password registration form

### 3. Google OAuth Integration
- **Laravel Socialite** package installed
- **GoogleAuthController** created at [app/Http/Controllers/Auth/GoogleAuthController.php](app/Http/Controllers/Auth/GoogleAuthController.php)
- OAuth routes added to [routes/auth.php](routes/auth.php):
  - `GET /auth/google` - Redirects to Google OAuth page
  - `GET /auth/google/callback` - Handles callback from Google
- Database migration added to include `google_id` column in users table

### 4. Configuration Files Updated
- [config/services.php](config/services.php) - Google OAuth configuration added
- [.env.example](.env.example) - Google OAuth environment variables added

## Setup Instructions

### Step 1: Get Google OAuth Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Google+ API:
   - Navigate to "APIs & Services" > "Library"
   - Search for "Google+ API" and enable it
4. Create OAuth credentials:
   - Go to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "OAuth client ID"
   - Select "Web application"
   - Add authorized redirect URIs:
     - For local development: `http://localhost:8000/auth/google/callback`
     - For production: `https://yourdomain.com/auth/google/callback`
   - Click "Create"
5. Copy the **Client ID** and **Client Secret**

### Step 2: Configure Environment Variables

Add the following to your `.env` file:

```env
GOOGLE_CLIENT_ID=your-google-client-id-here
GOOGLE_CLIENT_SECRET=your-google-client-secret-here
GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback
```

**Important:** Make sure your `APP_URL` is set correctly:
- Local: `APP_URL=http://localhost:8000`
- Production: `APP_URL=https://yourdomain.com`

### Step 3: Clear Configuration Cache

After updating the `.env` file, clear the configuration cache:

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 4: Test the Integration

1. Navigate to the login page: `http://localhost:8000/login`
2. You should see:
   - Email/password login form
   - "Create an Account" link (instead of "Book as Guest")
   - "Sign in with Google" button
3. Click "Sign in with Google" to test the OAuth flow

## How It Works

### Login Flow
1. User clicks "Sign in with Google"
2. User is redirected to Google's OAuth consent screen
3. After authorization, Google redirects back to `/auth/google/callback`
4. The app checks if a user with the Google email exists:
   - **If user exists and is a customer**: Logs them in
   - **If user exists but is not a customer (staff/admin)**: Shows error
   - **If user doesn't exist**: Creates new customer account and logs them in

### Account Creation
When a user signs up with Google:
- A new User record is created with:
  - Name from Google profile
  - Email from Google account
  - Random password (not used for Google login)
  - Email automatically verified
  - Customer role assigned
  - Google ID stored for future logins
- A Customer profile is automatically created
- User is logged in and redirected to the customer dashboard

### Security Features
- Google OAuth uses secure OAuth 2.0 protocol
- Emails are automatically verified for Google users
- Random passwords are generated for OAuth accounts
- Google ID is stored separately to identify OAuth users
- Existing staff/admin accounts cannot login via Google OAuth (customer-only)

## Troubleshooting

### "Unable to login with Google" Error
- Check that Google OAuth credentials are correctly set in `.env`
- Verify the redirect URI in Google Cloud Console matches your app URL
- Ensure Google+ API is enabled in Google Cloud Console
- Clear config cache: `php artisan config:clear`

### "This account is not a customer account" Error
- This error appears when a staff/admin/owner account tries to login via Google
- These accounts must use the regular email/password login

### Redirect URI Mismatch Error
- The redirect URI in Google Cloud Console must exactly match:
  - `http://localhost:8000/auth/google/callback` (local)
  - `https://yourdomain.com/auth/google/callback` (production)
- Do not include trailing slashes

## Production Deployment

Before deploying to production:

1. Update `APP_URL` in `.env` to your production domain
2. Add production redirect URI to Google Cloud Console:
   - `https://yourdomain.com/auth/google/callback`
3. Ensure `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET` are set
4. Run: `php artisan config:cache`
5. Test the OAuth flow on production

## Additional Notes

- Users who sign up with Google can still reset their password if they want to use email/password login later
- The "google_id" column in the users table is nullable and unique
- OAuth users have their email automatically verified
- The migration has been run and the database is ready for Google OAuth users
