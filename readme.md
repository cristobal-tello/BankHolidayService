# BankHolidayService
The goal of this project is to create a web api service that will tell you if a given date is a bank holiday or not.

## How I created the project
You need composer installed in your computer. If you don't have it, you can download it from here: https://getcomposer.org/download/

Then, install the Symfony Maker Bundle to create code from the command line
```bash
composer require symfony/maker-bundle --dev
```

Finally, you can create the project
```bash
symfony new BankHolidayService
```
### Install needed packages
We need next packages:
#### Install doctrine
We need doctrine to work with the database
```bash
composer require symfony/orm-pack
```
#### Install dom-crawler
We need dom-crawler and css-selector to parse html
```bash
composer require symfony/dom-crawler
composer require symfony/css-selector
```
We need monolog as a logger
```bash
composer require monolog
```
#### Create a controller
Use the make:controller command to create a controller
```bash
php bin/console make:controller IsBankHolidayController
```
Adjust the route in the new controller to match your needs.
```php
#[Route('/isbankholiday/{isoCountryCode}/{locationName}/{date}', name: 'isbankholiday')]
```
#### Configuring the database access
In .env file, change the DATABASE_URL variable to match your database connection string.
```php
DATABASE_URL="mysql://<userofdb>:@127.0.0.1:3306/BankHoliday?serverVersion=8.0.32&charset=utf8mb4"
```

#### Creates the database
```bash
php bin/console doctrine:database:create
```
#### Drop the database
```bash
php bin/console doctrine:database:drop --force
```

#### Create entities
```bash
php bin/console make:entity <entity_name>
```

#### Configure relations
We need to configure how database tables are related.

##### In a Location entity
We need to add:
```php
#[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'locations')]
private $country;

#[ORM\OneToMany(mappedBy: 'location', targetEntity: Holiday::class)]
private $holidays;
public function __construct()
    {
        $this->holidays = new ArrayCollection();
    }
```

In country entity, add
```php
#[ORM\OneToMany(mappedBy: 'country', targetEntity: Location::class)]
private $locations;

public function __construct()
{
    $this->locations = new ArrayCollection();
}
```
In Holiday entity, add
```php
#[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'holidays')]
private $location;
```
#### Migrations
```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```
## How to run the project
./symfony server:start