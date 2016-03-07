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