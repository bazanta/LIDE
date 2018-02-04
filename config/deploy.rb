set :symfony_directory_structure, 2

# Default value for :scm is :git
set :scm, :git

# Default branch is :master
set :branch,      "develop"
set :application, 'LIDE'
set :repo_url, 'git@github.com:bazanta/LIDE.git'
set :deploy_via,  :copy

# Symfony application path
set :app_path,              "app"
set :log_path,              fetch(:app_path) + "/logs"
set :cache_path,            fetch(:app_path) + "/cache"

set :linked_files,         [fetch(:app_path) + "/config/parameters.yml"]
set :linked_dirs, %w{app/logs node_modules}

# Dirs that need to be writable by the HTTP Server (i.e. cache, log dirs)
set :file_permissions_paths,         [fetch(:log_path), fetch(:cache_path)]

after 'deploy:starting', 'composer:install_executable'
after 'deploy:updated', 'npm:install'
set :gulp_tasks, 'all'

namespace :deploy do
  before :updated, 'gulp'
end

after 'deploy:finishing', 'deploy:cleanup'

# Default value for :format is :airbrussh.
# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: 'log/capistrano.log', color: :auto, truncate: :auto
set :format, :pretty

#set :log_level, :info
set :log_level, :debug
set :keep_releases, 5

# Default value for :pty is false
# set :pty, true