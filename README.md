# CodeIgniter3 Filename Checker

This controller checks CodeIgniter 3.0 class filename.

> Starting with CodeIgniter 3.0, all class filenames (libraries, drivers, controllers and models) must be named in a Ucfirst-like manner or in other words - they must start with a capital letter.
[CodeIgniter User Guide](http://www.codeigniter.com/user_guide/installation/upgrade_300.html#step-2-update-your-classes-file-names)

## Installation

Install `controllers/Check.php` into your CodeIgniter 3.0 `application/controllers` folder.

## Usage

Access to `http://your-server/check/filename`.

Or you can run via CLI:
	
~~~
$ php index.php check filename
~~~

If you want to fix filename:

~~~
$ php index.php check filename fix
~~~

## Related Projects for CodeIgniter 3.0

* [CodeIgniter Composer Installer](https://github.com/kenjis/codeigniter-composer-installer)
* [Cli for CodeIgniter 3.0](https://github.com/kenjis/codeigniter-cli)
* [CI PHPUnit Test](https://github.com/kenjis/ci-phpunit-test)
* [CodeIgniter Simple and Secure Twig](https://github.com/kenjis/codeigniter-ss-twig)
* [CodeIgniter Doctrine](https://github.com/kenjis/codeigniter-doctrine)
* [CodeIgniter Deployer](https://github.com/kenjis/codeigniter-deployer)
