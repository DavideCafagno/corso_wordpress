<?php 
                add_action("init", "register_custom_asd_asd_asd");
                    function register_custom_asd_asd_asd(){
                        register_post_type("asd-asd-asd",
                            array(
                                "labels" => array(
                                    "name" => __("asd asd asd ", "textdomain"),
                                    "singular_name" => __("asd", "textdomain"),
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