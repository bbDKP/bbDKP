<?php
/**
 * bbdkp acp language file for mainmenu - german
 *
 * @package phpBB Extension - bbdkp
 * @copyright 2010 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author sajaki <sajaki@gmail.com>
 * @link http://www.bbdkp.com
 * @version 2.0
 * @translation various unknown authors, killerpommes
 * 
 */

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

// Create the lang array if it does not already exist
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// Merge the following language entries into the lang array
$lang = array_merge($lang, array(
	'ACP_DKP_POOL_ADD'		=> 'DKP Pool hinzufügen',  
	'ACP_DKP_POOL_EDIT'		=> 'DKP Pool bearbeiten',
	'ACP_DKP_POOL_LIST'		=> 'DKP Pools',
	'ACP_DKP_EVENT_ADD'		=> 'Ereignis hinzufügen',
));