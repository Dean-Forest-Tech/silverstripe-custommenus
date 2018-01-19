# Custom Menus

A module to allow the creation of customised menus for your
SilverStripe site/App via SiteConfig.

This module is developed and maintained by [ilateral](http://www.ilateralweb.co.uk)

## Requirements
SilverStripe 3.3 or greater.

## Installation Instructions

The prefered way to install this module is via composer:

    composer require i-lateral/silverstripe-custommenus

Alternativley you can download the module:

1. Download and add the module to the "custommenus" folder in your SilverStripe root folder
2. Run dev/build?flush=all

## Usage

Once the module is downloaded and installed, you can create menus
and add pages to them by visiting:

    http://www.yourwebsite.com/admin.settings

And then clicking on the "Menus" tab.

You will then need to create a `MenuHolder` (or use one of the installed defaults).

Now edit the `MenuHolder` and set the BaseClass (this will usually default to `SiteTree`). Once you have done this **and saved** you will be able to associate this menu item with you Page/Object using
the ObjectID field.

## Tempaltes

`CustomMenus` comes with two options for rendering menus into a
template you can either:

### Use the bundled template

Adding **$RenderCustomMenu(menu-slug)** to your template code make
use of the CustomMenu.ss include to render a UL (with a unique class name).

You can also overload this template in your own theme to generate
custom HTML for your navigation.

### Loop through a list of menu items

Alternativley you can generate your own template by adding the 
following code:

    <% if $CustomMenu(main-menu) %><ul class="menu">
        <% loop $CustomMenu(main-menu) %>
            <li class="$LinkingMode $FirstLast">
                <a href="$Link">$MenuTitle</a>
            </li>
        <% end_loop %>
    </ul><% end_if %>

## Linking to custom DataObjects

By default this module looks for (and utilises) the CMS module
(allowing linking to Pages). It is fairly easy though to add links
to other DataObjects though.

### DataObject Requirements

First off, ensure that your custom `DataObject` has the following methods available:

- Link
- AbsoluteLink
- RelativeLink

Also, ensure the following properties are available:

- Title
- MenuTitle

### Add `DataObject` class

Now, you need to make `CustomMenuLink` aware of this class. You
can do that by adding it (and a description) to your config.yml:

    CustomMenuLink:
      base_classes:
        "SiteTree": "Page on site"
        "Product": "A Product"