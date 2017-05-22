# flarum-api-client by ![flagrow logo](https://avatars0.githubusercontent.com/u/16413865?v=3&s=15) [flagrow](https://discuss.flarum.org/d/1832-flagrow-extension-developer-group)

[![Latest Stable Version](https://poser.pugx.org/flagrow/flarum-api-client/v/stable)](https://packagist.org/packages/flagrow/flarum-api-client) [![Gitter](https://badges.gitter.im/flagrow/flarum-api-client.svg)](https://gitter.im/flagrow/chat)

This is a generic PHP API client for use in any project. You can simply include this package as a dependency to your project to use it.

### installation

```bash
composer require flagrow/flarum-api-client
```

### configuration

In order to start working with the client you might need a Flarum master key:

1. Generate a 40 character random, unguessable string, this is the Token needed for this package.
2. Manually add it to the `api_keys` table using phpmyadmin/adminer or another solution.

The master key is required to access non-public discussions and running actions otherwise reserved for
Flarum administrators.

### examples

A basic example:

```php
<?php

require_once "vendor/autoload.php";

use Flagrow\Flarum\Api\Flarum;

$api = new Flarum('http://example.com');

// A collection of discussions from the first page of your Forum index.
$discussions = $api->discussions()->request();
// Read a specific discussion.
$discussion = $api->discussions()->id(1)->request();
// Read the first page of users.
$users = $api->users()->request();
```

An authorized example:

```php
$api = Flarum('http://example.com', ['token' => '<insert-master-token>; userId=1']);
```

> The userId refers to a user that has admin permissions or the user you want to run actions for. Appending the userId setting to the token only works for Master keys.

### links

- [on github](https://github.com/flagrow/flarum-api-client)
- [on packagist](http://packagist.com/packages/flagrow/flarum-api-client)
- [issues](https://github.com/flagrow/flarum-api-client/issues)
- [changelog](https://github.com/flagrow/flarum-api-client/changelog.md)

> Flagrow is a collaboration of Flarum extension developers to provide quality, maintained extensions.