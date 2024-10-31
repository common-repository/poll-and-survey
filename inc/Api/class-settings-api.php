<?php
/**
 * @package poll and survey plugin for wordpress
 */
namespace Pasp\Inc\Api;
require_once \dirname(__FILE__) . '../../Api/Callbacks/class-posttype-callbacks.php';
use Pasp\Inc\Api\Callbacks\PostTypeCallbacks;

class SettingsApi
{
    public $add_pages = array();
    public $add_subpages = array();
    public $add_metabox = array();
    public $postCallbacks;
    public function pasp_register()
    {
        $this->postCallbacks = new PostTypeCallbacks();
        if (!empty($this->add_pages)) {
            add_action( 'admin_menu', array($this,'CreateAdminMenu') );
        }

        if( !empty( $this->add_metabox ) ){
            add_action( 'add_meta_boxes', array($this,'CreateMetaBox') );
        }
        $this->postCallbacks->pasp_save_options();
    }
    public function addPages( array $page )
    {
        $this->add_pages = $page;
        return $this;
    }

    public function duplicateMainMenu(string $title = null)
    {
        if (empty($this->add_pages)) {
            return $this;
        }
        $duplicate_page = $this->add_pages[0];
        
        $subpage = array(
            array(
                'parent_slug'   => $duplicate_page['menu_slug'],
                'page_title'    =>  $duplicate_page['page_title'],
                'menu_title'    =>  ($title) ? $title : $duplicate_page['menu_title'],
                'capability'    =>  $duplicate_page['capability'],
                'menu_slug'     =>  $duplicate_page['menu_slug'],
                'callback'      =>  $duplicate_page['callback'],

            )
        );

        $this->add_subpages = $subpage;
        return $this;

    }

    public function addSubPages( array $subpages )
    {
        $this->add_subpages = array_merge($this->add_subpages,$subpages);
        return $this;
    }

    /**
     * @method addMetaBox
     * @param $metafield
     * get metabox field array from class-custom-metabox.php file
     */
    public function addMetaBox( array $metafield )
    {
        $this->add_metabox = $metafield;
        return $this;
    }
    /**
     * @method CreateAdminMenu
     * create admin main menu
     */
    public function CreateAdminMenu()
    {
        /**
         * main menu loop
         */
        foreach ($this->add_pages as $page) {
            add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
        }
        /**
         * sub menu loop
         */
        foreach ($this->add_subpages as $subpage) {
            add_submenu_page( $subpage['parent_slug'], $subpage['page_title'], $subpage['menu_title'], $subpage['capability'], $subpage['menu_slug'], $subpage['callback'] );
        }
    }

    /**
     * @method CreateMetaBox
     * create metabox
     */
    public function CreateMetaBox()
    {
        foreach ($this->add_metabox as $metabox) {
            add_meta_box( $metabox['id'], $metabox['title'], $metabox['callback'], $metabox['screen'], $metabox['context'], $metabox['priority'] );
        }
        
    }
}
