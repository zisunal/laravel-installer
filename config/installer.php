<?php
use Zisunal\LaravelInstaller\Livewire\Steps\WelcomeStep;
use Zisunal\LaravelInstaller\Livewire\Steps\RequirementsStep;
use Zisunal\LaravelInstaller\Livewire\Steps\EnvironmentStep;
use Zisunal\LaravelInstaller\Livewire\Steps\AdminUserStep;
use Zisunal\LaravelInstaller\Livewire\Steps\FinishStep;

return [
    /*
    | ---------------------------------------------------------------------
    | Installer Enabled
    | ---------------------------------------------------------------------
    | Enable or disable the installer.
    | true or false
    | This can be used to temporarily disable the installer without removing the package.
    */
    'enabled' => env('INSTALLER_ENABLED', true),

    /*
    | ---------------------------------------------------------------------
    | Route Prefix
    | ---------------------------------------------------------------------
    | The prefix for all installer routes.
    | For example, if you set this to 'setup', the installer will be accessible at /setup instead of /install.
    */
    'route_prefix' => env('INSTALLER_ROUTE_PREFIX', 'install'),

    /*
    | ---------------------------------------------------------------------
    | Redirect After Install
    | ---------------------------------------------------------------------
    | The URL to redirect to after successful installation.
    | This can be a route name or a URL path.
    | For example, you might want to redirect to the admin dashboard after installation.
    */
    'redirect_after_install' => env('INSTALLER_REDIRECT_AFTER_INSTALL', '/admin'),

    /*
    | ---------------------------------------------------------------------
    | Lock File
    | ---------------------------------------------------------------------
    | The path to the lock file that indicates the application has been installed.
    | By default, this is set to storage/app/installed.lock.
    | You can change this to a different location if needed.
    */
    'lock_file' => storage_path('app/installed.lock'),

    /*
    | ---------------------------------------------------------------------
    | Middleware
    | ---------------------------------------------------------------------
    | The middleware to apply to installer routes.
    | By default, this is set to [ 'web' ] to ensure session and CSRF protection.
    | You can add additional middleware if needed, such as authentication or localization middleware.
    */
    'middleware' => [ 'web' ],

    /*
    | ---------------------------------------------------------------------
    | Generate App Key
    | ---------------------------------------------------------------------
    | Whether to automatically generate an application key during installation.
    | This is typically needed for Laravel applications to ensure encrypted data is secure.
    | Set this to false if you want to generate the app key manually or if your application already has an app key set.
    */
    'generate_app_key' => true,

    /*
    | ---------------------------------------------------------------------
    | Run Migrations
    | ---------------------------------------------------------------------
    | Whether to automatically run database migrations during installation.
    | This is typically needed to set up the database schema for your application.
    | Set this to false if you want to run migrations manually or if your application does not require a database.
    */
    'run_migrations' => true,

    /*
    | ---------------------------------------------------------------------
    | Run Seeders
    | ---------------------------------------------------------------------
    | Whether to automatically run database seeders during installation.
    | This can be useful to populate the database with initial data, such as default settings or an admin user.
    | Set this to false if you want to run seeders manually or if your application does not require seeding.
    */
    'run_seeders' => true,

    /*
    | ---------------------------------------------------------------------
    | Database Seeder
    | ---------------------------------------------------------------------
    | The class name of the database seeder to run during installation.
    | This should be a seeder class that exists in your application, such as DatabaseSeeder or a custom seeder that you create for installation purposes.
    | If this is set to null, the installer will not run any seeders during installation.
    */
    'database_seeder' => null,

    /*
    | ---------------------------------------------------------------------
    | Storage Link
    | ---------------------------------------------------------------------
    | Whether to automatically create a symbolic link from public/storage to storage/app/public during installation.
    | This is typically needed for Laravel applications to allow access to files stored in the storage directory via the web.
    | Set this to false if you do not want the installer to create the storage link, or if you have already set up the storage link manually.
    */
    'storage_link' => true,

    /*
    | ---------------------------------------------------------------------
    | Admin Model
    | ---------------------------------------------------------------------
    | The Eloquent model class that represents the admin or owner user in your application.
    | This is used by the installer to create an admin user during installation.
    | Set this to the fully qualified class name of your admin user model, such as App\Models\User::class.
    */
    'admin_model' => \App\Models\User::class,

    /*
    | ---------------------------------------------------------------------
    | Admin Role Attribute
    | ---------------------------------------------------------------------
    | The attribute on the admin model that indicates the user's role or permissions.
    | This is used by the installer to assign the appropriate role or permissions to the admin user during installation.
    | Set this to the name of the attribute on your admin model that represents the user's role, such as 'role' or 'is_admin'.
    */
    'admin_role_attribute' => 'role',

    /*
    | ---------------------------------------------------------------------
    | Admin Role Value
    | ---------------------------------------------------------------------
    | The value to assign to the admin role attribute for the admin user created during installation.
    | This is used by the installer to ensure that the admin user has the correct role or permissions after installation.
    | Set this to the value that corresponds to the admin role in your application, such as 'admin', 'owner', or 'super_admin'.
    */
    'admin_role_value' => 'owner',

    /*
    | ---------------------------------------------------------------------
    | Installation Steps
    | ---------------------------------------------------------------------
    | The list of installer steps to run during installation.
    | Each step should be a Livewire component class that extends AbstractInstallerStep.
    | The installer will run these steps in the order they are defined in this array.
    | You can customize the steps by adding your own Livewire components or by modifying the existing ones provided by the package.
    */
    'steps' => [
        WelcomeStep::class,
        RequirementsStep::class,
        EnvironmentStep::class,
        AdminUserStep::class,
        FinishStep::class,
    ],

    /*
    | ---------------------------------------------------------------------
    | Requirements
    | ---------------------------------------------------------------------
    | The server requirements that must be met for the installation to proceed.
    | This includes the required PHP version, necessary PHP extensions, and file permissions.
    | The installer will check these requirements during the installation process and display any unmet requirements to the user.
    | You can customize the requirements based on the needs of your application, ensuring that users have the necessary environment to run your application successfully after installation.
    */
    'requirements' => [
        'php' => '8.2.0',
        'extensions' => [
            'openssl',
            'pdo',
            'mbstring',
            'tokenizer',
            'xml',
            'ctype',
            'json',
            'fileinfo',
            'bcmath',
            'gd',
            'curl',
            'intl',
            'zip',
        ],
        'permissions' => [
            "storage" => file_exists( storage_path( 'app' ) ) && is_writable( storage_path( 'app' ) ),
            "cache"   => file_exists( base_path( 'bootstrap/cache' ) ) && is_writable( base_path( 'bootstrap/cache' ) ),
        ],
    ],

    /*
    | ---------------------------------------------------------------------
    | Service Providers to Register
    | ---------------------------------------------------------------------
    | A list of service provider classes to register during installation.
    | This can be useful if you have custom service providers that need to be registered after the installer steps to function properly, such as providers that bind services or perform actions needed after installation.
    | You can add the fully qualified class names of your service providers to this array, and the installer will register them before running the installation steps.
    | This allows you to ensure that any necessary services or dependencies are available during the installation process, without requiring users to manually register providers in their application configuration.
    */
    'providers_to_register' => [
        //
    ],
    
    /*
    | ---------------------------------------------------------------------
    | Step Discovery
    | ---------------------------------------------------------------------
    | Whether to automatically discover installer steps in specified directories.
    | If enabled, the installer will scan the defined paths for Livewire components that extend AbstractInstallerStep and include them as installation steps.
    | This allows you to add new steps simply by creating a new Livewire component in the specified directory without needing to manually add it to the 'steps' array.
    | You can specify multiple paths for discovery, and the installer will merge discovered steps with the manually defined steps, ensuring that all valid steps are included in the installation process.
    */
    'discover' => [
        'enabled' => true,
        'paths' => [
            app_path('Installer/Steps') => 'App\\Installer\\Steps',
        ],
    ],
];