<?php

namespace Scottboms\Check;

use Kirby\Toolkit\Dir;
use Kirby\Toolkit\F;
use Kirby\Toolkit\Str;

class CheckFiles
{
	public function findImagesWithoutContentOrTemplate(): array
	{
		$images = [];
		$contentExtension = kirby()->contentExtension(); // ← gets 'txt', 'json', etc.

		foreach (site()->index()->files()->filterBy('type', 'image') as $file) {
			$hasContent = $file->exists(); // kirby auto-checks if .txt exists
			$templateFromContent = $file->content()->get('template')->value();
			$altText = $file->content()->get('alt')->value();

			// flag if the .txt file does not exist or if it exists but has no template
			if (!$hasContent || empty($templateFromContent)) {
				$parent = $file->parent();
				$parentPanelUrl = null;

				if ($parent instanceof \Kirby\Cms\Page) {
					$parentPanelUrl = str_replace('/', '+', $parent->id());
					$parentFilesUrl = '';
				}

				$images[] = [
					'id' => $file->id(),
					'filename' => $file->filename(),
					'name' => $file->name(),
					'url' => $file->url(),
					'panelUrl' => $file->panelUrl(),
					'parentPanelUrl' => $file->parent()?->panelUrl(),
					'blueprint' => $file->blueprint(),
					'mime' => $file->mime(),
					'thumbUrl' => $file->resize(300)->url(),
					'parent' => $parent?->title()->value() ?? null,
					'parentId' => $file->parent()->id(),
					'fileUrl' => '/pages/' . $parentPanelUrl . '/files/' . $file->filename(),
					'hasContentFile' => $hasContent,
					'contentFilename' => $file->filename() . '.' . $contentExtension,
					'templateInContent' => $templateFromContent ?: null,
					'alt' => $altText ?? null
				];
			}
		}

		return $images;
	}

	public function availableFileTemplates(): array
	{
		$templates = [];
		$filesPath = kirby()->root('blueprints') . '/files';

		foreach (glob($filesPath . '/*.yml') as $file) {
				$name = pathinfo($file, PATHINFO_FILENAME);
				$label = ucfirst(str_replace(['-', '_'], ' ', $name));
				$templates[] = [
					'value' => $name,
					'text'  => $label
				];
			}

		usort($templates, fn($a, $b) => strcmp($a['text'], $b['text'])); // alphabetize the list
		return $templates;
	}

  // --------------------------------------------------------------------------
  // logging
  // implements custom logging to /site/logs/debug.log
  public function log(string $level, string $message): void
  {
    $timestamp = date('Y-m-d H:i:s');
    $logDir = kirby()->root('logs');
    $logFile = $logDir . '/debug.log';

    // ensure log directory exists
    Dir::make($logDir);
    $entry = Str::unhtml("[$timestamp][$level] $message") . PHP_EOL;
    F::append($logFile, $entry);
  }


}
