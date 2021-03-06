<?php

use \WPControllers\Post;
use \WPControllers\Page;
use \WPControllers\Term;
use \WPControllers\User;

if ( !function_exists('get_post_controller') ) {
  /**
   * Global function to call PostController::get_controller
   * @see PostController::get_controller
   *
   * @param string|null $key
   * @param array $options
   *
   * @return Post
   */
  function get_post_controller($key = null, $options = array()) { return Post::get_controller($key, $options); }
}

if ( !function_exists('get_post_controllers') ) {
  /**
   * Global function to call PostController::get_controllers
   * @see PostController::get_controllers
   *
   * @param array $args
   *
   * @return Post[]
   */
  function get_post_controllers($args = null) { return Post::get_controllers($args); }
}

if ( !function_exists('get_page_controllers') ) {
  /**
   * Global function to call Page::get_controllers
   * @see PostController::get_controllers
   *
   * @param array $args
   *
   * @return array
   */
  function get_page_controllers($args = null) { return Page::get_controllers($args); }
}

if ( !function_exists('get_term_controller') ) {
  /**
   * Global function to call Term::get_controller
   * @see Term::get_controller
   *
   * @param string $key
   * @param string $taxonomy
   * @param string $field
   * @param array $options
   *
   * @return Term
   */
  function get_term_controller($key = null, $taxonomy = null, $field = 'id', $options = array()) {
    return Term::get_controller($key, $taxonomy, $field, $options);
  }
}

if ( !function_exists('get_term_controllers') ) {
  /**
   * Global function to call Term::get_controllers
   * @see Term::get_controller
   *
   * @param $args array
   *
   * @return array
   */
  function get_term_controllers($args) {
    return Term::get_controllers($args);
  }
}

if ( !function_exists('get_user_controller') ) {
  /**
   * Global function to call User::get_controller
   * @see User::get_controller
   *
   * @param string|null $key
   * @param string $field
   * @param array $options
   *
   * @return User
   */
  function get_user_controller($key = null, $field = 'id', $options = array()) { return User::get_controller($key, $field, $options); }
}

if ( !function_exists('get_user_controllers') ) {
  /**
   * Global function to calll User::get_controllers
   * @see User::get_controllers
   *
   * @param array $args
   *
   * @return array
   */
  function get_user_controllers($args) { return User::get_controllers($args); }
}
