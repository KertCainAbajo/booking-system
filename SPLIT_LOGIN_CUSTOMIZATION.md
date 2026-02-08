# 🚘 Split Login Page - Customization Guide

## Overview
Your new split login page features:
- **Left Panel**: Full-height visual branding with automotive imagery
- **Right Panel**: Clean, modern login form
- **Automotive Theme**: Charcoal black, dark green, and red accents

---

## 🎨 Customizing the Left Panel Background Image

### Option 1: Use Your Own Local Image
1. Add your car service image to: `public/images/`
   - Recommended: `car-service-hero.jpg` or `garage-background.jpg`

2. Update the background in `resources/views/layouts/guest.blade.php` (line ~26):
   ```blade
   <div class="absolute inset-0 bg-[url('/images/car-service-hero.jpg')] bg-cover bg-center opacity-40"></div>
   ```

### Option 2: Use a Different URL
Replace the Unsplash URL (line ~26 in `guest.blade.php`):
```blade
<div class="absolute inset-0 bg-[url('YOUR_IMAGE_URL')] bg-cover bg-center opacity-40"></div>
```

### Option 3: Remove Background Image (Solid Colors Only)
Comment out or remove the image div, keep only the gradient overlay.

---

## 🎨 Color Customization

### Change Primary Colors
Edit `resources/views/layouts/guest.blade.php`:

**Background Gradient** (line ~24):
```blade
<!-- Current: Gray to Emerald -->
<div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-emerald-900">

<!-- Racing Green Theme -->
<div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-emerald-900 to-gray-800">

<!-- Dark Red/Charcoal Theme -->
<div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-red-900 to-gray-800">

<!-- Pure Dark Theme -->
<div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-900 to-black">
```

**Accent Color** (currently Red - line ~52):
```blade
<span class="text-red-500">Auto Services</span>
```
Change `text-red-500` to:
- `text-emerald-500` for green
- `text-blue-500` for blue
- `text-orange-500` for orange

---

## 📝 Update Text Content

### Main Headline
Update in `guest.blade.php` (line ~51):
```blade
<h1 class="text-5xl xl:text-6xl font-bold leading-tight tracking-tight">
    Book Trusted<br/>
    <span class="text-red-500">Auto Services</span>
</h1>
```

### Features List
Customize the three features (lines ~60-106) to highlight your services:
- Current: Easy Scheduling, Certified Technicians, Transparent Pricing
- Add: Oil Changes, Brake Service, Diagnostics, etc.

---

## 🔧 Button & Input Styling

All styling is in `resources/css/app.css`:

### Change Button Color from Charcoal to Racing Green:
```css
.auth-btn-primary {
    @apply w-full px-6 py-4 bg-emerald-800 text-white font-bold text-base rounded-xl;
    @apply hover:bg-emerald-700 hover:shadow-xl active:scale-[0.98];
    @apply focus:outline-none focus:ring-2 focus:ring-emerald-800 focus:ring-offset-2;
}
```

### Change Link Accent to Red:
```css
.auth-link-primary {
    @apply text-sm font-bold text-gray-900 hover:text-red-600;
    @apply underline decoration-2 underline-offset-2;
}
```

---

## 📱 Mobile Responsiveness

The split layout automatically adapts:
- **Desktop (lg+)**: Split view - visual left, form right
- **Mobile/Tablet**: Single column - logo top, form below
- No additional changes needed!

---

## 🖼️ Recommended Image Specifications

For best results, use images with these specs:

- **Resolution**: 1920x1080 or higher
- **Aspect Ratio**: 16:9 or taller
- **Subject**: 
  - Car in professional garage/shop
  - Mechanic working on vehicle
  - Modern workshop interior
  - Sports car or SUV detail shots
- **Lighting**: Well-lit, professional
- **Orientation**: Vertical or landscape works

### Free Stock Photo Sources:
- [Unsplash](https://unsplash.com/s/photos/car-service) - Auto service photos
- [Pexels](https://pexels.com/search/auto-repair/) - Garage/mechanic shots
- [Pixabay](https://pixabay.com/images/search/car-repair/) - Free commercial use

---

## 🚀 Quick Start

After customization:

1. **Clear cache**:
   ```bash
   php artisan config:clear
   php artisan view:clear
   ```

2. **Rebuild assets** (if you changed CSS):
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

3. **Test**: Visit `/login` to see your changes

---

## 🎯 Pro Tips

1. **Dark overlay opacity**: Adjust `from-gray-900/90` values (line ~29) to make image more/less visible
2. **Logo size**: Change `h-20` in line ~37 to adjust logo height
3. **Panel width**: Adjust `lg:w-[45%]` in line ~20 for different split ratios
4. **Headline size**: Modify `text-5xl xl:text-6xl` for different font sizes

---

## 🔄 Revert to Simple Layout

If you need to go back to the original centered card layout, restore the previous `guest.blade.php` from git:

```bash
git checkout resources/views/layouts/guest.blade.php
```

---

**Need help?** Check the inline comments in:
- `resources/views/layouts/guest.blade.php` - Layout structure
- `resources/views/livewire/pages/auth/login.blade.php` - Form content
- `resources/css/app.css` - Styling classes
