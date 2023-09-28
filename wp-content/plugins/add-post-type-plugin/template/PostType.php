<?php

class PostType
{
    public $post_name;
    public $post_slug;

    public $post_singular_name;
    public $supports;
    public $post_taxonomies;

    public function __construct($post_name, $post_slug, $post_singular_name, $supports, $post_taxonomies)
    {
        $this->post_name = $post_name;
        $this->post_slug = $post_slug;

        $this->post_singular_name = $post_singular_name;
        $this->supports = $supports;
        $this->post_taxonomies = $post_taxonomies;
    }

}
