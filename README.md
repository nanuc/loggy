# Loggy
This is a helper to include the [Loggy](https://loggy.live) in your Laravel projects. Loggy enables you to quickly send log messages to a central place where they are displayed in real-time.


## Installation
### Install via composer
You can install the package via composer:
```
composer require nanuc/loggy
```
### Generate a key

Afterwards generate a key so that Loggy is able to identify logs coming from you:
```
php artisan loggy:key
```
The output of the command shows you your future Loggy link. Open this link now in a browser before you test your Loggy installation.

You can set the key `LOGGY_KEY` in `.env` to anything you want. Just remember that all apps with the same key share the same URL.

### Test Loggy
You can send a test entry to Loggy:
```
php artisan loggy:test
```
If everything went right you should see two entries popping up on the openend website.

## Usage
Just put the following in your Laravel code to send information to Loggy:
```
loggy($myMessage);
```
`myMessage` can be basically anything.

### Time measurement
You can start and stop a time measurement with 
```
loggy_start('name-of-time-measurement');
do_something();
loggy_stop('name-of-time-measurement');
```
The name is optional and defaults to "Measurement".

### Blade
You can also use loggy in your Blade views.
```
@loggy($myMessage)
```

## More information
### Why?
When starting to work with Laravel Vapor we realized how great this product of the Laravel team is!
But our app showed different behaviour on the production system than in our development system.
Finding the issues was very hard because logging in the Vapor console is not as intuitive as we were used to from our local systems.
So we built a helper that just sends logging entries to a dashboard in realtime - "Loggy" was born.
We asked ourselves the question: why not open it to any Laravel developer? We found no negative answer, so: here we go!
And it's not just great for Vapor - it helps even with your local development.
### What does it do with my data?
Receive it - display it - forget it. Data is persisted in no way. Which means your Loggy page has to be open in a browser to receive the data.


