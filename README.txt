TDK Designs & Branding Solutions - website files

HOW TO PUT IT ONLINE
1. Back up, then empty, the public_html folder on your hosting.
   Do not keep x.php, the .git folder or the "p" folder from the old site.
2. Upload everything in this folder into public_html, including the hidden
   .htaccess file (turn on "show hidden files" in your file manager).
3. Open the site, then send a test message from the Contact page.

PAGES
index.html                 Home
about.html                 About
services.html              Services
posters.html               Service: graphic design
printing.html              Service: printing
signboards-branding.html   Service: signage
vehicle-branding.html      Service: vehicle branding
cloth-print.html           Service: clothing printing
work.html                  Our work (gallery with filters)
contact.html + contact.php Contact page and the script that emails the form
404.html                   Shown when a page is not found

WHERE TO CHANGE THINGS
Colours and fonts      assets/css/site.css (variables at the top)
Enquiry email address  contact.php ($recipient)
Photos                 assets/img/work (each photo has an 800px and a 1600px version)
Client logos           assets/img/clients
