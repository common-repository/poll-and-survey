<?php
/**
 * @package poll and survey plugin for wordpress
 */
namespace Pasp\Inc\Base;

class Deactivate
{
    public static function deactivate()
    {
        flush_rewrite_rules( true );
    }
}