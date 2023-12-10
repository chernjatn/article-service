<?php

namespace App\Console\Commands;

use App\Enums\Channel;
use App\Models\Article;
use App\Models\Author;
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
        $authorsSitemapUrl = $this->authorsSitemap();

        foreach (Channel::cases() as $channel) {
            $this->mainSitemap([
                $authorsSitemapUrl,
                $this->articlesSitemap($channel)
            ]);
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
            ->where('status', true));

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
            ->where('status', true));

        $fileName = "sitemap-authors.xml";

        $sitemap->writeToDisk('public', "sitemap-authors.xml");

        return $fileName;
    }

    private function mainSitemap(array $entitySitemapUrls): void
    {
        $sitemap = Sitemap::create();

        foreach ($entitySitemapUrls as $entitySitemapUrl) {
            $publicFilePath = public_path($entitySitemapUrl);

            if (!File::exists($publicFilePath)) {
                continue;
            }

            $sitemap->add(Url::create($entitySitemapUrl)
                ->setLastModificationDate(Carbon::now()));
        }

        $sitemap->writeToDisk('public', "sitemap.xml");
    }
}
