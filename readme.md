# test-stimulus
I used 6.4 Symfony version (https://symfony.com/doc/6.4/setup.html)

## How I created the project
You need composer installed in your computer. If you don't have it, you can download it from here: https://getcomposer.org/download/

Then, install the Symfony Maker Bundle to create code from the command line
```bash
composer create-project symfony/skeleton:"6.4.*" .
composer require webapp
```
### Install needed packages
We need next packages:
#### Install doctrine
We need doctrine to work with the database
```bash
composer require symfony/orm-pack
```
We need monolog as a logger
```bash
composer require monolog
```
#### Configuring the database access
In .env file (or .env.local if you're using docker), change the DATABASE_URL variable to match your database connection string,in this case we will use mySQL
```php
DATABASE_URL="mysql://<userofdb>:@127.0.0.1:3306/<dbname>?serverVersion=8.0.32&charset=utf8mb4"
```
In docker (.env.local file)
```php
APP_ENV=dev
DATABASE_URL=mysql://<dbuserindockercompose>:<passwordindockercompose>@db:3306/<dbname>
```
#### Drop the database
```bash
php bin/console doctrine:database:drop --force
```
#### Creates the database
```bash
php bin/console doctrine:database:create
```

#### Create a controller
Use the make:controller command to create a controller
```bash
php bin/console make:controller HomeControNAller
```
Adjust the route in the new controller to match your needs.
```php
#[Route('/', name: 'app_home')]
```
#### Create entities
```bash
php bin/console make:entity <entity_name>
```


#### Create entities
```bash
php bin/console make:entity <entity_name>
```

#### Configure relations
We need to configure how database tables are related.


In customer.php entity
#[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'name')]


#### Migrations
```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```
## Forms

composer require symfony/form














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
