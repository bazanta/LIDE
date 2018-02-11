set :stage, :prod
set :symfony_env, "prod"

set :deploy_to, '/var/www/html/LIDE' # path on production server

set :controllers_to_clear, ["app_*.php"]
set :composer_install_flags, '--prefer-dist --no-interaction --optimize-autoloader'

# edit IP / Port and SSH user of your production server
server 'nomDomain_ou_IP', user: 'user', port: 22, roles: %w{app db web} 
SSHKit.config.command_map[:composer] = "php #{shared_path.join("composer.phar")}"
