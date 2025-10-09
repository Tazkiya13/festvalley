# FestValley Template

This folder contains the base HTML, CSS, and JavaScript template for the FestValley artist booking platform. The template is designed to be responsive, accessible, and easy to integrate with any backend framework.

## 📁 Directory Structure

```
template/
├── index.html                 # Main template page
├── README.md                  # This documentation
├── css/
│   ├── festvalley.css        # Main stylesheet with global styles
│   └── components.css        # Component-specific styles
├── js/
│   ├── festvalley.js         # Main JavaScript functionality
│   ├── chat.js               # Chat system functionality
│   └── booking.js            # Booking form functionality
└── components/
    ├── artist-card.html      # Artist card and profile modal
    ├── booking-form.html     # Booking form components
    └── dashboard.html        # Dashboard layout
```

## 🎨 Design Features

### Color Scheme
- **Primary Purple**: `#667eea` - Used for primary actions and branding
- **Secondary Orange**: `#f093fb` - Used for accents and highlights
- **Success Green**: `#28a745` - For success states and confirmations
- **Warning Orange**: `#ffc107` - For warnings and pending states
- **Danger Red**: `#dc3545` - For errors and critical actions

### Typography
- **Headings**: Inter font family for clean, modern look
- **Body Text**: System font stack for optimal readability
- **Responsive**: Fluid typography that scales with viewport

### Layout
- **Responsive Grid**: Bootstrap 5-based grid system
- **Mobile-First**: Designed for mobile devices first
- **Flexible Containers**: Adapts to different screen sizes

## 🧩 Components

### Navigation
- Responsive navbar with mobile toggle
- User authentication states
- Search functionality integration
- Notification indicators

### Hero Section
- Eye-catching gradient background
- Search form with location and date filters
- Call-to-action buttons
- Responsive image/video background support

### Artist Cards
- Image galleries with multiple photos
- Video integration
- Rating and review display
- Package pricing
- Quick booking actions
- Favorite functionality

### Booking System
- Multi-step form with progress indicator
- Form validation and error handling
- Date picker with availability
- File upload support
- Payment integration ready
- Confirmation and tracking

### Chat Widget
- Real-time messaging interface
- Support for both customer and artist sides
- Offline/online status indicators
- Message history
- File sharing capabilities

### Dashboard
- Sidebar navigation
- Stats overview cards
- Activity timeline
- Booking management
- Profile settings
- Responsive table views

## 🚀 Getting Started

### 1. Basic Integration

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your App - FestValley</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- FestValley Styles -->
    <link href="template/css/festvalley.css" rel="stylesheet">
    <link href="template/css/components.css" rel="stylesheet">
</head>
<body>
    <!-- Your content here -->
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- FestValley Scripts -->
    <script src="template/js/festvalley.js"></script>
    <script src="template/js/chat.js"></script>
    <script src="template/js/booking.js"></script>
</body>
</html>
```

### 2. Using Components

Copy the HTML from component files and integrate into your pages:

```html
<!-- Include artist card -->
<div class="artists-grid">
    <!-- Copy content from components/artist-card.html -->
</div>

<!-- Include booking form -->
<div class="booking-container">
    <!-- Copy content from components/booking-form.html -->
</div>
```

### 3. JavaScript Initialization

```javascript
// Initialize FestValley components
document.addEventListener('DOMContentLoaded', function() {
    // Main FestValley functionality
    FestValley.init();
    
    // Chat system (if needed)
    ChatSystem.init();
    
    // Booking system (if on booking page)
    if (document.getElementById('booking-form-container')) {
        BookingSystem.init();
    }
});
```

## 🔧 Customization

### CSS Variables
Override CSS custom properties to match your brand:

```css
:root {
    --primary-color: #your-color;
    --secondary-color: #your-color;
    --success-color: #your-color;
    --warning-color: #your-color;
    --danger-color: #your-color;
    
    --font-family: 'Your Font', sans-serif;
    --border-radius: 0.5rem;
    --box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
```

### JavaScript Configuration
Configure the FestValley object:

```javascript
FestValley.config = {
    apiUrl: '/api',
    csrfToken: 'your-csrf-token',
    locale: 'en',
    currency: 'EUR',
    // Add your configuration
};
```

## 📱 Responsive Breakpoints

- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px
- **Large Desktop**: > 1200px

## 🎯 Key Features

### Search & Filtering
- Artist search with autocomplete
- Location-based filtering
- Date availability checking
- Genre and price filtering
- Advanced search options

### Booking Flow
- Multi-step booking process
- Real-time availability checking
- Package selection
- Customer information collection
- Payment integration ready
- Email confirmations

### User Experience
- Smooth animations and transitions
- Loading states and feedback
- Error handling and validation
- Accessibility compliance (WCAG 2.1)
- SEO-friendly structure

### Performance
- Optimized CSS and JavaScript
- Lazy loading for images
- Minimal dependencies
- Progressive enhancement

## 🔌 Integration Notes

### Backend Integration
- All forms include CSRF token fields
- AJAX endpoints are configurable
- RESTful API structure assumed
- JSON response format expected

### Database Integration
- Artist and package data structure defined
- Booking workflow states documented
- User authentication states handled
- File upload paths configurable

### Payment Integration
- Payment component ready for Stripe/PayPal
- Invoice generation support
- Booking confirmation flow
- Refund and cancellation handling

## 🛡️ Security Considerations

- CSRF protection on all forms
- XSS prevention in dynamic content
- File upload validation
- Input sanitization
- Rate limiting on AJAX requests

## 📚 Dependencies

### Required
- Bootstrap 5.3.0+
- Material Icons
- Modern browser with ES6 support

### Optional
- Flatpickr (for enhanced date picking)
- Select2 (for enhanced select boxes)
- Toastr.js (for notifications)
- Chart.js (for dashboard analytics)

## 🎉 Features Included

- ✅ Responsive design
- ✅ Artist browsing and search
- ✅ Multi-step booking process
- ✅ Real-time chat system
- ✅ User dashboard
- ✅ Payment integration ready
- ✅ File upload support
- ✅ Email template structure
- ✅ Invoice generation
- ✅ Review and rating system
- ✅ Notification system
- ✅ Mobile-first approach
- ✅ Accessibility compliance

## 📞 Support

For questions about implementing this template:

1. Check the component documentation in each HTML file
2. Review the JavaScript comments for API integration points
3. Test responsive behavior across different devices
4. Validate accessibility with screen readers

## 🔄 Updates

This template is based on the current FestValley platform design as of 2024. Regular updates may be needed to:

- Add new components
- Update styling for new features
- Improve accessibility
- Optimize performance
- Fix browser compatibility issues

---

**Built with ❤️ for FestValley**
