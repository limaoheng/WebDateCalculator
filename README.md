# WebDateCalculator

This project is an experiemental project using Angular 6 with PHP. This is for calculating days, weeks and weekdays between two dates.

Currently, for demonstration purpose, I chose Australia/Sydney to have special weekend on Friday, and Saturday. This can be configured, and in real world, this should be coming from some configuration or web service.

Furthermore, Adelaide has luckily chosen to be the only city in the world having public holidays. The holidays has been hardcoded in, just for demonstration purpose. This later could be re-configed or changed to use some other web service (or libraries) to achieve same goal.

## Development server

Run `ng serve` for a dev server. Navigate to `http://localhost:4200/`. The app will automatically reload if you change any of the source files.

Furthermore, run `php -S localhost:80 -t php/` to use build in PHP server to serve the backend.

## Test

Implemented PHP Unit test using PHPUnit. To run them, please use `vendor/bin/phpunit` under php directory.


## Build

Run `ng build` to build the project. The build artifacts will be stored in the `dist/` directory. Use the `--prod` flag for a production build.
