php-inotify
=========

Why
=========
In cases when you need to scan dir to find new files or files modifications, 
you probably will create some script and implements pulling mechanism.
That is good for small systems with less files but not efficient enough for big one.  
And that why we got inotify mechanism that generate event on file|dir changes 
like create, delete, change and many more that we can listen to.
More info in php manual. [here](https://www.php.net/manual/en/book.inotify.php) 

Installation
=========
```bash
composer require krowinski/bcmath-extended
```

Installing inotify extension for php 
=========
To listen on event we need php extension called inotify.
In most cases you just need to install using pecl

For example installation for php 7.1 on debian|ubuntu

```bash
apt-get update
apt-get install php-pear make php7.1-dev
pecl install inotify 
echo "extension=inotify.so" >>  /etc/php/7.1/cli/php.ini
```

Example
=========
You can find example in 
[example.php](https://github.com/krowinski/php-inotify/blob/master/example/example.php)
and events that you can listen to [InotifyEventCodeEnum.php](https://github.com/krowinski/php-inotify/blob/master/src/Inotify/InotifyEventCodeEnum.php)
Event implement Arrayable, JsonSerializable and __toString.
 
```php
Array
(
    [id] => 1
    [eventCode] => 256
    [eventDescription] => ON_CREATE - File or directory created in watched directory
    [uniqueId] => 0
    [fileName] => 2
    [pathName] => /tmp
    [customName] => test
    [pathWithFile] => /tmp/2
    [timestamp] => 1565610455
)
Array
(
    [id] => 1
    [eventCode] => 32
    [eventDescription] => ON_OPEN - File was opened
    [uniqueId] => 0
    [fileName] => 2
    [pathName] => /tmp
    [customName] => test
    [pathWithFile] => /tmp/2
    [timestamp] => 1565610455
)
Array
(
    [id] => 1
    [eventCode] => 4
    [eventDescription] => ON_ATTRIB - Metadata changed (e.g. permissions, mtime, etc.)
    [uniqueId] => 0
    [fileName] => 2
    [pathName] => /tmp
    [customName] => test
    [pathWithFile] => /tmp/2
    [timestamp] => 1565610455
)
Array
(
    [id] => 1
    [eventCode] => 8
    [eventDescription] => ON_CLOSE_WRITE - File opened for writing was closed
    [uniqueId] => 0
    [fileName] => 2
    [pathName] => /tmp
    [customName] => test
    [pathWithFile] => /tmp/2
    [timestamp] => 1565610455
)
Array
(
    [id] => 1
    [eventCode] => 512
    [eventDescription] => ON_DELETE - File or directory deleted in watched directory
    [uniqueId] => 0
    [fileName] => 2
    [pathName] => /tmp
    [customName] => test
    [pathWithFile] => /tmp/2
    [timestamp] => 1565610456
)
```

Where:
```
[id] => watch descriptor
[eventCode] => bit mask of events
[eventDescription] => human readable event description (can be UNKNOWN if not found in InotifyEventCodeEnum)
[uniqueId] => is a unique id to connect related events (e.g. IN_MOVE_FROM and IN_MOVE_TO)
[fileName] => name of a file (e.g. if a file was modified in a watched directory)
[pathName] => watched resource you give in configuration
[customName] => custom resource name for external parsing like "form-upload-dir" etc
[pathWithFile] => helper that contact pathName and fileName
[timestamp] => ...
```
