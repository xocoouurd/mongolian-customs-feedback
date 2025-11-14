# Mongolian Customs Feedback Form

A simple, clean feedback submission system styled to match customs.gov.mn.

## Features

- Three submission types: Санал (Suggestion), Гомдол (Complaint), Өргөдөл (Petition)
- Email notifications via Gmail SMTP
- A4-style textarea for professional document feel
- Loading animation on submission
- Responsive and clean UI
- Mongolian language interface

## Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 8.0+
- **Email**: PHPMailer via SMTP
- **Fonts**: Montserrat (headers), Roboto Condensed (body)
- **Colors**: Navy blue (#00006e), Gold accent (#ffd700)

## Quick Start

1. Install dependencies:
   ```bash
   composer install
   ```

2. Configure email settings in `config.php`

3. Deploy to web server (see `DEPLOYMENT.md` for detailed instructions)

## Documentation

See [DEPLOYMENT.md](DEPLOYMENT.md) for complete deployment instructions.

## Configuration

Edit `config.php` to set:
- SMTP credentials
- Sender email
- Receiver email

## License

Proprietary - All rights reserved
