# wp-plugin-epfl-partner-universities
WordPress Shortcodes to show all partner universities for IN and OUT exchange.

**epfl-partner-universities** is a WordPress plugin used to show all partner universities for IN and OUT exchange.
This plugin calls an IS-Academia API to get the list of partners.
PHP and javascript are the main languages used to code the plugin.

## Get epfl-partner-universities
To get the plugin :
- Download **wp-dev** that you will find at the following url: _https://github.com/epfl-si/wp-dev_ ;
- Find the **epfl-partner-universities** EPFL plugin at the following path: _wp-dev\volumes\wp\6.1.3\wp-content\plugins\epfl-partner-universities_ ;
- Import **epfl-partner-universities** in your favorite IDE ;
- ✅

## Required environment
To access to the IS-Academia API, we need to add the kubernet cluster IP address to the authorized hosts.

## epfl-partner-universities file organization
The files of the plugin are organised as below:

_epfl-partner-universities.php_ :
Contains the call to the ISA web service and the html code to display according to the call result for the IN Exchange.

_epfl-partner-universities_map.php_ :
Contains the call to the ISA web service and the html code to display according to the call result for the OUT Exchange.

_partner-universities-traductions.php_ :
Utility class for traslantions.

_partner-universities-utils.php_ :
Utility class for all common methods.