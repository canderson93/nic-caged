<?php
/*
 * Plugin Name: Nicholas Caged
 * Description: Nicholas Cage is escaping - It's the plugin no one wanted, but everyone needed.
 */

class Nic_Caged {

    public function filter_images($content) {
        if (!is_main_query()) { return $content; }

        $target_url = plugin_dir_url(__FILE__) . 'public/nic-cage.png';

        /* Replace every occurence of src="*" and srcset="*" with our url -- since this is on the content hook, it shouldn't hit
         * any script tags, but we can't guarantee what other plugins are doing. Parsing the HTML would do a better job
         * than RegEx, but this is meant to be quick and hacky, so quick and hacky it shall stay.
         */
        $content = preg_replace(
            array(
                '/src\\=(?:\\"|\\\')(.+?)(?:\\"|\\\')/',
                '/srcset\\=(?:\\"|\\\')(.+?)(?:\\"|\\\')/',
            ),
            array(
                'src="'.$target_url.'"',
                'srcset="'.$target_url.'"'
            ),
            $content);


        return preg_replace('/src\\=(?:\\"|\\\')(.+?)(?:\\"|\\\')/', 'src="'.$target_url.'"', $content);
    }


    public function register_hooks(): void {
        add_filter('the_content', array(&$this, 'filter_images'), 10, 1);

        //Add styles
        add_action('wp_enqueue_scripts', function() {
            wp_enqueue_style('nic_caged_styles', plugin_dir_url(__FILE__) . 'public/styles.css');
        });
    }
}

$nic_caged = new Nic_Caged();
$nic_caged->register_hooks();