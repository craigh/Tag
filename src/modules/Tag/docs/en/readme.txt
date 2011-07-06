README
======

Tag is a module to provide a content-tagging mechanism for the Zikula Application
Framework.

Tag is a **hooks-based** module. In order to utilize it, you must hook it to 
the content module(s) you wish to tag items within. In order to do this, click
the 'hooks' menu item from the content-creation module (e.g. News) and then
drag the Tag hook ('Content tagging service') onto the 'ui_hooks' content area
(e.g. 'News Articles Hooks' or similar) to 'connect' them.

After you have hooked the modules together, you may tag new items or edit old
items to tag them from the content-providing module's create/edit function.