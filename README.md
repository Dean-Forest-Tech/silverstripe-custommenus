Custom Menus
============

Developer
---------
Developed and maintained by [i-lateral](http://www.i-lateral.com)

Requirements
------------
SilverStripe 3.0 or greater.


Installation Instructions
-------------------------

Downloading the module:

1. Download and add the module to the "custommenus" folder in your SilverStripe root folder

2. Run dev/build?flush=all

3. Visit admin/menus/ to setup your custom menus

4. In you template, use: <% loop CustomMenu(slug) %><your HTML here /><% end_loop %>

You can also download and install this module using composer and packagist 
