# Laravel Pipedrive Notification Channel

A simple Pipedrive driver for Laravel's notification system.

## Features

Currently, the package allows you to easily create and update the following Pipedrive resources:

- Deals
- Activities
- Notes

These resources can easily be linked together, so you can create a deal and attach an activity or note in one easy action.

## How to

In order to install, simply use composer:

`$ composer require derjacques/laravel-pipedrive-notification-channel`

Heres a full example of how to use the notification channel:

```php
// app/Notifications/ExampleNotification

use DerJacques\PipedriveNotifications\PipedriveChannel;
use DerJacques\PipedriveNotifications\PipedriveMessage;

class ExampleNotification extends Notification
{

    public function via($notifiable)
    {
        return [PipedriveChannel::class];
    }

    public function toPipedrive($notifiable)
    {
        return
            (new PipedriveMessage())
                ->deal(function ($deal) {
                    $deal->stage(1)
                         ->title('new deal')
                         ->activity(function ($activity) {
                             $activity->subject('Call Jane')
                                      ->type('call');
                         })
                         ->activity(function ($activity) {
                             $activity->id(3)
                                      ->subject('Email Joe')
                                      ->type('mail');
                         })
                         ->note(function ($note) {
                             $note->content('Link to deal');
                         });
                })
                ->activity(function ($activity) {
                    $activity->subject('Buy milk')
                             ->type('shopping')
                             ->due('2017-12-18');
                });
    }
}
```

```php
// app/User.php

public function routeNotificationForPipedrive()
{
    return 'YOUR-PIPEDRIVE-KEY';
}
```
