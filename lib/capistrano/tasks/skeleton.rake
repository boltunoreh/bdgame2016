require "capistrano/symfony"

namespace :skeleton do

  desc "Dump exposed js routes"
  task :dump_js_routes do
    on roles(:all) do
      symfony_console "fos:js-routing:dump"
    end
  end

  desc "Dump api documentation"
  task :dump_api_doc do
    on roles(:all) do
      execute "mkdir -p #{current_path}/web/doc/ && php #{symfony_console_path} api:doc:dump --format=html --no-sandbox > #{current_path}/web/doc/api.html"
    end
  end

  task :migrate do
    on roles(:db) do
      symfony_console "doctrine:migrations:migrate", "--no-interaction"
    end
  end

  task :fixtures do
    on roles(:db) do
      symfony_console "doctrine:fixtures:load", "--append --no-interaction"
    end
  end

  task :create_admin do
    on roles(:db) do
      symfony_console "fos:user:create admin admin@admin.ru admin", "--super-admin -q"
    end
  end

  task :fix_media do
    on roles(:db) do
      execute "php #{symfony_console_path} sonata:media:fix-media-context"
    end
  end

end