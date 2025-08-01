# 🏠 Mietbestätigung PDF Generator

Generate a filled PDF document from an HTML form using PHP with **FPDF** and **FPDI** libraries.

---

## 📋 Requirements

- PHP 7.4+ (PHP 8 recommended)  
- Web server (Apache, Nginx, etc.) with PHP support  
- FPDF and FPDI libraries (already included in the repository)

---

## 🚀 Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/Vasianandcarps/form_-wp.git
   cd form_-wp
## 🚀 Installation

2. **Place files in your web server directory**

   For example:

   - On XAMPP (Windows):  
     `C:\xampp\htdocs\form`
   
   - On Linux (Apache, Nginx):  
     `/var/www/html/form`

3. **Check folder structure**

   Make sure your project folder looks like this:

form_-wp/
├── fpdf/ # FPDF library source files
├── fpdi/ # FPDI library source files
├── Fragebogen_Mietbestätigung.pdf # PDF template
├── generate_pdf.php # PHP script generating the PDF
└── form.html # HTML form file


4. **Verify library inclusion in your PHP script**

In `generate_pdf.php`, the libraries are included like this:

```php
require_once __DIR__ . '/fpdf/fpdf.php';
require_once __DIR__ . '/fpdi/src/autoload.php';
