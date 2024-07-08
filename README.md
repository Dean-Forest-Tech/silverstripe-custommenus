# Custom Menus

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Dean-Forest-Tech/silverstripe-custommenus/badges/quality-score.png?b=2)](https://scrutinizer-ci.com/g/Dean-Forest-Tech/silverstripe-custommenus/?branch=2)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/Dean-Forest-Tech/silverstripe-custommenus/badges/code-intelligence.svg?b=2)](https://scrutinizer-ci.com/code-intelligence)
[![Build Status](https://travis-ci.org/Dean-Forest-Tech/silverstripe-custommenus.svg?branch=2)](https://travis-ci.org/Dean-Forest-Tech/silverstripe-custommenus)

A module to allow the creation of customised menus for your
SilverStripe site/App via SiteConfig.

## Requirements
SilverStripe 4 or 5

## Installation Instructions

The prefered way to install this module is via composer:

    composer require dft/silverstripe-custommenus

Alternativley you can download the module:

1. Download and add the module to the "custommenus" folder in your SilverStripe root folder
2. Run dev/build?flush=all

## Usage

Once the module is downloaded and installed, you can create menus
and add pages to them by visiting:

    http://www.yourwebsite.com/admin/settings

And then clicking on the "Menus" tab.

You will then need to create a `MenuHolder` (or use one of the installed defaults).

Now edit the `MenuHolder` and set the BaseClass (this will usually default to `SiteTree`). Once you have done this **and saved** you will be able to associate this menu item with you Page/Object using
the ObjectID field.

## Templates

`CustomMenus` comes with two options for rendering menus into a
template you can either:

### Use the bundled template

Adding `$RenderedCustomMenu(menu-slug)` to your template code make
use of the CustomMenu.ss include to render a UL (with a unique class name).

You can also overload this template in your own theme to generate
custom HTML for your navigation.

You can also enable the Title of the menu in the template by calling:

`$RenderedCustomMenu(menu-slug, true)`

### Loop through a list of menu items

Alternativley you can generate your own template by adding the 
following code:

````
    <% if $CustomMenu(main-menu) %><% with $CustomMenu(main-menu) >
        <ul class="menu">
            <% loop $Me %>
                <li class="$LinkingMode $FirstLast">
                    <a href="$Link">$MenuTitle</a>
                </li>
            <% end_loop %>
        </ul>
    <% end_with %><% end_if %>
````

### Access to the base `CustomMenuHolder`

When rendering the menu into a template, you can access the base holder using
the `$Holder` variable, from the example above you can use:

````
    <% if $CustomMenu(main-menu) %><% with $CustomMenu(main-menu) >
        <h2>$Holder.Title</h2>
        <ul class="menu">
            <% loop $Me %>
                <li class="$LinkingMode $FirstLast">
                    <a href="$Link">$MenuTitle</a>
                </li>
            <% end_loop %>
        </ul>
    <% end_with %><% end_if %>
````

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

**NOTE** You MUST ensure that any data object you want to add
to a menu has a defined `searchable_fields` config variable.

### Customising assotiations

Sometimes using the default fields for an object can cause issues
(maybe you want to search only MenuTitle for a page, for example).

You can customise how your linked classes are loaded via the following
additional config:

    CustomMenuLink:
      base_classes:
        'SilverStripe\CMS\Model\SiteTree':
          Title: 'A Page' # Name for this object in the CMS
          Label: 'Title' # The title field used when displaying t he assotiation in the CMS
          SearchFields: # Fields used to search for an assotiated object
            - Title
            - URLSegment
            - StockID