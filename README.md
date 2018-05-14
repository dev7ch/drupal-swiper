About
=====
Integrates the Swiper library into Drupal.

Current Options
---------------
Allows you to use Swiper in a few different ways

- As a library to be used with any other theme or module by calling
swiper_add() (N.B. This returns an array to be used as #attached on your
own render array).
- Integrates with Fields (swiper_fields)
- Adds a Views display mode (swiper_views)

About Swiper
----------------

Library available at https://github.com/nolimits4web/Swiper

Installation
============

Dependencies
------------

- [Libraries API 2.x](http://drupal.org/project/libraries)
- [Swiper Library](https://github.com/nolimits4web/Swiper)

Tasks
-----

1. Download the Swiper library from
https://github.com/nolimits4web/Swiper
(To use Composer instead, see instructions in the Composer section below)
2. Unzip the file and rename the folder to "Swiper" (pay attention to the
case of the letters)
3. Put the folder in one of the following places relative to drupal root.
    - libraries
    - profiles/PROFILE-NAME/libraries
    - sites/all/libraries
    - sites/SITE-NAME/libraries
4. The following files are required (last file is required for javascript
debugging)
    - dist/js/swiper.jquery.min.js
    - dist/js/swiper.min.js
    - dist/css/swiper.min.css
5. Ensure you have a valid path similar to this one for all files
    - Ex: libraries/Swiper/dist/js/swiper.jquery.min.js

That's it!


Composer
----------
Composer may be used to download the library as follows...

1. Add the following to composer.json _require_ section
  `
    "nolimits4web/Swiper": "3.4.2"
  `

2. Add the following to composer.json _installer-paths_ section
(if not already added)
  `
    "libraries/{$name}": ["type:drupal-library"]
  `

3. Add the following to composer.json _repositories_ section
(your version may differ)


    {
      "type": "package",
      "package": {
        "name": "nolimits4web/Swiper",
        "version": "3.4.2",
        "type": "drupal-library",
        "source": {
          "url": "https://github.com/nolimits4web/Swiper/archive/master.zip",
          "type": "zip"
        }
      }
    }

4. Open a command line terminal and navigate to the same directory as your
composer.json file and run
  `
    composer update
  `


Drush Make
----------

You can also use Drush Make to download the library automatically. Simply
copy/paste the 'swiper.make.example' to 'swiper.make' or copy the
contents of the make file into your own make file.

Usage
======

Options
-----------

No matter how you want to use Swiper (with fields or views) you need to
define "options" to tell Swiper how you want it to display. 
An options defines all the settings for displaying the slider. Things like slide
direction, speed, starting slide, etc... You can define as many options as
you like and on top of that they're all exportable! Which means you can carry
configuration of your wiper instances from one site to the next or
create features.

Go to admin/config/media/swiper

From there you can edit the default options and define new ones. These will
be listed as options in the various forms where you setup Swiper to
display.

Swiper Views
----------------

Swiper Views allows you to build views which display their results in
Swiper. Similarly to how you can output fields as an "HTML List" or
"Table", you can now select "Swiper" as an option.

Debugging
---------

You can toggle the development version of the library in the administrative
settings page. This will load the unminified version of the library.  Uncheck
this when moving to a production site to load the smaller minified version.


Export API
==========

You can export your Swiper options using D8 Configuration Management
by going to admin/config/development/configuration/single/export and choosing
Swiper options as the Configuration type.

External Links
==============

- [Wiki Documentation for Swiper]
(http://idangero.us/swiper/api)
