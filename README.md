# Laravel CRUD Generator

A Laravel package to generate CRUD operations quickly and easily using a YAML configuration file.

## Installation

You can install the package via Composer:

```bash
composer require usermp/laravel-generator:dev-master
```

## Configuration

Add the `LaravelGeneratorServiceProvider` class to the providers array in your `config/app.php` file:
    
```php
'providers' => [
	Usermp\LaravelGenerator\LaravelGeneratorServiceProvider::class,
],
```
## Usage

To generate a CRUD operation for a specific service, create a YAML configuration file and use the `generate:crud` Artisan command.

### Example YAML Configuration

Below is an example YAML configuration file for generating a CRUD for an `Post` service:

```yaml
service:
    name: Post
    fields:
        title: ["string", "required", "max:255"]
        slug: ["string", "required", "max:255"]
        description: ["text", "nullable"]
        author_id: ["integer", "User#id"]
    traits: [
      Illuminate\Notifications\Notifiable,
      Illuminate\Database\Eloquent\SoftDeletes
    ]
```

### Generating CRUD

Once you have your YAML configuration file ready, run the following command to generate the CRUD operations:

```bash
php artisan generate:crud path/to/your-config-file.yaml
```


Replace `path/to/your-config-file.yaml` with the actual path to your YAML configuration file.

## Features

- Generates migration files based on the YAML configuration.
- Creates Eloquent models with specified traits and fields.
- Generates controllers with standard CRUD methods.
- Creates form requests for validation rules.

## License

This package is open-sourced software licensed under the MIT license.

## Contact

For any inquiries or support, please reach out to usermp76@gmail.com.
