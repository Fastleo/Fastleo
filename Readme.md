**Install Fastleo**

    composer require fastleo/fastleo

****Publish the storage and package****

    php artisan storage:link
    php artisan vendor:publish --tag=fastleo --force

****Make migrate****

    php artisan migrate

****Create admin****

    php artisan fastleo:user --admin

****Clear cache****

    php artisan fastleo:clear
    composer dump-autoload

****Enter in fastleo****

    http://site.org/fastleo

****Fastleo setting model****
    
    public $fastleo = ModelName|title;

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
            // example 'Model:key:value:where:?order?'
            'data' => [],
            'data' => 'Model:key:value',
            
            // if type == include
            // example 'App\UserImage'
            'data' => 'Relationships class'
            
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