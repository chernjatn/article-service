<?php

namespace App\Console\Commands;

use App\Enums\Channel;
use App\Models\Article;
use App\Models\Author;
use Illuminate\Console\Command;
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
        foreach (Channel::cases() as $channel) {
            $this->authorSitemap($channel);
            $this->articleSitemap($channel);
        }
    }

    private function articleSitemap(Channel $channel): void
    {
        $sitemap = Sitemap::create();

        $baseUrl = $channel->host() . self::ARTICLE_URL;

        $sitemap->add(
            Url::create($baseUrl)
        );

        Article::query()
            ->select('id', 'slug', 'updated_at')
            ->ofChannel($channel)
            ->where('status', true)
            ->cursor()
            ->each(static function (Article $article) use ($sitemap, $baseUrl) {
                $tag = Url::create($baseUrl . $article->slug);
                if ($article->updated_at) {
                    $tag->setLastModificationDate($article->updated_at);
                }

                $sitemap->add($tag);
            });

        $fileName = "sitemap-articles.xml";

        $sitemap->writeToDisk('public', "/channels/{$channel->value}/$fileName");
    }

    private function authorSitemap(Channel $channel): void
    {
        $sitemap = Sitemap::create();

        $baseUrl = $channel->host() . self::AUTHOR_URL;

        $sitemap->add(
            Url::create($baseUrl)
        );

        Author::query()
            ->select('id', 'slug', 'updated_at')
            ->where('status', true)
            ->cursor()
            ->each(static function (Author $author) use ($sitemap, $baseUrl) {
                $tag = Url::create($baseUrl . $author->slug);
                if ($author->updated_at) {
                    $tag->setLastModificationDate($author->updated_at);
                }

                $sitemap->add($tag);
            });

        $fileName = "sitemap-authors.xml";

        $sitemap->writeToDisk('public', "/channels/{$channel->value}/$fileName");
    }
}
