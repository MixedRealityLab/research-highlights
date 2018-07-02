# This project has been abanonded and is unsupported.

# Research Highlights
This is the repository for a PHP + flat-file DB system for publishing highlights from research conducted within a Centre for Doctoral Training. The system was developed at The University of Nottingham, by [Martin Porcheron](https://www.porcheron.uk/).

## Configuration Instructions
* Configuration is based in the _src/config.php_ file
* User databases are all file-based in the _src/usr/_ directory
* Passwords are not stored and are username and salt dependant

* * The default salt (in _salt.php_) should be changed, passwords are the SHA1 of salt followed by the username
* * To calculate your password, use something like [DuckDuckGo](https://duckduckgo.com/?q=sha1+SALT_HEREmyusername1&ia=answer)

* A directory, _src\_private_, is not included here as it includes the live user database used for the [Horizon CDT Research Highlights](http://cdt.horizon.ac.uk/highlights/)

## Build Instructions
Build using the `ant` command:

* The basic build instruction is `ant quick` 
* You can override the _DOMAIN_ and _PATH_ php configuration variables with the ant flags `-Ddomain=...` and `-Dpath=...`
* There is also a `full` target for Ant, this compresses JS and CSS files using the YUI Compressor

## Legal
This code is copyright Martin Porcheron, and licensed under the MIT licence. 
