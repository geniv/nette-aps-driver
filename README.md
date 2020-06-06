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
- getListPerson(): IDataSource
- savePerson(int $idPerson = null, ...): bool
- deletePerson(int $idPerson): bool

- getListCard(): IDataSource
- saveCard(int $idCard = null, ...): bool
- deleteCard(int $idCard): bool
- issueCard(int $idSystem, int $idModule): bool

- getListFolder(): IDataSource
- saveFolder(int $idFolder = null, ...): bool
- deleteFolder(int $idFolder): bool

- getListAccessGroup(): IDataSource
- saveAccessGroup(int $idAccessGroup = null, ...): bool
- deleteAccessGroup(int $idAccessGroup): bool

- getListEvent(): IDataSource

- getListHoliday(): IDataSource
- saveHoliday(int $idHoliday = null, ...): bool
- deleteHoliday(int $idHoliday): bool

- getListModule(): IDataSource

- getListPersonAccessGroup(): IDataSource
- savePersonAccessGroup(int $idPerson = null, int $idAccessGroup = null): bool
- deletePersonAccessGroup(int $idPerson, int $idAccessGroup): bool

- getListSchedule(): IDataSource
- saveSchedule(int $idSchedule = null, ...): bool
- deleteSchedule(int $idSchedule): bool

- getListSystem(): IDataSource
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
