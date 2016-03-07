# config valid only for current version of Capistrano
lock '3.4.0'

set :application, 'skeleton'
set :repo_url, 'https://bitbucket.org/prodhub/skeleton-backend'
set :symfony_directory_structure, 2

# Share files/directories between releases
set :linked_files, ["app/config/parameters.yml"]
set :linked_dirs, ["app/logs", "vendor"]

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, '/data/sites/docs.production.adwatch.ru/public'

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
# set :log_level, :debug

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# set :linked_files, fetch(:linked_files, []).push('config/database.yml', 'config/secrets.yml')

# Default value for linked_dirs is []
# set :linked_dirs, fetch(:linked_dirs, []).push('log', 'tmp/pids', 'tmp/cache', 'tmp/sockets', 'vendor/bundle', 'public/system')

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
# set :keep_releases, 5

namespace :skeleton do

  desc "Dump exposed js routes"
  task :dump_js_routes do
    on roles(:all) do
      symfony_console('fos:js-routing:dump')
    end
  end

  task :migrate do
    on roles(:all) do
      symfony_console('doctrine:migrations:migrate', '--no-interaction')
    end
  end

  task :create_admin do
    on roles(:all) do
      symfony_console('fos:user:create admin admin@admin.ru admin', '--super-admin -q')
    end
  end

  task :fix_media do
    on roles(:all) do
      symfony_console('sonata:media:fix-media-context')
    end
  end

end


namespace :deploy do

  after :restart, :clear_cache do
    on roles(:web), in: :groups, limit: 3, wait: 10 do
      # Here we can do anything such as:
      # within release_path do
      #   execute :rake, 'cache:clear'
      # end
    end
  end
end

after 'deploy:updated', 'skeleton:migrate'
after 'deploy:updated', 'skeleton:fix_media'
after 'deploy:updated', 'symfony:assets:install'
after 'deploy:updated', 'skeleton:dump_js_routes'


