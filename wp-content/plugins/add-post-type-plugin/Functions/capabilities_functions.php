<?php
function modify_capabilities()
{
    $user = wp_get_current_user();
    $roles = $user->roles;
    switch ($roles) {
        case in_array("contributor", $roles):
            $user->add_cap('publish_posts', false);
            $user->add_cap('edit_posts', true);
            $user->add_cap('edit_published_posts', false);//read_private_posts
            $user->add_cap('read_private_posts', true);
            break;
        case in_array("administrator", $roles):
            break;
        case in_array("editor", $roles):
            break;
        case in_array("subscriber", $roles):
            break;
        default:
    }

}

add_action('init', 'modify_capabilities');

function modify_users_visibility($query)
{
    $user = wp_get_current_user();
    $roles = $user->roles;
    if (array_key_exists('post_type', $query->query_vars) && $query->query_vars['post_type'] == 'articoli-custom')
        switch ($roles) {
            case in_array("contributor", $roles):

                $query->set('author', $user->ID);
                break;
            case in_array("administrator", $roles):
                break;
            case in_array("editor", $roles):
                break;
            case in_array("subscriber", $roles):
                break;
            default:
        }

}

add_action('pre_get_posts', 'modify_users_visibility');

function modify_users_edit_capability($mange)
{
    $user = wp_get_current_user();
    $roles = $user->roles;
    global $wp_query;
    if (array_key_exists('post_type', $wp_query->query_vars) && $wp_query->query_vars['post_type'] == 'articoli-custom')
        switch ($roles) {
            case in_array("contributor", $roles):

// $wp_query->set('author', $user->ID );
                break;
            case in_array("administrator", $roles):
                break;
            case in_array("editor", $roles):
                break;
            case in_array("subscriber", $roles):
                break;
            default:
        }

}

add_action('restrict_manage_posts', 'modify_users_edit_capability');

add_filter('post_row_actions', 'remove_row_actions');
function remove_row_actions($actions)
{
    global $current_screen;
    if ($current_screen->post_type != 'articoli-custom') return $actions;
    $user = wp_get_current_user();
    $roles = $user->roles;
    switch ($roles) {
        case in_array("contributor", $roles):
            unset($actions['edit']);
            unset($actions['inline hide-if-no-js']);
            unset($actions['trash']);
            break;
        case in_array("administrator", $roles):
            break;
        case in_array("editor", $roles):
            break;
        case in_array("subscriber", $roles):
            break;
        default:
    }


    return $actions;
}