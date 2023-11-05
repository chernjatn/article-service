<?php

namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportArticle implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Article $article)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (is_null($this->article->wp_article_id)) {
            $wpArticle = article()->createArticle($this->article);

            $this->article->wp_article_id = $wpArticle['id'];

            $this->article->save();
        }
    }

    public function uniqueId(): string
    {
        return $this->article->id;
    }
}
