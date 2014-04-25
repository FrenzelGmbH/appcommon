appcommon
=========

Common Classes for our Apps

Installation
============

Install package via composer "frenzelgmbh/appcommon": "dev-master"

Update config file *config/web.php* and *config/db.php*

```php
// app/config/web.php
return [
    'modules' => [
        'appcommon' => [
            'class' => 'frenzelgmbh\appcommon\Module',
            // set custom module properties here ...
        ],
    ],
];
// app/config/db.php
return [
        'class' => 'yii\db\Connection',
        // set up db info
];
```

Run migration file
php yii migrate --migrationPath=@vendor/frenzelgmbh/appcommon/migrations
