<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'todo-laravel');

// Project repository
set('repository', 'https://github.com/jetgamer16/todo-laravel.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', ['.env']);
add('shared_dirs', ['storage','public']);

// Writable dirs by web server 
add('writable_dirs', ['bootstrap/cache','storage']);


// Hosts

host('172.16.221.122')->user('prod-ud4-deployer')
    ->identityFile('~/.ssh/id_rsa')
    ->set('deploy_path', '/var/www/prod-ud4-deployer/html/todo-laravel');    
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

task('upload:env', function () {
 upload('.env.production', '{{deploy_path}}/shared/.env');
})->desc('Environment setup');

task('reload:php-fpm', function () {
 run('sudo /etc/init.d/php8.1-fpm restart');
});
# inclusió en el cicle de desplegament
after('deploy', 'reload:php-fpm');


