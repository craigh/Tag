Tag
===

Tag is a content tagging module for the Zikula Application Framework.
Tag is a hook-based module that utilizes the most advanced Zikula 1.3 technology
including Doctrine 2.1 and Form lib.

The Tag UI is modeled after the WordPress tag interface and makes strong use of
Ajax/prototype technologies.

Tag has a plugin interface to allow other modules to define how their links
are presented on the tag page.

Tag provides special link decoding for use in shorturls.

Tag provides a TagCloud block and ContentType plugin

Tag can migrate/import crpTag data.

###Version 1.0.2

_Unreleased_

This release changes the Database and now stores the full Zikula_ModUrl object in the DB
instead of simply the url string. The string is maintained for BC, but methods utilizing
that data are deprecated and module others must move to using the full object before
version 1.0.3.

###Version 1.0.1

_19 February 2012_

This released focused on significant improvements in HTML-validation including reformatting
all links to use a 'slug'. There are also improvements in page titles based on the Tag.

Zikula 1.3.2+ required


###Version 1.0.0

_21 July 2011_

Initial release.

Zikula 1.3.0+ required