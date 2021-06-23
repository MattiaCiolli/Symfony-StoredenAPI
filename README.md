# Symfony-Storeden
Webapp made with Symfony to store and do operations on Orders and Products from Storeden API.

Functionalities:

* Sections:
    - orders:
        - Order list taken from API Storeden (API read only)
        - Saving this list on local DB
        - Change order status only locally (no sending to remote API)
        - Order cancellation only locally (no sending to remote API)
        - CSV export of the list
        - Search order
    - products:
        - Product list taken from API Storeden (API read only)
        - Saving this list on local DB
        - Change product name and image change (image upload) only locally (no sending to remote API)
        - Product cancellation only locally (no sending to remote API)
        - Export CSV of the list
        - Product search
    - users:
        - Creation of users locally with access level Admin, Editor, Assistant
    - report:
         - total number of orders
         - total number of products
         - a graph traking order totals

* Login with email and password of 3 levels of users:
    - Admin who accesses all sections
    - Editor that accesses the order list, order detail, product list and product detail view. Product cancellation, product modification
    - Assistant that accesses the order list view, order details without downloading in CSV.

The webapp runs locally using `symfony server:start` and uses XAMPP's PHPMYADMIN to handle DB storage.

