# hiddlestick

a brand new social network

## Projekt by:
* Luca Bäck - https://github.com/luca-baeck
* Fabian Tolksdorf - https://git.infint.de/tolksdfa



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
* Der footer befindet sich aus unerfindlichen Gründen nicht am Ende der Seite und wurde deshalb ausgeblendet.
* Der Header verschwindet rätselhafterweise beim scrollen nach unten, obwohl als sticky festgelegt.
* Die Fullscreen view und die Fähigkeit, Points zu verteilen sind aus Zeitgründen entfallen

## setup
* requirements erfüllen
* sql befehle ausführen
* dateien aus www/default in den DIR_DATA(->FileConfig) kopieren (Testdaten)
