# BoardLite
BoardLite is imageboard application crafted with AngularJS and PHP 7.1.

# Features
* Creating threads with image, video or just text
* Replying to threads with image, video or just text
* Multiple boards with different subjects
* Fast routing and data sending/receiving with AngularJS
* Ability to create own rules and boards (Admin only)
* Board creating and deleting via adminpanel (Admin only)
* Thread removing (Admin only)
* Login and register
* Lock/Unlock threads
* More in future!

# Get started
1. Clone/Download this repository
2. Upload files to your webhost/localhost
3. Import board_lite.sql to phpMyAdmin or similar
4. Fill values from static/config/Configuration.php
  - If you are using MySQL, no need for editing ``` $_CONFIG['db_conn_str'] ``` value.
5. Create some rules in static/config/Rules.php if you want.
6. Done!

# External libraries / frameworks
* [AngularJS](https://angularjs.org/)
* [jQuery](https://jquery.com/)
* [Bootstrap](https://getbootstrap.com/docs/3.3/)
* [Bootswatch theme](https://bootswatch.com/3/yeti/)

# Issues / Development
* This repository is under on going development and that may cause application to be broken sometimes.
* Feel free to report any ideas, bugs, issues by creating a [new issue](https://github.com/JokkeeZ/BoardLite/issues/new).
* Want to contribute?
  * You can fork this repo, write your fancy code and pull request!
  * All requests accepted, will be credited to their GitHub account.

# License
Licensed under MIT License, see more: [MIT License](https://github.com/JokkeeZ/BoardLite/blob/master/LICENSE)
* External libraries/frameworks licenses may differ from BoardLite.
