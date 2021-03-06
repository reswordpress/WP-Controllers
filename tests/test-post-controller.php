<?php

namespace WPControllers;

class PostTest extends \WP_UnitTestCase {
  public function test_get_controller() {
    $post = $this->factory()->post->create_and_get();

    // Get controller by WP_Post
    $controller = Post::get_controller($post);
    $this->assertInstanceOf(Post::class, $controller);

    // Get controller by id
    $controller = Post::get_controller($post->ID);
    $this->assertInstanceOf(Post::class, $controller);

    // Get controller by slug
    $controller = Post::get_controller($post->post_name);
    $this->assertInstanceOf(Post::class, $controller);

    // Get controller by single template
    $this->go_to("/p={$post->ID}");
    $controller = Post::get_controller();
    $this->assertInstanceOf(Post::class, $controller);
    $this->assertEquals($post->ID, $controller->id);
  }

  public function test_get_controllers() {
    $posts = $this->factory()->post->create_many(2);

    // Array of posts
    $controllers = Post::get_controllers($posts);
    $this->assertEquals($posts, wp_list_pluck($controllers, 'id'));

    // Query
    $controllers = Post::get_controllers([
      'post_type'   => 'post',
      'order'       => 'ASC'
    ]);
    $this->assertEquals($posts, wp_list_pluck($controllers, 'id'));

    // Something unexpected
    $controllers = Post::get_controllers(false);
    $this->assertEmpty($controllers);

    // Get controllers by posts archive
    $this->go_to(get_post_type_archive_link('post'));
    $controllers = Post::get_controllers();
    $controller_ids = wp_list_pluck($controllers, 'id');
    sort($posts);
    sort($controller_ids);
    $this->assertEquals($posts, $controller_ids);
  }

  public function test_get_controller_post_type() {
    // Root level post
    $this->assertSame('post', Post::get_controller_post_type(Post::class));

    // Child post
    $this->assertSame('page', Post::get_controller_post_type(Page::class));

    // TODO: Test classes with no explicit post type
    // TODO: Test template classes
  }

  public function test_wp_post_properties() {
    $post = $this->factory()->post->create_and_get();
    $controller = Post::get_controller($post);

    $properties = get_object_vars($post);
    foreach($properties as $key => $value) {
      $this->assertSame($value, $controller->$key, "Post controller should support the WP_Post->$key property");
    }
  }

  public function test_url() {
    $post = $this->factory()->post->create_and_get();
    $controller = Post::get_controller($post);

    $this->assertSame(get_permalink($post), $controller->url());
  }

  public function test_archive_url() {
    $this->assertEquals(get_post_type_archive_link('post'), Post::archive_url());
  }

  public function test_author() {
    $user = $this->factory()->user->create_and_get();
    $post = $this->factory()->post->create_and_get([
      'post_author'   => $user->ID
    ]);

    $post_controller = Post::get_controller($post);
    $user_controller = $post_controller->author();

    $this->assertEquals($user->ID, $user_controller->id);
  }

  public function test_date() {
    $post = $this->factory()->post->create_and_get();
    $controller = Post::get_controller($post);

    // Local timezone
    $timestamp = strtotime($post->post_date);
    $this->assertSame($timestamp, $controller->date('timestamp'));
    $this->assertSame(date('d:m:Y', $timestamp), $controller->date('d:m:Y'));

    // GMT
    $timestamp = strtotime($post->post_date_gmt);
    $this->assertSame($timestamp, $controller->date('timestamp', true));
    $this->assertSame(date('d:m:Y', $timestamp), $controller->date('d:m:Y', true));
  }

  public function test_modified() {
    $post = $this->factory()->post->create_and_get();
    $controller = Post::get_controller($post);

    // Local timezone
    $timestamp = strtotime($post->post_modified);
    $this->assertSame($timestamp, $controller->modified('timestamp'));
    $this->assertSame(date('d:m:Y', $timestamp), $controller->modified('d:m:Y'));

    // GMT
    $timestamp = strtotime($post->post_modified_gmt);
    $this->assertSame($timestamp, $controller->modified('timestamp', true));
    $this->assertSame(date('d:m:Y', $timestamp), $controller->modified('d:m:Y', true));
  }

  public function test_terms() {
    $term1 = $this->factory()->term->create_and_get();
    $term2 = $this->factory()->term->create_and_get();
    $term3 = $this->factory()->term->create_and_get();

    // Post with multiple terms
    $post = $this->factory()->post->create_and_get();
    wp_set_post_terms($post->ID, [ $term1->term_id, $term2->term_id ], 'post_tag');

    $controller = Post::get_controller($post);
    $terms = $controller->terms('post_tag');

    $this->assertSame(2, count($terms));
    $this->assertSame([$term1->term_id, $term2->term_id], wp_list_pluck($terms, 'id'));

    // Post wih no terms
    $post = $this->factory()->post->create_and_get();
    $controller = Post::get_controller($post);

    $this->assertEmpty($controller->terms('post_tag'));
  }
}
