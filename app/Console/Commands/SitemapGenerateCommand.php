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

    protected $signature = 'sitemap:generate';

    protected $description = 'Generate sitemap';

    public function handle(): void
    {
        foreach (Channel::cases() as $channel) {
            $this->articlesSitemap($channel);
        }
    }

    private function articlesSitemap(Channel $channel): void
    {
        $sitemap = Sitemap::create();

        $sitemap->add(
            Url::create(self::ARTICLE_URL)
        );

        Article::query()
            ->select('id', 'slug', 'updated_at')
            ->ofChannel($channel)
            ->where('status', true)
            ->whereNotNull('slug')
            ->cursor()
            ->each(static function (Article $article) use ($sitemap) {
                $tag = Url::create(self::ARTICLE_URL . $article->slug);
                if ($article->updated_at) {
                    $tag->setLastModificationDate($article->updated_at);
                }

                $sitemap->add($tag);
            });

        $sitemap->writeToDisk('public', "{$channel->value}/sitemap-articles.xml");
    }
}
