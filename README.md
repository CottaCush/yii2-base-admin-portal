#  Yii2 Base Admin Portal
> A Yii 2 Base Admin Portal Template Template


**Features**

- [Yii framework](http://www.yiiframework.com/) as the PHP MVC framework.
 
- Security - It sets some headers that projects applications against click-jacking and XSS.

- Assets version - This fixes issue with updates to js and css files and cached browser files.

- New Relic - Ensures that the proper routes show up in the new relic monitoring dashboard.

- Continuous Integration - Sample ant build.xml file that can be easily modified.

## Requirements

The minimum requirement by this project template that your Web server supports PHP 8.0.

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
composer global require "fxp/composer-asset-plugin:~1.4"
composer create-project --prefer-dist cottacush/yii2-base-admin-portal new_project
~~~



## Build

Dependencies 

- [Ant](http://ant.apache.org/) 

Run build
```
ant
```

## Environment Variables
Make a copy of `.env.sample` to `.env` in the env directory.

## Starting the Application
You can run the application in development mode by running this command from the project directory:

```
./yii serve
```

This will start the application on port 8080.

To run on a different port, run the following command from the project directory:

```
./yii serve localhost:<port>
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email <developers@cottacush.com> instead of using the issue tracker.

## Credits

- Adegoke Obasa <goke@cottacush.com>
- [All Contributors](https://github.com/CottaCush/yii2-base-admin-portal/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
