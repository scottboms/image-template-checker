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
	version: '1.1.0',
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
				'pattern' => 'checker/assign',
					'method' => 'POST',
					'action'  => function () {
						$user = kirby()->user();
						$logger = new CheckFiles();

						$request = kirby()->request();
						$fileId = $request->body()->get('key');
						$template = $request->body()->get('template');
						$field = 'template'; // name of the field to add/modify

						$logger->log('debug', 'values: File ' . $fileId . ' Template: ' . $template);

						if (!$user) {
							$logger->log('error', 'Unauthorized access to checker/assign');
							throw new Exception('You must be logged in', 403);
							// otherwise continue with saving
						}

						try {
							// TODO: this all needs work to actually do anything
							$image = kirby()->site()->index(true)->findById($fileId);
							$logger->log('info', 'Image: ' . $image);

							if (!$image) {
								$logger->log('error', "File not found: $fileId");
								throw new Exception('File not found!', 404);
							}

							if($template === 'template') {
								//$image->update([$field => $template]);
								$logger->log('debug', 'Template is ' . $template);
								//$image = $image->changeTemplate($template);
								//$image = $image->update(['template' => $template]);
							}

							return [
							  'fieldId' => $fileId,
							  'template' => $template,
							  'success' => true,
							  'message' => 'Template assigned'
							];
						} catch (Exception $e) {
							$logger->log('error', 'Exception: ' . $e->getMessage());
							return ['error' => $e->getMessage()];
						}



					}
			]
		]
	]
]);
