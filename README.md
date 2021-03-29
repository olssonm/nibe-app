## Nibe Uplink-app

A ready-to-use app for local data-gathering for NIBE's heat systems via the NIBE Uplink API.

<img width="1164" alt="Screenshot 2021-03-26 at 09 39 03" src="https://user-images.githubusercontent.com/907114/112606094-4735ce80-8e18-11eb-9955-dba435aa2411.png">

This app is very much opinionated and built for myself and my own use cases, but feel free to fork and use. If there are any missing features or bugs, [PRs are very much welcome](https://github.com/olssonm/nibe-app/pulls).

I've also [released an oauth-provider](https://github.com/olssonm/oauth2-nibe) built on [thephpleague/oauth2-client](https://github.com/thephpleague/oauth2-client) if you rather build your own app from scratch.

*Note: This application only communicates via the Nibe Uplink-API, not the myUplink-API which for example the S-series uses.*

## Prerequisites

As this project is built on the Laravel-framework, getting started is quite simple. Just make sure your server and/or host fullfill the requirements [listed in the documentation](https://laravel.com/docs/8.x/deployment#server-requirements). 

This application should be able to run on for example a Raspberry Pi or a low end VPS.

## Installation

You can either clone this repo via Git (or download it directly) and then install the dependencies:

```
$ git clone olssonm/nibe-app
$ cd olssonm/nibe-app
$ composer install
```

Or install it via `create-project`:

```
$ composer create-project olssonm/nibe-app
```

Then edit your .env-file to setup your URL and database- and NIBE-credentials.

Finally, run the migrations:

```
$ php artisan migrate
```

## Usage

To be able to use this application you will first need to go through a few steps:

1. Connect your heat system to [Nibe Uplink](https://www.nibeuplink.com/)
2. Create a developer-account on the [Nibe Uplink developer-portal](https://api.nibeuplink.com/) and register a new application. The callback-URL should be `https://{yoursite.com}/auth/callback`

After that you can navigate to your configured application URL and go through the setup-wizard where you get to select which system to connect:

<img width="300" alt="Screenshot 2021-03-26 at 10 10 59" src="https://user-images.githubusercontent.com/907114/112609238-9a5d5080-8e1b-11eb-91ba-ab12ec9ca9cc.png">

### Data fetching

You can now setup a cron job to fetch data from the NIBE API, this is done via the `nibe:fetch`-command:

```
$ php artisan nibe:fetch
```

While it is possible to retrieve data every minute or so, your heating system will probably only connect a few times per hour and a higher resolution than this might be unecessary and just cause the database to grow rapidly. 

A recommendation is to retrieve new data every 15-30 minutes or so.

### Import

If your heating system has been connected to NIBE Uplink for a while you might want to import historical data. This is not possible via the API, but there's an import method for this.

On the NIBE website, download a CSV containing BT7, BT1 and BT50-parameters and place the file in `/storage/app/import` (without renaming it), and run the `nibe:import`-command and it will try to insert all the data in the database. 

```
$ php artisan nibe:import
```

*Note: you have to setup the application and go through the setup wizard before this step.*

### Backups

[`spatie/laravel-backup`](https://github.com/spatie/laravel-backup) is installed with this package. Edit the .env-file to set your backup settings and then run `php artisan backup:run` to take a backup.

Check out the [full documentation for more information](https://spatie.be/docs/laravel-backup/v7/introduction) regarding backups.

## Todo

Future things to improve or implement. [PRs are welcome](https://github.com/olssonm/nibe-app/pulls)!

- Read more parameters than the four that is currently read
- Implement a custom range with a datepicker instead of "just" predefined ranges
- Improve dataset-algorithm; currently for longer ranges an average for a whole days is calculated – which may be missleading if for example more datapoints is available during the day than the night
- Read the smart-pricing levels (I myself do not have this active and can therefore not read those levels)
- Support more systems than one
- Methods via the WRITESYSTEM-scope to enable updating settings remotely (might require a premium account)

## License

The MIT License (MIT). Please see the [LICENSE.md](LICENSE.md) for more information.

© 2021 [Marcus Olsson](https://marcusolsson.me).
