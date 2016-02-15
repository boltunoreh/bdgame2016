MINIMUM_VAGRANT_VERSION = "1.8.1"

if Gem::Version.new(Vagrant::VERSION) < Gem::Version.new(MINIMUM_VAGRANT_VERSION)

    abort %Q[
You need to upgrade Vagrant ( https://www.vagrantup.com/downloads.html )
Needed vagrant version: >=#{MINIMUM_VAGRANT_VERSION}
Current vagrant version: #{Vagrant::VERSION}
]
end

unless Vagrant.has_plugin?("vagrant-vbguest")

    abort %Q[
Vagrant plugin ( vagrant-vbguest ) is not installed!
To install, type this in WINDOWS terminal:
vagrant plugin install vagrant-vbguest
]
end


require "rubygems"
require 'json'
require 'fileutils'

require './.vagrant/helpers/os.rb'

FileUtils.cp(".vagrant/local.json.dist", ".vagrant/local.json") unless File.file?(".vagrant/local.json")

# --
# Вынесем параметры в отдельный json конфиг
# --
parameters = JSON.parse(open(".vagrant/local.json").read)

unless ['54','55','56','70'].include?(parameters['php'])
    abort %Q[
You need to specify correct php version
Available php versions: 54 (php5.4), 55 (php5.5), 56 (php5.6), 70 (php7.0)
Your version: #{parameters['php']}
]
end

IP_ADDRESS = parameters['ip']
PROJECT_NAME = File.basename(File.dirname(__FILE__))

Vagrant.configure("2") do |config|
    config.vm.box = "centos65.v11"
    config.vm.box_url = "http://tools.production.adwatch.ru/vagrant-boxes/centos65.v11.box"
    config.vbguest.auto_update = false

    #Текущая машина будет привязана к указанному ip адресу в параметрах
    config.vm.network "private_network", ip: IP_ADDRESS

    config.vm.provider "virtualbox" do |v|
      v.name = "vagrant-" + PROJECT_NAME

      v.customize ["modifyvm", :id, "--ioapic", "on"]

      v.memory = parameters['machine']['memory']
      v.cpus = parameters['machine']['cpus']

      v.customize ["storagectl", :id, "--name", "SATA", "--hostiocache","on"]

      v.customize ["modifyvm", :id, "--nictype1", "virtio" ]
      v.customize ["modifyvm", :id, "--nictype2", "virtio" ]
      v.customize ["modifyvm", :id, "--nictype3", "virtio"]

      v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
      v.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
    end

    if OS.windows?
      config.vm.synced_folder "./", "/data/sites/" + PROJECT_NAME + "/public"
    else
      config.vm.synced_folder "./", "/data/sites/" + PROJECT_NAME + "/public", type: "nfs"
    end

    config.ssh.username = "vagrant"
    config.ssh.password = "vagrant"

    # -- Подготовим ОСЬ для удобной работы в вагранте
    config.vm.provision :shell, :args => IP_ADDRESS+' '+PROJECT_NAME, :path => ".vagrant/scripts/setup_env.sh"

    # -- Настроим нужную версию php
    config.vm.provision :shell, :args => parameters['php'], :path => ".vagrant/scripts/setup_php.sh"

    # -- Создадим директории для проекта и проставим права
    config.vm.provision :shell, :path => ".vagrant/scripts/setup_dirs.sh"

    # -- Настроим переменные окружения
    config.vm.provision :shell, :path => ".vagrant/scripts/setup_bashrc.sh", :privileged => false

    # -- Создадим виртуальный хост http
    config.vm.provision :shell, :path => ".vagrant/scripts/setup_http.sh"

    # -- Создадим виртуальный хост ssl (https)
    config.vm.provision :shell, :path => ".vagrant/scripts/setup_ssl.sh"

    # -- deploy проекта
    config.vm.provision :shell, :path => ".vagrant/scripts/setup_app.sh", :privileged => false
end