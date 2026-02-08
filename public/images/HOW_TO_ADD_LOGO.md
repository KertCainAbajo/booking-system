# How to Add Your Logo to the Booking System

## Step 1: Add Your Logo File

Replace the placeholder `logo.svg` with your actual logo file, or add new logo files:
- `logo.png` - For PNG format
- `logo.svg` - For SVG format (recommended)
- `logo-white.png` - For dark backgrounds

## Step 2: Add Logo to Your Layouts

### For Admin Layout (resources/views/layouts/admin.blade.php)

Find the header or navigation section and add:

```html
<img src="{{ asset('images/logo.svg') }}" alt="Booking System Logo" class="h-10 w-auto">
```

### For All Layouts

You can add the logo to:
- `resources/views/layouts/admin.blade.php`
- `resources/views/layouts/owner.blade.php`
- `resources/views/layouts/staff.blade.php`
- `resources/views/layouts/customer.blade.php`
- `resources/views/layouts/guest.blade.php`

## Step 3: Common Logo Placements

### In Navigation Bar
```html
<nav class="bg-white shadow">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-8">
            <!-- Rest of navigation -->
        </div>
    </div>
</nav>
```

### As a Link to Home
```html
<a href="{{ route('dashboard') }}" class="flex items-center">
    <img src="{{ asset('images/logo.svg') }}" alt="Booking System" class="h-10">
</a>
```

### With Text Beside It
```html
<div class="flex items-center space-x-3">
    <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-10">
    <span class="text-xl font-bold">Booking System</span>
</div>
```

## Step 4: Responsive Logo (Optional)

For different sizes on mobile vs desktop:

```html
<img src="{{ asset('images/logo.svg') }}" 
     alt="Logo" 
     class="h-8 md:h-10 lg:h-12">
```

## Image Optimization

1. **Recommended logo size**: 200-400px width
2. **File size**: Keep under 100KB
3. **Format priority**: SVG > PNG > JPG

## Need Help?

The logo is now stored in `public/images/` directory and can be accessed via:
- In Blade: `{{ asset('images/logo.svg') }}`
- Direct URL: `http://yourdomain.com/images/logo.svg`
