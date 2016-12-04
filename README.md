# BoardLite
BoardLite is imageboard application crafted with AngularJS and PHP 7.0.

# Features
* Creating threads with image, video or just text
* Replying to threads with image, video or just text
* Multiple boards with different communication subjects
* Fast routing and data sending/receiving with AngularJS
* Ability to create own rules and boards (Admin only)

# Get started
1. Clone/Download this repository
2. Upload files to your webhost/localhost
3. Import board_lite.sql to phpMyAdmin or similar
4. Fill values form static/config/Configuration.php
  - If you are using MySQL, no need for editing ``` $_CONFIG['db_conn_str'] ``` value.
5. Create some rules in static/config/Rules.php if you want.
6. I think you are good to go!

# External libraries
* [AngularJS](https://angularjs.org/)
* [jQuery](https://jquery.com/)
* [Bootstrap](http://getbootstrap.com/)
* [Bootswatch theme](https://bootswatch.com/yeti/)

# License
Licensed under MIT License, see more: [MIT License](https://github.com/JokkeeZ/BoardLite/blob/master/LICENSE)
