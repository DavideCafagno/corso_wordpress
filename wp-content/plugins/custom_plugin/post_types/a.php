<?php 
                add_action("init", "register_custom_a");
                    function register_custom_a(){
                        register_post_type("a",
                            array(
                                "labels" => array(
                                    "name" => __("a", "textdomain"),
                                    "singular_name" => __("a", "textdomain"),
                                ),
                                "public" => true,
                                "has_archive" => true,
                                "hierarchical" => false,
                                "supports" => array("title","author","editor","excerpt","thumbnail","comments","custom-fields",),
                                "taxonomies" => array("post_tag", "category", "categoria_custom"),
                                "show_ui" => true,
                            )
                    
                        );
                    }
                ?>