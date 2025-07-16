<?php

require_once __DIR__ . '/classes/CheckFiles.php';

use Scottboms\Check\CheckFiles;
use Composer\Semver\Semver;
use Kirby\Cms\App as Kirby;
use Kirby\Toolkit\F;

// validate Kirby version
if (Semver::satisfies(Kirby::version() ?? '0.0.0', '~4.0 || ~5.0') === false) {
	throw new Exception('Template Checker requires Kirby 4 or 5');
}

Kirby::plugin(
	name: 'scottboms/template-checker',
	info: [
		'homepage' => 'https://github.com/scottboms/template-checker'
	],
	version: '1.2.0',
	license: 'MIT',
	extends: [
	'options' => [],
	'areas' => [
		'checker' => function ($kirby) {
			return [
				'label' => 'Check Images',
				'icon'  => 'images',
				'breadcrumbLabel' => function() {
					return 'Check Images';
				},
				'menu'  => true,
				'link' => 'checker/images',
				'views' => [
					[
						'pattern' => 'checker/images',
						'action'  => function () {
							$user = kirby()->user();
							//$checker = new CheckFiles();
							//$images = $checker->findImagesWithoutContentOrTemplate();
							//$templates = $checker->availableFileTemplates();

							// dump($templates); // DEBUG

							return [
								'component' => 'k-image-template-checker',
								'title' => 'Images Without Template',
								'props' => [
									'images' => (new CheckFiles())->findImagesWithoutContentOrTemplate(),
									'availableTemplates' => (new CheckFiles())->availableFileTemplates()
								]
							];
						}
					]
				]
			];
		}
	],
	'api' => [
		'routes' => [
			[
				'pattern'   => 'checker/assign',
					'method'  => 'POST',
					'auth'    => true,
					'action'  => function () {

						//$logger = new CheckFiles();

						// get values submitted from the drawer component
						$key = get('key');
						$template = get('template');
						$alt = get('alt');

						//$logger->log('debug', 'values: Key ' . $key . ' Template: ' . $template . 'Alt: ' . $alt);

					  if (!$file = $key ? $file = site()->file($key) ?? page()->file($key) : null) {
							throw new Exception('File not found', 404);
						}
						$updates = []; // create array of values to update

						if ($template) {
							$updates['template'] = $template;
						}

						if ($alt !== null) {
							$updates['alt'] = $alt;
						}

						// try performing updates...
						try {
							$file->update($updates);
							return [
								'status' => 'ok',
								'message' => 'File updated',
							];
						} catch (Exception $e) {
							throw new Exception('Update failed: ' . $e->getMessage(), 500);
						}
					}
			]
		]
	]
]);
