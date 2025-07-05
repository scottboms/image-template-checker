<?php

require_once __DIR__ . '/classes/ImageTemplateChecker.php';

use Scottboms\Check\ImageTemplateChecker;
use Composer\Semver\Semver;
use Kirby\Cms\App as Kirby;

  // validate Kirby version
if (Semver::satisfies(Kirby::version() ?? '0.0.0', '~4.0 || ~5.0') === false) {
	throw new Exception('Image Template Checker requires Kirby 4 or 5');
}

Kirby::plugin(
  name: 'scottboms/image-template-checker',
  info: [
    'homepage' => 'https://github.com/scottboms/image-template-checker'
  ],
  version: '1.0.0',
  license: 'MIT',
  extends: [
  'options' => [],
  'areas' => [
    'image-template-checker' => [
      'label' => 'Image Template Checker',
      'icon'  => 'image',
      'breadcrumbLabel' => function() {
          return 'Image Template Checker';
      },
      'menu'  => true,
      'link' => 'image-template-checker',
      'views' => [
        [
          'pattern' => 'image-template-checker',
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
