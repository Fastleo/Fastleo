**Install Fastleo**

    composer require fastleo/fastleo

****Publish the package’s****

    php artisan vendor:publish --tag=fastleo --force

****Make migrate****

    php artisan migrate

****Create admin****

    php artisan fastleo:admin

****Clear cache****

    php artisan fastleo:clear
    composer dump-autoload

****Enter in fastleo****

    http://site.org/fastleo

****Fastleo setting model****
    
    public $fastleo = false;
    public $fastleo_model = [];
    public $fastleo_columns = [];

****Extend Fastleo setting model****
    
    public $fastleo_model = [
        'menu' => true,
        'name' => 'ModelName',
        'title' => 'Model Title',
        'icon' => 'fas fa-box-open', // https://fontawesome.com/icons
    ];

    public $fastleo_columns = [
        'column' => [
            'title' => 'Column Name',
            'type' => 'string', // string|text|integer|checkbox|select|include
            'media' => false,
            'tinymce' => false,
            'visible' => true,
            'required' => false,
            'disabled' => false,
            'description' => '',
            'placeholder' => '',
            'multiple' => false,
            'data' => [], // array, example [10,20,30]
            'data' => '', // example 'App\User:id:email:admin:1'
                          // 'Model:column_key:column_value'
                          // 'Model:column_key:column_value:where:value'
                          // 'Model::column_value:where:value'
        ],
    ];
    
