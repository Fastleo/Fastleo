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
    
    public $fastleo = false|title;
    public $fastleo_columns = [];

****Extend Fastleo setting model****
    
    public $fastleo = 'ModelName';

    public $fastleo_columns = [
        'column' => [
            'title' => 'Title',
            'type' => 'string[text|integer|checkbox|select|include]',
            'media' => false,
            'tinymce' => false,
            'visible' => true,
            'description' => '',
            'placeholder' => '',
            
            // if type == select
            'multiple' => false,
            'data' => [], // array, example [10,20,30]
            'data' => '', // string, example 'App\User:id:email:name'
                          // 'Model:key:value:?order?'
                          // 'Model:key:value:where:?order?'
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