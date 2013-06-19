Custom Menus
============

Developer
---------
Developed and maintained by [i-lateral](http://www.i-lateral.com)

Requirements
------------
SilverStripe 3.1 or greater.


Installation Instructions
-------------------------

Downloading the module:

1. Download and add the module to the "custommenus" folder in your SilverStripe root folder

2. Run dev/build?flush=all

3. Visit admin/menus/ to setup your custom menus

4. In you template, use: <% loop CustomMenu(slug) %><your HTML here /><% end_loop %>

You can also download and install this module using composer and packagist 

Usage
-----

Once the module is downloaded and installed, you can create menus and add pages
to them using the "Menus" page in the admin interfacve.

To render these menus in your templates, you can either:

Add **$RenderCustomMenu(menu-slug)** to your template code. This will make use of the
CustomMenu.ss include to render a UL (with a unique class name).

Alternativley you can generate your own template by adding the following code:

    <% if $CustomMenu(main-menu) %><ul class="menu">
        <% loop $CustomMenu(main-menu) %>
            <li class="$LinkingMode $FirstLast">
                <a href="$Link">$MenuTitle</a>
            </li>
        <% end_loop %>
    </ul><% end_if %>
