Nette APS driver
================

via APS 400 and APS mini Plus

Installation
------------

```sh
$ composer require geniv/nette-aps-driver
```
or
```json
"geniv/nette-aps-driver": "^1.0"
```

require:
```json
"php": ">=7.0",
"dibi/dibi": ">=3.0",
"nette/utils": ">=2.4"
```

methods:
--------
```
via code
```

Include in application
----------------------

neon configure services:
```neon
services:
    - ApsDriver(@mssql.connection)
```

usage:
```php
$aps = $this->context->getByType(\ApsDriver::class);

$aps->getListPerson()->fetchAll();
$aps->getListPerson()->where(['ID_Folder'=>7])->fetchAll();
$aps->getListPerson()->where(['IsDeleted'=>true])->fetchAll();
```
