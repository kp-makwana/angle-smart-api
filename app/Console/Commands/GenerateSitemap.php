<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
  protected $signature = 'sitemap:generate';
  protected $description = 'Generate sitemap.xml';

  public function handle()
  {
    $sitemap = Sitemap::create(config('app.url'));

    // Home page
    $sitemap->add(
      Url::create('/')
        ->setPriority(1.0)
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
    );

    $sitemap->writeToFile(public_path('sitemap.xml'));

    $this->info('✅ Sitemap generated successfully!');

  }
}
