<?php

function WUPEnqueueStyles()
{
    wp_enqueue_style('mef',WUP_PLUGIN_URL.'assets/css/mef.css');
 //   wp_enqueue_style('bootstrap5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
}

function WUPEnqueueScripts()
{   
    wp_enqueue_script('WUP-scripts', WUP_PLUGIN_URL . 'assets/js/scripts.js', ['jquery']);
    wp_enqueue_script( 'boot1','https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array( 'jquery' ),'',true );
    wp_enqueue_script( 'boot2','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', array( 'jquery' ),'',true );
    wp_enqueue_script( 'boot3','https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js', array( 'jquery' ),'',true );
}

add_action('admin_enqueue_scripts', 'WUPEnqueueStyles');
add_action('admin_enqueue_scripts', 'WUPEnqueueScripts');
