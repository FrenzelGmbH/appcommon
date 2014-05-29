appcommon
=========

Common Classes for our Apps - as we are currently moving to a complete modular development!
Normally appcommon is used by every "app" and mostly by our modules. This is because the widgets
are mostly extended from the base classes in here...

If you have any suggestions or whishes let us know, if it makes sense, we are always willing to change our modules!

One thing that is very important, that modules should be as "flexible" as possible, so they can be addopted into
any project without changing e.g. the database structure.

For styling puposes we considure that you are using the bootstrap css framework. We have no plans to support others!

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

//add the following line to your Assets-Config in the depends section

'frenzelgmbh\appcommon\commonAsset'

```

Run migration file
php yii migrate --migrationPath=@vendor/frenzelgmbh/appcommon/migrations
