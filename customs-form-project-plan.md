# Project: Mongolian Customs Feedback Form

## Overview
Simple feedback submission system styled to match customs.gov.mn. Three submission types (Санал, Гомдол, Өргөдөл) with email notifications via Gmail SMTP.

---

## Technical Stack
- **Frontend**: Single HTML file with embedded CSS/JS
- **Backend**: PHP with PHPMailer for SMTP
- **Email**: Gmail SMTP with app password
- **Fonts**: Montserrat (headers), Roboto Condensed (body text)
- **Primary Color**: `#00006e`
- **Accent Color**: `#ffd700` (gold)

---

## Gmail SMTP Configuration
```
Email: khosbayar.5272@gmail.com
App Password: pqyp zifp eaqq ihyb
SMTP Host: smtp.gmail.com
SMTP Port: 587
Encryption: TLS
```

---

## Project Structure
```
/
├── index.html          # Main page (buttons, form, all styling)
├── submit.php          # Form handler, sends email via SMTP
├── logo.png            # Provided by user in root folder
└── vendor/
    └── phpmailer/      # PHPMailer library for SMTP
```

---

## Page Requirements

### Header
- Logo (logo.png) on the left
- Site title: "МОНГОЛЫН ГААЛЬ" or similar
- Background: `#00006e`
- Text: white, Montserrat font

### Main Content
- Clean white background
- Centered content area
- Page title in Mongolian (e.g., "Санал хүсэлт илгээх")

### Three Buttons
- **Санал** (Suggestion/Feedback)
- **Гомдол** (Complaint)
- **Өргөдөл** (Petition/Request)
- Style: Large, prominent buttons with `#00006e` background, white text
- Hover effect: Gold accent (`#ffd700`) or lighter blue
- Montserrat font

### Form (appears when button clicked)
- Large textarea for message input
- No character limit
- Placeholder text in Mongolian
- Submit button styled to match
- Form should display which type was selected (Санал/Гомдол/Өргөдөл)
- All labels and text in Mongolian

### Success Message
- Display confirmation in Mongolian after successful submission
- Example: "Таны хүсэлт амжилттай илгээгдлээ" (Your request was sent successfully)
- Option to submit another or return to main buttons

### Footer
- Background: `#00006e`
- Simple footer with basic info
- White text, Roboto Condensed font

---

## Functionality

### JavaScript (embedded in index.html)
1. Button click shows the form
2. Track which button was clicked (Санал/Гомдол/Өргөдөл)
3. Form validation (ensure text is not empty)
4. AJAX submission to submit.php
5. Display success/error message
6. Reset form after successful submission

### PHP (submit.php)
1. Receive POST data (type and message)
2. Validate input
3. Use PHPMailer to send email via Gmail SMTP
4. Email subject should include the type: "[Санал]", "[Гомдол]", or "[Өргөдөл]"
5. Email body contains the submitted message
6. Return JSON response (success or error)
7. Send to: khosbayar.5272@gmail.com

---

## Email Format
```
To: khosbayar.5272@gmail.com
Subject: [Санал] Шинэ хүсэлт ирлээ
Body:
Төрөл: Санал
Огноо: 2025-11-13 19:05
Мессеж:
[User's submitted text here]
```

---

## Setup Instructions

1. **Install PHPMailer** via Composer or download directly:
   ```bash
   composer require phpmailer/phpmailer
   ```
   Or download from: https://github.com/PHPMailer/PHPMailer

2. **Place logo.png** in the root directory

3. **Configure submit.php** with the Gmail credentials provided above

4. **Test locally** with PHP built-in server:
   ```bash
   # Find an available port first (8000, 8001, 8080, etc. may be in use)
   # Use any empty port that's available
   php -S localhost:[EMPTY_PORT]
   ```

5. **Deploy** to any PHP-enabled server

---

## Important Notes
- All text content must be in Mongolian
- No contact info fields required (anonymous submissions)
- No character limits on textarea
- Responsive design not strictly required but keep it clean
- Match the professional government website aesthetic
- PHPMailer handles the SMTP authentication properly
