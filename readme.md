# flarum-api-client by ![flagrow logo](https://avatars0.githubusercontent.com/u/16413865?v=3&s=15) [flagrow](https://discuss.flarum.org/d/1832-flagrow-extension-developer-group)

[![Latest Stable Version](https://poser.pugx.org/flagrow/flarum-api-client/v/stable)](https://packagist.org/packages/flagrow/flarum-api-client) [![Gitter](https://badges.gitter.im/flagrow/flarum-api-client.svg)](https://gitter.im/flagrow/chat)

This is a generic PHP API client for use in any project. You can simply include this package as a dependency to your project to use it.

### goals

- Improve coverage of all functionality in Flarum.

For this package we will implement new calls based on your requests. Please submit them in the [issue tracker on Github](https://github.com/flagrow/flarum-api-client/issues).

For a complete overview of our releases, please visit the [milestones tracker](https://github.com/flagrow/flarum-api-client/milestones) on Github.

### installation

```bash
composer require flagrow/flarum-api-client
```

### configuration

In order to start working with the client you might need a Flarum master key:

1. Generate a 40 character random, unguessable string, this is the Token needed for this package.
2. Manually add it to the `api_keys` table using phpmyadmin/adminer or another solution.

### examples

A basic example:

```php
$api = new Flagrow\Flarum\Api\Client('http://example.com/api/');
// load the first discussion of your Example.com forum:
$discussion = $api->discussions(1);
```

An authorized example:

```php
$api = new Flagrow\Flarum\Api\Client('http://example.com/api/', 'randomtoken; userId=1');
// generate a new tag for your Example.com forum:
$tag = $api->createTag('Amazing Title', 'amazing-slug');
```

> The userId refers to a user that has admin permissions or the user you want to run actions for. Appending the userId setting to the token only works for Master keys.

### links

- [on github](https://github.com/flagrow/flarum-api-client)
- [on packagist](http://packagist.com/packages/flagrow/flarum-api-client)
- [issues](https://github.com/flagrow/flarum-api-client/issues)
- [changelog](https://github.com/flagrow/flarum-api-client/changelog.md)
- [flagrow extensions](https://github.com/flagrow?utf8=%E2%9C%93&query=flarum-ext-)
- [flagrow group information](http://flagrow.github.io/)

> Flagrow is a collaboration of Flarum extension developers to provide quality, maintained extensions.