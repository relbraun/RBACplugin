<?php

/*
Plugin Name: RBAC plugin
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Ariel Braun
Version: 1.0
Author URI: relbraun@gmail.com
 */
/**
 * Description of RBACplugin
 *
 * @author Kalman
 *
 * @property-read array $selectedPostTypes Description
 */

class RBACplugin
{
    const DOMAIN='RBACplugin';

    protected $post_types = array('page');

    protected $non_logged_options ;

    protected $logged_options;

    protected function init()
    {
        add_action('add_meta_boxes', array(&$this, 'add_meta_box'));
        add_action('save_post', array($this, 'save'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('the_post', array($this, 'the_post'));
        add_action( 'admin_enqueue_scripts', array($this, 'admin_scripts') );
    }

    public function __construct()
    {
        $this->non_logged_options = array(
            ''=>'Select What to do',
            'login' => __('Login page', self::DOMAIN),
            'redirect' => __('Redirect to page:', self::DOMAIN),
            'die' => 'wp_die',
        );
        $this->logged_options=array(
            ''=>'Select What to do',
            'die' => 'wp_die',
            'redirect' => __('Redirect to page:', self::DOMAIN),
        );
        $this->init();
    }

    public function __get($name)
    {
        $name='get'.$name;
        if(method_exists($this, $name)){
            return $this->$name();
        }
    }

    public function add_meta_box($post_type)
    {
        if(in_array($post_type, $this->selectedPostTypes)){
            add_meta_box('RBACmetabox',__('RBAC Plugin', self::DOMAIN), array($this, 'write_meta_box'), $post_type, 'advanced', 'core');
        }

    }
    /**
     *
     * @param WP_post $post
     */
    public function write_meta_box($post)
    {
        $roles =  get_option('wp_user_roles');
        $selected_posts = get_post_meta($post->ID, 'RBACroles', true);
        include 'metabox.php';
    }

    public function save($post_id)
    {
        if(isset($_POST['RBACroles'])){
            update_post_meta($post_id, 'RBACroles', $_POST['RBACroles']);
        }
    }

    public function admin_menu()
    {
        add_management_page(__('RBACplugin settings', self::DOMAIN), __('RBACplugin settings', self::DOMAIN),
                            'manage_options', 'RBACplugin_settings', array($this, 'admin_page'));
    }

    public function admin_page()
    {
        $this->save_settings();
        include 'admin_page.php';
    }

    public function the_post($post)
    {
        $avilable=  get_post_meta($post->ID, 'RBACroles', true);
        if(isset($avilable['role'])){
        switch ($avilable['role']) {
            case 'logged':
                if(is_user_logged_in()){
                if($avilable['todo']=='redirect'){
                    if($avilable['redirect']){
                        wp_redirect (get_permalink ($avilable['redirect']));
                    }
                    else{
                        wp_redirect (home_url());
                    }
                }
                elseif($avilable['todo']=='die'){
                    wp_die(__('You are legged-in user and you cannot come here.', self::DOMAIN));
                }
                }
                break;
            case 'not_logged':
                if(!is_user_logged_in()){
                if($avilable['todo']=='redirect'){
                    if($avilable['redirect']){
                        wp_redirect (get_permalink ($avilable['redirect']));
                    }
                    else{
                        wp_redirect (home_url());
                    }
                }
                elseif($avilable['todo']=='die'){
                    wp_die(__('You are legged-in user and you cannot come here.', self::DOMAIN));
                }
                elseif ($avilable['todo']=='login') {
                    wp_redirect (wp_login_url( get_permalink() ));
                }
                }
                break;
            default:
                break;
        }
        }

    }

    public function getSelectedPostTypes()
    {
        return get_option('RBAC_selected_posttypes');
    }

    public function setSelectedPostTypes($posts)
    {
        update_option('RBAC_selected_posttypes', $posts);
    }

    public function get_option($opt)
    {
        $opts=get_option('RBAC_settings');
        return $opts[$opt];
    }

    public function save_settings()
    {
        if(isset($_POST['RBAC_settings']))
            update_option('RBAC_settings', $_POST['RBAC_settings']);
    }

    public function admin_scripts()
    {
        wp_enqueue_style(__CLASS__, plugin_dir_url(__FILE__).'/style.css');
        wp_enqueue_script(__CLASS__.'js', plugin_dir_url(__FILE__).'/js.js',array(),false,true);
    }

    protected function get_pages()
    {
        $posts = get_posts(array(
            'posts_per_page'=>-1,
            'post_type' => 'page',
        ));
        //var_dump($posts);die;
        $pages=array();
        foreach($posts as $post){
            $pages[$post->ID]=$post->post_title;
        }
        $pages=array(''=>'') + $pages;
        return $pages;
    }

    protected function renderOptions($arr ,$selected = '')
    {
        foreach($arr as $key => $val){
            $s=$selected==$key ? ' selected' : '';
            echo "<option value='$key'$s>$val</option>";
        }
    }

    public static function run()
    {
        return new self;
    }

    public static function install()
    {
        update_option('RBAC_selected_posttypes', array('page'));
    }
}
register_activation_hook( __FILE__, array( 'RBACplugin', 'install' ) );
RBACplugin::run();
?>
