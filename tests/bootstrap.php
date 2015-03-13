<?php

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

define('TEMP_DIR', __DIR__ . '/tmp/' . getmypid());
@mkdir(dirname(TEMP_DIR)); // @ - directory may already exist
Tester\Helpers::purge(TEMP_DIR);

function createContainer(Nette\DI\Compiler $compiler, $config)
{
	$class = 'Container' . md5(lcg_value());
	$compiler->loadConfig(Tester\FileMock::create($config, 'neon'));
	$code = $compiler->compile(NULL, $class);
	file_put_contents(TEMP_DIR . '/code.php', "<?php\n\n$code");
	require TEMP_DIR . '/code.php';
	return new $class;
}
