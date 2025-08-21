<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# KITTI Investment Platform

A comprehensive investment platform built with Laravel that allows users to invest in KITTI plans with secure payment processing, admin management, and user dashboard functionality.

## Features

### 🏠 Landing & Registration
- **Multi-step Registration Form**: Smooth UX with step-by-step form completion
- **Mobile OTP Verification**: Secure mobile number verification
- **Document Upload**: Support for Aadhar/PAN upload (JPG/PNG/PDF, max 5MB)
- **Plan Selection**: Choose from ₹1,000, ₹10,000, ₹50,000, or ₹1,00,000 plans
- **Duration Selection**: 12-month investment period with maturity date calculation
- **Account Information**: Bank account or UPI ID for returns/settlements
- **Terms & Conditions**: Mandatory acceptance before registration

### 💳 Payment Processing
- **Multiple Payment Methods**: Payment Gateway, UPI, and QR Code payments
- **Secure Transactions**: Encrypted payment processing with transaction tracking
- **Payment Verification**: Automatic and manual payment verification
- **Retry Mechanism**: Failed payment retry functionality
- **Receipt Generation**: Downloadable payment receipts

### 📧 Email Notifications
- **Payment Success**: Auto-email to client and admin with transaction details
- **Admin Alerts**: Real-time notifications for new registrations
- **Credentials Delivery**: Secure user credentials sent via email
- **Payment Pending**: Alerts for unsettled payments

### 👨‍💼 Admin Panel
- **Dashboard**: Comprehensive overview with statistics and recent activities
- **Registration Management**: View, approve, reject registrations
- **Payment Tracking**: Monitor all payment transactions
- **Discontinue Requests**: Process user discontinuation requests
- **System Configuration**: Manage platform settings and parameters
- **Audit Logs**: Track all admin actions and system events
- **Reports**: Generate detailed reports and analytics

### 👤 User Panel
- **Dashboard**: Investment overview with plan details and maturity information
- **Payment History**: Complete payment history with downloadable receipts
- **Profile Management**: Update personal information and security settings
- **Discontinue Requests**: Submit and track discontinuation requests
- **Security Features**: 2FA support and password reset via email OTP

### 🔒 Security Features
- **Data Encryption**: Sensitive data encrypted at rest
- **Masked Information**: Bank/UPI details masked in admin panel
- **Audit Trail**: Complete audit logging for all actions
- **Session Management**: Secure session handling with timeout
- **Input Validation**: Comprehensive validation for all user inputs

## Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: SQLite (can be configured for MySQL/PostgreSQL)
- **Frontend**: Blade templates with Tailwind CSS
- **Payment Integration**: Ready for payment gateway integration
- **Email**: Laravel Mail system (configurable for SMTP/SES)
- **File Storage**: Laravel Storage system

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and NPM (for asset compilation)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd kitti
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed --class=KittiSeeder
   ```

6. **Storage setup**
   ```bash
   php artisan storage:link
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

## Default Credentials

After running the seeder, you'll have access to:

- **Admin Panel**: `admin@kitti.com` / `admin123`
- **Admin URL**: `http://localhost:8000/admin/dashboard`

## Configuration

### System Configuration
The platform includes a comprehensive system configuration panel where admins can manage:

- Auto-confirmation timelines
- Payment gateway settings
- Email/SMS notification preferences
- Company information
- Support contact details

### Payment Gateway Integration
To integrate with actual payment gateways:

1. Update payment gateway credentials in admin panel
2. Modify `PaymentController` methods for your gateway
3. Configure webhook endpoints for payment callbacks

### Email Configuration
Configure email settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@kitti.com
MAIL_FROM_NAME="KITTI Investment Platform"
```

## Database Schema

### Core Tables
- `users` - User accounts and authentication
- `kitti_registrations` - Investment registrations
- `payment_transactions` - Payment records
- `discontinue_requests` - Discontinuation requests
- `audit_logs` - System audit trail
- `system_configs` - Platform configuration

### Key Relationships
- One user can have multiple registrations
- Each registration can have multiple payment transactions
- Each registration can have multiple discontinue requests
- All admin actions are logged in audit_logs

## API Endpoints

### Registration Endpoints
- `POST /registration/step1` - Personal information
- `POST /registration/verify-otp` - Mobile OTP verification
- `POST /registration/step2` - Plan selection
- `POST /registration/step3` - Document upload
- `POST /registration/step4` - Duration selection
- `POST /registration/step5` - Account information
- `POST /registration/step6` - Terms acceptance

### Payment Endpoints
- `GET /payment/gateway/{transaction}` - Payment gateway
- `GET /payment/upi/{transaction}` - UPI payment
- `GET /payment/qr/{transaction}` - QR payment
- `POST /payment/gateway/callback` - Gateway callback
- `POST /payment/upi/callback` - UPI callback

### Admin Endpoints
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/registrations` - List registrations
- `POST /admin/registrations/{id}/approve` - Approve registration
- `POST /admin/registrations/{id}/reject` - Reject registration
- `GET /admin/payments` - List payments
- `GET /admin/reports` - Generate reports

### User Endpoints
- `GET /user/dashboard` - User dashboard
- `GET /user/payment-history` - Payment history
- `POST /user/discontinue-request` - Submit discontinuation
- `GET /user/profile` - User profile
- `PUT /user/profile` - Update profile

## Security Considerations

### Data Protection
- All sensitive data is encrypted at rest
- Bank account and UPI details are masked in admin panel
- Session timeout configuration
- CSRF protection on all forms

### Access Control
- Role-based access control (Admin/User)
- Middleware protection for admin routes
- Input validation and sanitization
- SQL injection prevention

### Audit & Compliance
- Complete audit trail for all actions
- User activity logging
- Payment transaction tracking
- Data retention policies

## Deployment

### Production Checklist
- [ ] Configure production database
- [ ] Set up SSL certificate
- [ ] Configure email service
- [ ] Set up payment gateway integration
- [ ] Configure file storage (S3/Azure)
- [ ] Set up monitoring and logging
- [ ] Configure backup strategy
- [ ] Set up CI/CD pipeline

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=kitti_production
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls

FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-s3-key
AWS_SECRET_ACCESS_KEY=your-s3-secret
AWS_DEFAULT_REGION=your-region
AWS_BUCKET=your-bucket
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support and questions:
- Email: support@kitti.com
- Documentation: [Link to documentation]
- Issues: [GitHub Issues]

## Changelog

### v1.0.0 (2024-08-08)
- Initial release
- Multi-step registration system
- Payment processing integration
- Admin panel with comprehensive management
- User dashboard with investment tracking
- Audit logging and security features
#   k i t t i  
 