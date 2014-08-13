<?php
/**
 * Recruitment Class
 *
 * @package bbdkp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.1
 */
namespace bbdkp\controller\guilds;
/**
 * @ignore
 */
use bbdkp\controller\games\Roles;

if (! defined('IN_PHPBB'))
{
	exit();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

// Include the base class

if (!class_exists('\bbdkp\controller\games\Classes'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/games/classes/Classes.$phpEx");
}

if (!class_exists('\bbdkp\controller\guilds\Guilds'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/guilds/Guilds.$phpEx");
}
if (!class_exists('\bbdkp\controller\games\Roles'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/games/roles/Roles.$phpEx");
}

/**
 * holds vacancies per guild, game, role and class
 *
 * class hierarchy Game --> Roles --> Recruitment
 *   @package bbdkp
 *
 */
class Recruitment extends Roles
{
    /**
     * primary key
     */
    public $id;

    /**
     * guild for which we want to recruit
     *
     * @var int
     */
    protected $guild_id;

    /**
     * Class id needed
     *
     * @var int
     */
    protected $class_id;

    /**
     * how many are needed ?
     *
     * @var int
     */
    protected $positions;

    /**
     * how many did apply ?
     *
     * @var int
     */
    protected $applicants;

    /**
     * date last update -- epoch date
     *
     * @var int
     */
    protected $lest_update;

    /**
     * a note on this recruitment
     *
     * @var int
     */
    protected $note;

    /**
     * status of recruitment
     * 0 to 3
     *
     * @var int
     */
    protected $status;

    /**
     * possible recruitment statuses
     *
     * @var array
     */
    private $recruitstatus = array();

    /**
     * possible recruitment status colors
     *
     * @var array
     */
    private $classreccolor = array();

    /**
     * Recruitment class constructor
     *
     */
    public function __construct()
    {
        global $user;
        $this->recruitstatus = array(
            0 => $user->lang['CLOSED'],
            1 => $user->lang['LOW'],
            2 => $user->lang['MEDIUM'],
            3 => $user->lang['HIGH']);

        $this->classreccolor = array(
            0 => "bullet_white.png",
            1 => "bullet_yellow.png",
            2 => "bullet_red.png",
            3 => "bullet_purple.png");
    }

    /**
     * construct recruitment object
     *
     * @param int $id
     * @return int|void
     */
    public function get($id = 0)
    {
        global $config, $db;
        $sql_array = array(
            'SELECT'   => " u.id, u.game_id, u.guild_id, u.role_id, u.class_id, u.positions, u.applicants, u.status, u.last_update, u.note,
                r.role_color, r.role_icon, role_cat_icon, l.name as role_name ",
            'FROM'     => array(
                BBRECRUIT_TABLE   => 'u',
                GUILD_TABLE       => 'g',
                BBDKP_ROLES_TABLE => 'r',
                BB_LANGUAGE       => 'l',
            ),
            'WHERE'    => " 1=1
                AND u.guild_id = g.id
                AND u.role_id = r.role_id
                AND l.attribute='role' and
                AND r.game_id=l.game_id AND l.attribute_id = r.role_id  AND l.language = '" . $config['bbdkp_lang'] . "' and l.attribute='role'
                AND u.id = " . (int)$this->id,
            'ORDER_BY' => ' u.role_id '
        );
        $sql    = $db->sql_build_query('SELECT', $sql_array);
        $result = $db->sql_query($sql);
        $row    = $db->sql_fetchrow($result);
        $db->sql_freeresult($result);
        if (!$row)
        {
            return 0;
        } else
        {
            $this->guild_id      = $row['guild_id'];
            $this->game_id       = $row['game_id'];
            $this->role_id       = $row['role_id'];
            $this->class_id      = $row['class_id'];
            $this->rolename      = $row['role_name'];
            $this->role_color    = $row['role_color'];
            $this->role_icon     = $row['role_icon'];
            $this->role_cat_icon = $row['role_cat_icon'];
            $this->positions     = $row['positions'];
            $this->applicants    = $row['applicants'];
            $this->note          = $row['note'];
            $this->last_update   = $row['last_update'];
            $this->status        = $row['status'];
            return 1;
        }
    }

    /**
     * inserts a new recruitment
     */
    public function make()
    {
        global $db;
        $query = $db->sql_build_array('INSERT', array(
            'guild_id'   => $this->guild_id,
            'role_id'    => $this->role_id,
            'class_id'   => $this->class_id,
            'positions'  => $this->positions,
            'applicants' => $this->applicants,
            'note'       => $this->note,
            'last_update' => $this->last_update,
            'status'     => $this->status,
        ));
        $db->sql_query('INSERT INTO ' . BBRECRUIT_TABLE . $query);
        return 1;
    }

    /**
     * update my roles
     */
    public function update()
    {
        global $db;
        $query = $db->sql_build_array('UPDATE', array(
            'guild_id'   => $this->guild_id,
            'role_id'    => $this->role_id,
            'class_id'   => $this->class_id,
            'positions'  => $this->positions,
            'applicants' => $this->applicants,
            'note'       => $this->note,
            'last_update' => $this->last_update,
            'status'     => $this->status,
        ));
        $db->sql_query('UPDATE ' . BBRECRUIT_TABLE . ' SET ' . $query . ' WHERE id = ' . $this->id);
    }

    /**
     * deletes a role
     */
    public function delete()
    {
        global $db;
        $sql = 'DELETE FROM ' . BBRECRUIT_TABLE . ' WHERE id = ' . $this->id;
        $db->sql_query($sql);
    }

    /**
     * get all current recruitments for a guild
     *
     */
    public function ListRecruitments()
    {
        global $config, $db;

        $sql_array = array(

            'SELECT'   => " u.id, u.game_id, u.guild_id, u.role_id,
                u.class_id, u.positions, u.applicants, u.status, u.last_update, u.note,
                r.role_color, r.role_icon, r.role_cat_icon, r1.name as role_name,
                c1.name as class_name, c.colorcode, c.imagename
                 ",

            'FROM'     => array(
                BBRECRUIT_TABLE   => 'u',
                GUILD_TABLE       => 'g',
                BBDKP_ROLES_TABLE => 'r',
                BB_LANGUAGE       => 'r1',
                CLASS_TABLE       => 'c',
                BB_LANGUAGE       => 'c1',
            ),

            'WHERE'    => " 1=1
                AND u.guild_id = g.id
                AND u.role_id = r.role_id
                AND l.attribute='role' and
                AND r.game_id=l.game_id AND l.attribute_id = r.role_id  AND l.language = '" . $config['bbdkp_lang'] . "' and l.attribute='role'
                AND c.class_id > 0 AND c.class_id = u.class_id AND c.game_id = g.game_id
                AND c.game_id=c1.game_id AND c1.attribute_id = c.class_id  AND l.language = '" . $config['bbdkp_lang'] . "' and l.attribute='class'
                AND g.id =  " . $this->guild_id,
            'ORDER_BY' => 'c.game_id, c.class_id '
        );
        $sql          = $db->sql_build_query('SELECT', $sql_array);
        $result       = $db->sql_query($sql);
        $recruitments = (array)$result;
        $db->sql_freeresult($result);
        return $recruitments;
    }


    /**
     * get the guilds that recruit
     *
     * @return array
     */
    public function get_recruiting_guilds()
    {
        global $db;
        $sql_array = array(
            'SELECT'   => " g.id, g.name, g.emblemurl, rec_status ",
            'FROM'     => array(
                GUILD_TABLE     => 'g',
                BBRECRUIT_TABLE => 'r'),
            'WHERE'    => "r.guild_id = g.id AND r.needed > 0 ",
            'GROUP_BY' => ' g.name ',
            'ORDER_BY' => ' g.name ');
        $sql    = $db->sql_build_query('SELECT', $sql_array);
        $result = $db->sql_query($sql);
        return $result;
    }
}
