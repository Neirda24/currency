# Currency

## Install

```sh
$ cd your/folder
$ git clone git@github.com:Neirda24/currency.git
$ cd currency
$ composer install
$ php app/console doctrine:database:create # if not already done
$ php app/console doctrine:migration:migrate
$ php app/console currency:currencies:populate
```
