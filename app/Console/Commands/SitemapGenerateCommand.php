<?php

namespace App\Console\Commands;

use App\Enums\Channel;
use App\Models\Article;
use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapGenerateCommand extends Command
{
    private const ARTICLE_URL = '/articles/';
    private const AUTHOR_URL = '/authors/';

    protected $signature = 'sitemap:generate';

    protected $description = 'Generate sitemap';

    public function handle(): void
    {
        $authorSitemap = $this->authorSitemap();

        foreach (Channel::cases() as $channel) {
            $this->mainSitemap([
                $authorSitemap,
                $this->articleSitemap($channel)
            ], $channel);
        }
    }

    private function articleSitemap(Channel $channel): string
    {
        $sitemap = Sitemap::create();

        $sitemap->add(
            Url::create(self::ARTICLE_URL)
        );

        $sitemap->add(Article::query()
            ->select('id', 'slug', 'updated_at')
            ->ofChannel($channel)
            ->where('status', true)
            ->get());

        $fileName = "channels/{$channel->value}/sitemap-articles.xml";

        $sitemap->writeToDisk('public', $fileName);

        return $fileName;
    }

    private function authorSitemap(): string
    {
        $sitemap = Sitemap::create();

        $sitemap->add(
            Url::create(self::AUTHOR_URL)
        );

        $sitemap->add(Author::query()
            ->select('id', 'slug', 'updated_at')
            ->where('status', true)
            ->get());

        $fileName = "sitemap-authors.xml";

        $sitemap->writeToDisk('public', "sitemap-authors.xml");

        return $fileName;
    }

    private function mainSitemap(array $sitemaps, Channel $channel): void
    {
        $mainSitemap = Sitemap::create();
        $basePath = storage_path('app/public/');

        foreach ($sitemaps as $sitemap) {
            if (!File::exists($basePath . $sitemap)) {
                continue;
            }

            $mainSitemap->add(Url::create($sitemap)
                ->setLastModificationDate(Carbon::now()));
        }

        $mainSitemap->writeToDisk('public', "channels/{$channel->value}/sitemap.xml");
    }
}
