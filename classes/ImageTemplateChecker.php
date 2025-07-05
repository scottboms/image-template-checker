<?php

namespace Scottboms\Check;

class ImageTemplateChecker
{
  public function findImagesWithoutContentOrTemplate(): array
  {
    $images = [];

    foreach (site()->index()->files()->filterBy('type', 'image') as $file) {
      $hasContent = $file->exists(); // Kirby auto-checks if .txt exists
      $templateFromContent = $file->content()->get('template')->value();

      // Flag if the .txt file does not exist or if it exists but has no template
      if (!$hasContent || empty($templateFromContent)) {
        $parent = $file->parent();
        $parentPanelUrl = null;

        if ($parent instanceof \Kirby\Cms\Page) {
          $parentPanelUrl = '/pages/' . str_replace('/', '+', $parent->id());
        }

        $images[] = [
          'id' => $file->id(),
          'filename' => $file->filename(),
          'url' => $file->url(),
          'parent' => $parent?->title()->value() ?? null,
          'parentPanelUrl' => $parentPanelUrl,
          'hasContentFile' => $hasContent,
          'templateInContent' => $templateFromContent ?: null
        ];
      }
    }

    return $images;
  }
} 