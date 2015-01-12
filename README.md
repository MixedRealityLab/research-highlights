# Research Highlights
This is the repository for the Horizon CDT Research Highlights website source code.

## Configuration Instructions
* Configuration is based in the _src/config.php_ file
* User databases are all file-based in the _src/usr/_ directory
* Passwords are not stored and are username and salt dependant

* * The default salt (in _salt.php_) should be changed, passwords are the SHA1 of salt followed by the username
* * To calculate your password, use something like [DuckDuckGo](https://duckduckgo.com/?q=sha1+SALT_HEREmyusername1&ia=answer)

* A directory, _src\_private_, is not included here as it includes the live user database
* Various derivatives of the base website are found in _src\_modes/\*_

## Build Instructions
Build using the `ant` command:

* The basic build instruction is `ant build -Dmode=submission`
* The submission website can be built using `ant submission`
* You can override the _DOMAIN_ and _PATH_ php configuration variables with the ant flags `-Ddomain=...` and `-Dpath=...`
* _TIP_: Non-PHP files can use `@@@DOMAIN@@@`, `@@@PATH@@@` and `@@@URI_ROOT@@@` for the respective values, these are substituted at build-time

## Legal
This code is copyright Martin Porcheron, and licensed under the MIT licence.