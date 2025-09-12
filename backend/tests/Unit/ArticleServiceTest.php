<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ArticleService;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ArticleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ArticleService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ArticleService::class);
    }

    #[Test]
    public function it_can_create_article()
    {
        $article = $this->service->create(['name' => 'Test Article']);

        $this->assertInstanceOf(Article::class, $article);
        $this->assertDatabaseHas('articles', ['name' => 'Test Article']);
    }

    #[Test]
    public function it_can_find_article()
    {
        $article = Article::factory()->create();
        $found = $this->service->find($article->id);

        $this->assertNotNull($found);
        $this->assertEquals($article->id, $found->id);
    }

    #[Test]
    public function it_can_update_article()
    {
        $article = Article::factory()->create(['name' => 'Old Name']);
        $updated = $this->service->update($article->id, ['name' => 'New Name']);

        $this->assertEquals('New Name', $updated->name);
        $this->assertDatabaseHas('articles', ['name' => 'New Name']);
    }

    #[Test]
    public function it_can_delete_article()
    {
        $article = Article::factory()->create();
        $result = $this->service->delete($article->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
