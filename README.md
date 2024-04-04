# bra-wp-frontpage-popup
c Big River Analytics 2024 - David Bergeron

A custom plugin for adding an entry protocol modal to the homepage
of your wordpress website. This plugin is written in PHP but includes
some short javascript snippets.


## Installing
At the root of this folder you will find a .zip file containing the 
plugin. Install this plugin to wordpress however you like. Consider SFTP
or installing it through the wordpress dashboard.

## Using
This plugin will add a new field to you wordpress Admin Dashboard,
"Frontpage Popup". Inside this section of the dashboard you can edit
the text content of the modal popup.

## Modifying

If you would like to make changes to the Modal, you can do so 
in a few places. The HTML Markup used to render the modal is 
inside the function `fpp_add_dialog()` inside frontpage-popup.php. 
Here you can manipulate the HTML and add CSS classes if you wish.

If you would like to edit the functionality of the Modal, you can find the 
relevant javascript code inside the `/js` folder,
