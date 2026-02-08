# Images Directory

This directory is for storing static images used in your booking system application.

## Logo Files

Place your logo file(s) here. Recommended formats:
- **PNG** (with transparency for flexibility)
- **SVG** (scalable vector graphics for best quality)
- **JPG/JPEG** (for photographic logos)

### Suggested naming:
- `logo.png` - Main logo
- `logo-white.png` - White version (for dark backgrounds)
- `logo-icon.png` - Icon/favicon version
- `logo.svg` - Vector version

## Using Images in Your Application

### In Blade/Livewire Views:
```html
<img src="{{ asset('images/logo.png') }}" alt="Company Logo">
```

### In CSS:
```css
background-image: url('/images/logo.png');
```

### Direct URL:
```
https://yourdomain.com/images/logo.png
```

## Image Optimization Tips

1. **Compress images** before uploading to reduce file size
2. **Use appropriate dimensions** (e.g., 200-400px width for header logos)
3. **Consider using SVG** for logos that need to scale
4. **Use WebP format** for better compression (with PNG fallback)
