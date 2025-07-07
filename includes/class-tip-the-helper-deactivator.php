<?php
/**
 * This file contains the definition of the Tip_The_Helper_Deactivator class, which
 * is used during plugin deactivation.
 *
 * @package       Tip_The_Helper
 * @subpackage    Tip_The_Helper/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin deactivation.
 *
 * @since    1.0.0
 */
class Tip_The_Helper_Deactivator {
	/**
	 * Deactivation hook.
	 *
	 * This function is called when the plugin is deactivated. It can be used to
	 * perform tasks such as cleaning up temporary data, unscheduling cron jobs,
	 * or removing options.
	 *
	 * @since     1.0.0
	 * @static
	 * @access    public
	 */
	public static function on_deactivate() {}
}
