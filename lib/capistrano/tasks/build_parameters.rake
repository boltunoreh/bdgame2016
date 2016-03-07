namespace :deploy do
 task :build_parameters do
   on roles (:app) do
     invoke "composer:run", 'run-script', 'build_parameters'
   end
 end
end