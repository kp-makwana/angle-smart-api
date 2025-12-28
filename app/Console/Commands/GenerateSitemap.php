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

    /**
     * ----------------------------------
     * CORE PUBLIC PAGES (MUST HAVE)
     * ----------------------------------
     */
    $pages = [
      '/' => [1.0, Url::CHANGE_FREQUENCY_DAILY],
      '/about-us' => [0.8, Url::CHANGE_FREQUENCY_MONTHLY],
      '/contact-us' => [0.8, Url::CHANGE_FREQUENCY_MONTHLY],
      '/how-it-works' => [0.9, Url::CHANGE_FREQUENCY_MONTHLY],
      '/supported-brokers' => [0.7, Url::CHANGE_FREQUENCY_MONTHLY],
      '/security' => [0.6, Url::CHANGE_FREQUENCY_YEARLY],
    ];

    /**
     * ----------------------------------
     * LEGAL & COMPLIANCE PAGES (FINANCE)
     * ----------------------------------
     */
    $legalPages = [
      '/privacy-policy' => [0.5, Url::CHANGE_FREQUENCY_YEARLY],
      '/terms-and-conditions' => [0.5, Url::CHANGE_FREQUENCY_YEARLY],
      '/disclaimer' => [0.6, Url::CHANGE_FREQUENCY_YEARLY],
      '/risk-disclosure' => [0.6, Url::CHANGE_FREQUENCY_YEARLY],
      '/api-usage-policy' => [0.6, Url::CHANGE_FREQUENCY_YEARLY],
    ];

    /**
     * ----------------------------------
     * STATUS / TRANSPARENCY
     * ----------------------------------
     */
    $statusPages = [
      '/system-status' => [0.4, Url::CHANGE_FREQUENCY_WEEKLY],
    ];

    foreach (array_merge($pages, $legalPages, $statusPages) as $url => [$priority, $frequency]) {
      $sitemap->add(
        Url::create($url)
          ->setPriority($priority)
          ->setChangeFrequency($frequency)
      );
    }

    $sitemap->writeToFile(public_path('sitemap.xml'));

    $this->info('sitemap generated successfully!');
  }
}
