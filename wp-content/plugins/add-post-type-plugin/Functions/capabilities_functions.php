<?php
add_action('init', 'modify_capabilities');
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
            $user->add_cap('publish_posts', true);
            break;
        case in_array("subscriber", $roles):
            break;
        default:
    }

}


add_action('pre_get_posts', 'modify_users_visibility');
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

add_filter('post_row_actions', 'remove_row_actions',10,2);
function remove_row_actions($actions, $post)
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
            if(user_can($post->post_author,'administrator') || user_can($post->post_author,'editor')){
                unset($actions['edit']);
                unset($actions['inline hide-if-no-js']);
                unset($actions['trash']);
            }
            break;
        case in_array("subscriber", $roles):
            break;
        default:
    }


    return $actions;
}

add_action('save_post', 'approve_post');
function approve_post($postID)
{
    $post = get_post($postID);
    $post_type = $post->post_type;
    if($post_type != 'articoli-custom') return;

    $user = wp_get_current_user();
    $roles = $user->roles;
    switch ($roles) {
        case in_array("contributor", $roles):
            break;
        case in_array("administrator", $roles):
            break;
        case in_array("editor", $roles):
            if($post->post_status == 'append'){
                global $wpdb;
                $res = $wpdb->update('wp_posts',array('post_status' => 'approved'),array('ID' => $postID));
            }
            break;
        case in_array("subscriber", $roles):
            break;
        default:
    }


}