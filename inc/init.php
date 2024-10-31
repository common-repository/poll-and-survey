<?php
/**
 * @package poll and survey plugin for wordpress
 */
namespace Pasp\Inc;
require_once 'Base/class-settings-link.php';
require_once 'Base/class-custom-post-type.php';
require_once 'Base/class-enqueue.php';
require_once 'Api/class-settings-api.php';
require_once 'Base/class-custom-metabox.php';
require_once 'Pages/dashboard.php';
require_once 'Pages/shortcode.php';
require_once 'frontend/vote-process-ajax.php';
require_once \dirname(__FILE__) .'/Base/class-widget.php';

class Init
{
    /**
     * @method getClasses
     * @param null
     * contain all services_classes and execute
     */
    public static function getClasses()
    {
        return[
            Pages\Dashboard::class,
            Base\SettingsLink::class,
            Base\Enqueue::class,
            Api\SettingsApi::class,
            Base\CustomPostType::class,
            Base\CustomMetaBox::class,
            Pages\ShortCode::class,
            Frontend\VoteProcess::class,
        ];
    }

    /**
     * @method RegisterServices
     * @param null
     * get array value from getClasses method and then execuation
     */
    public static function RegisterServices()
    {
        if ( self::getClasses() != NULL ) {
            foreach (self::getClasses() as $class) {
                $service = self::instanciate($class);
                if (method_exists($service,'pasp_register')) {
                    $service->pasp_register();
                }
            }
        }
        
    }
    
    /**
     * @method instanciate
     * @param $class 
     * create instanceof all classes
     */
    public static function instanciate( $class )
    {
        $services = new $class;
        return $services;
    }
    
}
