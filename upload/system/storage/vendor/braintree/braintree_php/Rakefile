task :default => :test
task :test => %w[test:unit test:integration]

namespace :test do
  task :unit => %w[test:php:unit test:hhvm:unit]
  task :integration => %w[test:php:integration test:hhvm:integration]

  namespace :php do
    desc "print PHP version"
    task :version do
      print_php_version("php")
    end

    desc "run unit tests under PHP"
    task :unit => :version do
      run_php_test_suite("php", "unit")
    end

    desc "run integration tests under PHP"
    task :integration do
      run_php_test_suite("php", "integration")
    end
  end

  namespace :hhvm do
    desc "print HHVM version"
    task :version do
      print_php_version("hhvm")
    end

    desc "run tests under HHVM"
    task :test => [:unit, :integration]

    desc "run unit tests under HHVM"
    task :unit => :version do
      run_php_test_suite("hhvm", "unit")
    end

    desc "run integration tests under HHVM"
    task :integration do
      run_php_test_suite("hhvm", "integration")
    end
  end

  desc "run tests under PHP"
  task :php => %w[php:unit php:integration]

  desc "run tests under HHVM"
  task :hhvm => %w[hhvm:unit hhvm:integration]

  desc "run a single test file"
  task :file, :file_path do |t, args|
    run_php_test_file(args[:file_path])
  end

  desc "run single test (e.g. rake test:single[GatewayTest::testConfigGetsAssertedValid])"
  task :single, :test_name do |t, args|
    run_php_test(args[:test_name])
  end
end

desc "update the copyright year"
task :copyright, :from_year, :to_year do |t, args|
  sh "find tests lib -type f -name '*.php' -exec sed -i 's/#{args[:from_year]} Braintree/#{args[:to_year]} Braintree/g' {} +"
end

def print_php_version(interpreter)
  sh "#{interpreter} --version"
end

def run_php_test_suite(interpreter, test_suite)
  sh "#{interpreter} ./vendor/bin/phpunit --testsuite #{test_suite}"
end

def run_php_test_file(test_file)
  sh "./vendor/bin/phpunit #{test_file}"
end

def run_php_test(test_name)
  sh "./vendor/bin/phpunit --filter #{test_name}"
end
