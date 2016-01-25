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


# --
# "Повесим" эту тачку на указанный IP
# НЕЖЕЛАТЕЛЬНО менять ip, так как можно вызвать путанницу когда ссылками будем делиться
# Именно по этому адресу будет доступна твоя ПРИВАТНАЯ виртуальная машина
# --
IP_ADDRESS = parameters['ip']

SITE_PATH = File.basename(File.dirname(__FILE__))

Vagrant.configure("2") do |config|
    config.vm.box = "centos65.v10"
    config.vm.box_url = "http://tools.production.adwatch.ru/vagrant-boxes/centos65.v10.box"

    config.vm.provider "virtualbox" do |v|
      v.name = "vagrant-" + SITE_PATH

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

    config.vm.network "private_network", ip: IP_ADDRESS

    if OS.windows?
      #config.vm.synced_folder "./", "/data/sites/" + SITE_PATH + "/public"
    else
      #config.vm.synced_folder "./", "/data/sites/" + SITE_PATH + "/public", type: "nfs"
    end

    config.ssh.username = "vagrant"
    config.ssh.password = "vagrant"

    # -- Подготовим ОСЬ для удобной работы в вагранте
    config.vm.provision :shell, :args => IP_ADDRESS+' '+SITE_PATH, :path => ".vagrant/scripts/setup_env.sh"

    # -- Настроим нужную версию php
    #config.vm.provision :shell, :args => parameters['php'], :path => ".vagrant/scripts/setup_php.sh"

    # -- Создадим директории для проекта и проставим права
    #config.vm.provision :shell, :path => ".vagrant/scripts/setup_dirs.sh"

    # -- Создадим виртуальный хост http
    #config.vm.provision :shell, :path => ".vagrant/scripts/setup_http.sh"

    # -- Создадим виртуальный хост ssl (https)
    #config.vm.provision :shell, :path => ".vagrant/scripts/setup_ssl.sh"

    # -- deploy проекта
    #config.vm.provision :shell, :path => ".vagrant/scripts/setup_app.sh", :privileged => false
end