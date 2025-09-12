<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): \App\Models\User
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum'); // авторизация через Sanctum
        return $user;
    }


    #[Test]
    public function can_list_articles()
    {
        $this->actingUser();

        Article::factory()->count(3)->create();

        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function can_create_article_via_api()
    {
        $this->actingUser();

        $response = $this->postJson('/api/articles', ['name' => 'API Article']);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'API Article']);

        $this->assertDatabaseHas('articles', ['name' => 'API Article']);
    }

    #[Test]
    public function can_show_article()
    {
        $this->actingUser();

        $article = Article::factory()->create();

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $article->id]);
    }

    #[Test]
    public function can_update_article()
    {
        $this->actingUser();

        $article = Article::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson("/api/articles/{$article->id}", ['name' => 'Updated Name']);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('articles', ['name' => 'Updated Name']);
    }

    #[Test]
    public function can_delete_article()
    {
        $this->actingUser();

        $article = Article::factory()->create();

        $response = $this->deleteJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Article deleted']);

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    #[Test]
    public function returns_404_for_missing_article()
    {
        $this->actingUser();

        $response = $this->getJson('/api/articles/999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Article not found']);
    }
}
