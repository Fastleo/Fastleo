## About

Admin panel for laravel

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Used

**Install Fastleo**

    composer require fastleo/fastleo
    php artisan migrate
    php artisan storage:link
    php artisan vendor:publish --tag=fastleo --force
    
**After update Fastleo**

    php artisan migrate
    php artisan vendor:publish --tag=fastleo --force

****Create admin****

    php artisan fastleo:user --admin

****Clear cache****

    php artisan fastleo:clear
    composer dump-autoload

****Enter in Fastleo****

    http://site.org/fastleo

****Fastleo setting model****
    
    public $fastleo = ModelName[title];
    
    public $fastleo_group = GroupModels[title];
    
    public $fastleo_description = ModelDescription[text];

    public $fastleo_columns = [
    
        'column' => [
        
            'title' => 'Title',
            'type' => 'string[number|checkbox|text|select|include]',
            'visible' => true,
            'description' => '',
            'placeholder' => '',
            
            // if type == string
            'media' => false,
            
            // if type == text
            'tinymce' => false,
            
            // if type == select
            'multiple' => false,
            
            // if type == select
            // example [10,20,30]
            // example 'App\User:id:email:name'
            // example 'Model:key:value:?order?'
            // example 'Model:key:value:where:value:?order?'
            'data' => [],
            'data' => 'Model:key:value',
            
            // if type == include
            'model' => 'Relationship model' // example 'App\UserImages'
            'relation' => 'Relationship method' // example 'images'
            
        ],
    ];
    
****Fastleo local****

    "require": {
        "fastleo/fastleo": "@dev"
    },
    "repositories": [
        {
            "type": "path",
            "url": "../fastleo",
            "options": {
                "symlink": true
            }
        }
    ]

    composer update fastleo/fastleo --prefer-source