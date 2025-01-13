# Parking Management System (amiparqueo)

A web-based parking management system built with PHP that allows businesses to manage parking operations, track vehicle entries/exits, and generate reports.

## Features

- **User Authentication System**
  - Secure login system with session management
  - Master user and authorized user roles
  - Password reset functionality

- **Vehicle Management**
  - Track vehicle entry and exit times
  - Multiple vehicle types with customizable pricing
  - Support for different parking scenarios:
    - Hourly parking
    - Event rates
    - Day/Night rates
    - Ticket cancellation
    - Lost ticket handling

- **Reporting & History**
  - Real-time session history
  - Searchable ticket records
  - Excel export functionality for reports
  - Historical data archive

- **Administrative Features**
  - Customizable company logo
  - Employee management
  - Email notification system
  - Price configuration for:
    - Different vehicle types
    - Special events
    - Fixed rate scenarios

- **User Interface**
  - Responsive design
  - Material Design icons
  - Intuitive navigation
  - Mobile-friendly layout

## System Requirements

- PHP (with session support)
- MySQL/MariaDB database
- Web server (Apache/Nginx)
- Modern web browser

## Main Components

### Core Pages

1. `index.php`: Main dashboard for parking operations
   - Vehicle entry/exit logging
   - Time calculation
   - Pricing computation

2. `previous.php`: Current session history
   - Real-time transaction viewing
   - Ticket search functionality
   - Export capabilities

3. `historial.php`: Complete history archive
   - Historical record viewing
   - File download functionality
   - Data organization by date/time

4. `settings.php`: System configuration
   - User management
   - Price settings
   - Employee records
   - Company branding

## Database Structure

The system uses several key tables:
- `users`: User authentication and management
- `workers`: Employee information
- `vehicles`: Vehicle types and pricing
- `excel_files`: Export history
- `log_out`: Transaction records
- `fixed_events`: Special rate configurations

## Security Features

- Session-based authentication
- Prepared SQL statements to prevent injection
- Password hashing
- Role-based access control
- Input validation

## Additional Information

The system supports:
- Custom company logos (stored in `/logos/`)
- Multiple pricing models
- Ticket tracking system
- Multiple user roles

## Customization

The system allows for customization of:
- Vehicle types and rates
- Special event pricing
- Company branding
- Email notifications
- User roles and permissions

## Usage Notes

1. All users must log in to access the system
2. Master users have access to additional configuration options
3. The system automatically calculates parking duration and fees
4. Reports can be exported to Excel format
5. Transaction history is searchable by ticket number

## For testing purposes use:
   - Username: updates@amiparqueo.com
   - Password: 12345


---

For support or inquiries, contact: updates@amiparqueo.com
