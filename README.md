# Currency

## Install

### Set up the BDD

```sh
$ cd your/folder
$ git clone git@github.com:Neirda24/currency.git
$ cd currency
$ composer install
$ php app/console doctrine:database:create      # if not already done
$ php app/console doctrine:migration:migrate
$ php app/console doctrine:fixtures:load        # if you want to initiate the BDD with some products.
$ php app/console currency:currencies:populate
```

### Set up the CSS / JS

```sh
$ npm install
$ ./node_modules/.bin/bower install
$ ./node_modules/.bin/gulp build
```
