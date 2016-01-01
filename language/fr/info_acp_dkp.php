<?php
/**
 * bbdkp acp language file for mainmenu (FR)
 *
 * @package phpBB Extension - bbdkp
 * @copyright 2009 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author sajaki <sajaki@gmail.com>
 * @link http://www.bbdkp.com
 * @version 2.0
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
    'ACP_CAT_DKP'			=> 'bbDKP',
	'ACP_DKP_MAINPAGE'		=> 'Paramètres généraux',  
	'ACP_DKP_PANEL'		    	=> 'Statistiques',  
	'ACP_DKP_CONFIG'		=> 'Réglages bbdkp',
	'ACP_DKP_INDEX'			=> 'Réglages portail',
	'ACP_DKP_LOGS'			=> 'Consulter les Logs',
));