<?php return array (
  'beyondcode/laravel-websockets' => 
  array (
    'providers' => 
    array (
      0 => 'BeyondCode\\LaravelWebSockets\\WebSocketsServiceProvider',
    ),
    'aliases' => 
    array (
      'WebSocketRouter' => 'BeyondCode\\LaravelWebSockets\\Facades\\WebSocketRouter',
    ),
  ),
  'facade/ignition' => 
  array (
    'providers' => 
    array (
      0 => 'Facade\\Ignition\\IgnitionServiceProvider',
    ),
    'aliases' => 
    array (
      'Flare' => 'Facade\\Ignition\\Facades\\Flare',
    ),
  ),
  'fruitcake/laravel-cors' => 
  array (
    'providers' => 
    array (
      0 => 'Fruitcake\\Cors\\CorsServiceProvider',
    ),
  ),
  'joselfonseca/lighthouse-graphql-passport-auth' => 
  array (
    'providers' => 
    array (
      0 => 'Joselfonseca\\LighthouseGraphQLPassport\\Providers\\LighthouseGraphQLPassportServiceProvider',
    ),
  ),
  'laravel/passport' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Passport\\PassportServiceProvider',
    ),
  ),
  'laravel/socialite' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Socialite\\SocialiteServiceProvider',
    ),
    'aliases' => 
    array (
      'Socialite' => 'Laravel\\Socialite\\Facades\\Socialite',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/collision' => 
  array (
    'providers' => 
    array (
      0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    ),
  ),
  'nuwave/lighthouse' => 
  array (
    'aliases' => 
    array (
      'graphql' => 'Nuwave\\Lighthouse\\GraphQL',
    ),
    'providers' => 
    array (
      0 => 'Nuwave\\Lighthouse\\LighthouseServiceProvider',
      1 => 'Nuwave\\Lighthouse\\Auth\\AuthServiceProvider',
      2 => 'Nuwave\\Lighthouse\\GlobalId\\GlobalIdServiceProvider',
      3 => 'Nuwave\\Lighthouse\\OrderBy\\OrderByServiceProvider',
      4 => 'Nuwave\\Lighthouse\\Pagination\\PaginationServiceProvider',
      5 => 'Nuwave\\Lighthouse\\Scout\\ScoutServiceProvider',
      6 => 'Nuwave\\Lighthouse\\SoftDeletes\\SoftDeletesServiceProvider',
      7 => 'Nuwave\\Lighthouse\\Validation\\ValidationServiceProvider',
    ),
  ),
  'spatie/laravel-permission' => 
  array (
    'providers' => 
    array (
      0 => 'Spatie\\Permission\\PermissionServiceProvider',
    ),
  ),
);