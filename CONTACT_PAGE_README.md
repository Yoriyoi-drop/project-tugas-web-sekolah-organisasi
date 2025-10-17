# Hubungi Kami Page - Madrasah Aliyah Nusantara

## Overview
Modern, responsive contact page with teal gradient branding, accessible form validation, and mobile-first design approach.

## Features Implemented

### 🎨 Visual & Branding
- ✅ Primary gradient: `linear-gradient(135deg, #009688 0%, #00796B 100%)`
- ✅ Accent color: `#00BFA5` for buttons and links
- ✅ Poppins font family with Google Fonts integration
- ✅ Font Awesome icons for enhanced visual appeal
- ✅ Rounded corners (1rem border-radius) and subtle shadows
- ✅ CSS variables for easy theme customization

### 📱 Layout & Responsiveness
- ✅ Mobile-first CSS approach
- ✅ Hero section with large centered envelope icon
- ✅ Two-column grid layout (contact info + form)
- ✅ Responsive stacking on screens < 768px
- ✅ Container max-width: 1200px, centered
- ✅ Proper grid gaps and spacing

### 📞 Contact Information Card
- ✅ Multi-line address display
- ✅ Click-to-call telephone links (`tel:`)
- ✅ Mailto email links
- ✅ Operating hours information
- ✅ Embedded Google Maps iframe (responsive)
- ✅ Icons for each contact method
- ✅ Hover effects and visual feedback

### 📝 Contact Form
- ✅ Required fields: Nama Lengkap, Email, Pesan
- ✅ Optional field: Subjek
- ✅ Accessible labels with `for` attributes
- ✅ Client-side validation with JavaScript
- ✅ Server-side validation with Laravel
- ✅ `aria-invalid` attributes for screen readers
- ✅ Real-time validation feedback
- ✅ Submit button with loading state
- ✅ Double-submit prevention
- ✅ Keyboard accessibility

### ♿ Accessibility & Performance
- ✅ Semantic HTML5 structure (`header`, `main`, `section`, `form`)
- ✅ Sufficient color contrast ratios
- ✅ Focus styles for keyboard navigation
- ✅ ARIA attributes for screen readers
- ✅ Font Awesome SVG icons for scalability
- ✅ Progressive enhancement approach
- ✅ `prefers-reduced-motion` support

### ✨ Animations & Interactions
- ✅ Fade-in animations on page load
- ✅ Hover and focus states for interactive elements
- ✅ Smooth transitions (0.3s ease)
- ✅ Loading spinner for form submission
- ✅ Success modal with proper focus management

## Files Created/Modified

### New Files
- `public/css/contact.css` - Dedicated contact page styles
- `public/js/contact.js` - Contact form JavaScript functionality
- `CONTACT_PAGE_README.md` - This documentation file

### Modified Files
- `resources/views/pages/kontak.blade.php` - Complete page redesign
- `app/Http/Controllers/ContactController.php` - Enhanced validation and rate limiting
- `resources/views/layouts/app.blade.php` - Added Poppins font and Font Awesome

## Technical Implementation

### CSS Architecture
```css
:root {
    --primary-gradient: linear-gradient(135deg, #009688 0%, #00796B 100%);
    --accent-color: #00BFA5;
    --border-radius: 1rem;
    --shadow: 0 6px 20px rgba(3, 15, 20, 0.08);
    --transition: all 0.3s ease;
}
```

### JavaScript Features
- ES6 Class-based architecture
- Real-time form validation
- Accessibility-focused modal handling
- Focus trap implementation
- Progressive enhancement

### Laravel Integration
- CSRF protection
- Server-side validation with custom messages
- Rate limiting (3 attempts per 5 minutes)
- Flash message handling
- Blade template integration

## Usage Instructions

### 1. Dependencies
- Google Fonts (Poppins) - Already included
- Font Awesome 6.4.0 - Already included
- Bootstrap 5 - Already available in project

### 2. Customization
Update CSS variables in `:root` to change theme colors:
```css
:root {
    --primary-gradient: your-gradient-here;
    --accent-color: your-accent-color;
}
```

### 3. Google Maps Integration
Replace the iframe `src` in the contact page with your actual location:
```html
<iframe src="YOUR_GOOGLE_MAPS_EMBED_URL"></iframe>
```

### 4. Contact Information
Update contact details in the contact page template:
- Address
- Phone numbers
- Email addresses
- Operating hours

### 5. Form Handling
The form submits to Laravel's `ContactController@send` method. Customize the backend logic as needed:
- Email sending
- Database storage
- Third-party integrations

## Browser Support
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## Performance Optimizations
- CSS and JS are loaded efficiently
- Images are optimized and responsive
- Animations respect `prefers-reduced-motion`
- Progressive enhancement ensures functionality without JavaScript

## Accessibility Compliance
- WCAG 2.1 AA compliant
- Screen reader friendly
- Keyboard navigation support
- High contrast mode support
- Focus management in modals

## Future Enhancements
- [ ] Real-time chat integration
- [ ] Multi-language support
- [ ] Advanced form fields (file upload, etc.)
- [ ] Integration with CRM systems
- [ ] Analytics tracking for form submissions

## Support
For questions or issues, refer to the Laravel documentation or contact the development team.