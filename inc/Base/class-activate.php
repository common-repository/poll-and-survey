<?php
/**
 * @package poll and survey plugin for wordpress
 */
namespace Pasp\Inc\Base;

class Activate
{
    public static function activate()
    {
        flush_rewrite_rules( true );
    }
}