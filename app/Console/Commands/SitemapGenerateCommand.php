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
        $authorsSitemapPath = $this->authorsSitemap();

        foreach (Channel::cases() as $channel) {
            $this->mainSitemap([
                $authorsSitemapPath,
                $this->articlesSitemap($channel)
            ], $channel);
        }
    }

    private function articlesSitemap(Channel $channel): string
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

        $fileName = "{$channel->value}/sitemap-articles.xml";

        $sitemap->writeToDisk('public', $fileName);

        return $fileName;
    }

    private function authorsSitemap(): string
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

    private function mainSitemap(array $entitySitemapPaths, Channel $channel): void
    {
        $sitemap = Sitemap::create();
        $basePath = storage_path('app/public/');

        foreach ($entitySitemapPaths as $entitySitemapPath) {
            if (!File::exists($basePath . $entitySitemapPath)) {
                continue;
            }

            $sitemap->add(Url::create($entitySitemapPath)
                ->setLastModificationDate(Carbon::now()));
        }

        $sitemap->writeToDisk('public', "{$channel->value}/sitemap.xml");
    }
}
