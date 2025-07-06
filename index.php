<?php

require_once __DIR__ . '/classes/ImageTemplateChecker.php';

use Scottboms\Check\ImageTemplateChecker;
use Composer\Semver\Semver;
use Kirby\Cms\App as Kirby;

  // validate Kirby version
if (Semver::satisfies(Kirby::version() ?? '0.0.0', '~4.0 || ~5.0') === false) {
	throw new Exception('Template Checker requires Kirby 4 or 5');
}

Kirby::plugin(
	name: 'scottboms/template-checker',
	info: [
		'homepage' => 'https://github.com/scottboms/template-checker'
	],
	version: '1.0.1',
	license: 'MIT',
	extends: [
	'options' => [],
	'areas' => [
		'image-template-checker' => [
			'label' => 'Check Images',
			'icon'  => 'images',
			'breadcrumbLabel' => function() {
				return 'Check Images';
			},
			'menu'  => true,
			'link' => 'template-checker',
			'views' => [
				[
					'pattern' => 'template-checker',
					'action'  => function () {
						$checker = new ImageTemplateChecker();
						$images = $checker->findImagesWithoutContentOrTemplate();
						return [
							'component' => 'k-image-template-checker',
							'title' => 'Images Without Template',
							'props' => [
								'images' => $images
							]
						];
					}
				]
			]
		]
	]
]);
