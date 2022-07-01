# hiddlestick

a brand new social network

## Requirements
* MariaDB v 10.7.3
* libgd
* php v 7.4

### php-modules (package)
* gd (php7-gd)
* header_module
* fileinfo (php7-fileinfo)

`names of the packages are those on opensuse`

### php.ini settings
`file_uploads = On`

## Notes
* When using Firefox the expires attribute of cookies is always session (why?)
* Die Suche funktioniert nicht, bzw kann nur alle Posts, User, Sticker Anzeigen, da aus rätselhaften Gründen und extremen Zeitmangel nur leere Ergebnisse von der Datenbank zurückgegeben werden.
* Die Sortierung der Suche funktioniert noch nicht, ist aber in html und php schon teilweise implementiert
