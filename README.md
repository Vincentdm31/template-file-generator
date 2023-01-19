# Template File Generator

**Introduction**

This package allows you to generate files based on template with data customization.

<br/>

**Installation**

```
composer require laravins/template-file-generator
```

This will install the package inside your project and setup example files.

- `Config file` located at `config/template-file-generator/example-generator.php`
- `Template files` located at `resources/template-file-generator/example-generator/crud-views`

<br/>

***
<br/>

**Config file**
<br/>

This config file will:
- Use template folder located at `projet_path/resources/template_file-generator/example-generator/crud-views`
- Generate two files `list.blade.php` and `edit.blade.php` in `project_path/resources/views/users`.

- Replace each occurences of `%thanks%` by `Thanks for using this package 👌` of generated `list.blade.php` file.
- Replace each occurences of `%stars%` by `Road to 50 ⭐` of generated `list.blade.php` file.
- Replace each occurences of `%heart%` by `❤️` of generated `edit.blade.php` file.
- Replace each occurences of `%dev_attitude%` by `lazy` of generated `edit.blade.php` file.

```php
<?php

return [
    'files' => [
        // Put here the list of files
        'list.blade.php' => [
            // Put here variables to replace
            'thanks' => 'Thanks for using this package 👌',
            'stars' => 'Road to 50 ⭐',
        ],
        'edit.blade.php' => [
            'heart' => '❤️',
            'dev_attitude' => 'lazy',
        ]
    ],
    'config' => [
        // Path of your template folder
        'base_path' => 'template-file-generator/example-generator/crud-views',
        // Prefix of template folder path. Please, refer to prefixes section
        'base_path_prefix' => 'resource',
        // Path of your target generated folder
        'target_path' => 'views/users',
        // Prefix of template folder path. Please, refer to prefixes section
        'target_path_prefix' => 'resource'
    ],
];
```
<br/>

***
<br/>

**Prefixes**

Allowed prefixes used for `base_path_prefix` and `target_path_prefix` are:

| Prefix        | Target          |
| ------------- |----------------:|
| app           | app_path()      |  
| base          | base_path()     |
| config        | config_path()   |
| resource      | resource_path() |
| database      | database_path() |
| lang          | lang_path()     |
| public        | public_path()   |
| storage       | storage_path()  |


<br/>

***
<br/>

**Template files**

You need to set variables that you want to be replaced in `%` delimiter like `%var_to_change_%`.

```html
<p>Made with %heart%</p>
<p>For %dev_attitude% developers</p>
```

This will be converted into 
```html
<p>Made with ❤️</p>
<p>For lazy developers</p>
```