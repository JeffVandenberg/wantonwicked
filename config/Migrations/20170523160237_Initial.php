<?php
use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{

    public $autoId = false;

    public function up()
    {

        $this->table('beat_types')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('number_of_beats', 'integer', [
                'default' => null,
                'limit' => 6,
                'null' => false,
            ])
            ->addColumn('admin_only', 'boolean', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created_by_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('updated_by_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('updated', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('may_rollover', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('character_powers')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('character_id', 'integer', [
                'default' => '0',
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('power_type', 'string', [
                'default' => '',
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('power_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('power_note', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('power_level', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('is_public', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('extra', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'character_id',
                    'power_type',
                ]
            )
            ->create();

        $this->table('characters')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => '0',
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('character_name', 'string', [
                'default' => '',
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('show_sheet', 'string', [
                'default' => 'N',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('view_password', 'string', [
                'default' => '',
                'limit' => 30,
                'null' => false,
            ])
            ->addColumn('character_type', 'string', [
                'default' => '',
                'limit' => 15,
                'null' => false,
            ])
            ->addColumn('city', 'string', [
                'default' => '',
                'limit' => 15,
                'null' => false,
            ])
            ->addColumn('age', 'integer', [
                'default' => '0',
                'limit' => 5,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('sex', 'string', [
                'default' => 'Male',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('apparent_age', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('concept', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('url', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('safe_place', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('friends', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('exit_line', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('icon', 'string', [
                'default' => '0',
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('is_npc', 'string', [
                'default' => 'N',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('virtue', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('vice', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('splat1', 'string', [
                'default' => '',
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('splat2', 'string', [
                'default' => '',
                'limit' => 30,
                'null' => false,
            ])
            ->addColumn('subsplat', 'string', [
                'default' => '',
                'limit' => 40,
                'null' => false,
            ])
            ->addColumn('size', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('speed', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('initiative_mod', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('defense', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('armor', 'string', [
                'default' => '0',
                'limit' => 5,
                'null' => false,
            ])
            ->addColumn('health', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('wounds_agg', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('wounds_lethal', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('wounds_bashing', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('willpower_perm', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('willpower_temp', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('power_stat', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('power_points', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('morality', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('merits', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('flaws', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('equipment_public', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('equipment_hidden', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('public_effects', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('history', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('character_notes', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('goals', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('is_sanctioned', 'string', [
                'default' => '',
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('asst_sanctioned', 'string', [
                'default' => '',
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('is_deleted', 'string', [
                'default' => 'N',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('current_experience', 'float', [
                'default' => '0.00',
                'null' => false,
                'precision' => 6,
                'scale' => 2,
            ])
            ->addColumn('total_experience', 'float', [
                'default' => '0.00',
                'null' => false,
                'precision' => 6,
                'scale' => 2,
            ])
            ->addColumn('bonus_received', 'integer', [
                'default' => null,
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('updated_by_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('updated_on', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('gm_notes', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('sheet_update', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('hide_icon', 'string', [
                'default' => 'N',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('helper', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('status', 'string', [
                'default' => '',
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('bonus_attribute', 'string', [
                'default' => '',
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('misc_powers', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('average_power_points', 'decimal', [
                'default' => '0.00',
                'null' => false,
                'precision' => 4,
                'scale' => 2,
            ])
            ->addColumn('power_points_modifier', 'decimal', [
                'default' => '0.00',
                'null' => false,
                'precision' => 4,
                'scale' => 2,
            ])
            ->addColumn('temporary_health_levels', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('is_suspended', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('location_id', 'integer', [
                'default' => '1',
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('gameline', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->addIndex(
                [
                    'is_sanctioned',
                ]
            )
            ->addIndex(
                [
                    'is_deleted',
                ]
            )
            ->addIndex(
                [
                    'character_type',
                    'splat1',
                ]
            )
            ->addIndex(
                [
                    'location_id',
                ]
            )
            ->addIndex(
                [
                    'slug',
                ]
            )
            ->create();

        $this->table('configurations')
            ->addColumn('key', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addPrimaryKey(['key'])
            ->addColumn('value', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('description', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('data_type', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->create();

        $this->table('group_types')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('description', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->create();

        $this->table('groups')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('group_type_id', 'integer', [
                'default' => null,
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('is_deleted', 'boolean', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created_by', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->create();

        $this->table('permissions')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('permission_name', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => false,
            ])
            ->create();

        $this->table('phpbb_groups')
            ->addColumn('group_id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 8,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['group_id'])
            ->addColumn('group_type', 'integer', [
                'default' => '1',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('group_founder_manage', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('group_name', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('group_desc', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('group_desc_bitfield', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('group_desc_options', 'integer', [
                'default' => '7',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('group_desc_uid', 'string', [
                'default' => '',
                'limit' => 8,
                'null' => false,
            ])
            ->addColumn('group_display', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('group_avatar', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('group_avatar_type', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('group_avatar_width', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('group_avatar_height', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('group_rank', 'integer', [
                'default' => '0',
                'limit' => 8,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('group_colour', 'string', [
                'default' => '',
                'limit' => 6,
                'null' => false,
            ])
            ->addColumn('group_sig_chars', 'integer', [
                'default' => '0',
                'limit' => 8,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('group_receive_pm', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('group_message_limit', 'integer', [
                'default' => '0',
                'limit' => 8,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('group_legend', 'integer', [
                'default' => '0',
                'limit' => 8,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('group_max_recipients', 'integer', [
                'default' => '0',
                'limit' => 8,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('group_skip_auth', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'group_legend',
                    'group_name',
                ]
            )
            ->create();

        $this->table('phpbb_users')
            ->addColumn('user_id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 8,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['user_id'])
            ->addColumn('user_type', 'integer', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('group_id', 'integer', [
                'default' => '3',
                'limit' => 8,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_permissions', 'text', [
                'default' => null,
                'limit' => 16777215,
                'null' => false,
            ])
            ->addColumn('user_perm_from', 'integer', [
                'default' => '0',
                'limit' => 8,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_ip', 'string', [
                'default' => '',
                'limit' => 40,
                'null' => false,
            ])
            ->addColumn('user_regdate', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('username', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('username_clean', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('user_password', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('user_passchg', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_email', 'string', [
                'default' => '',
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('user_email_hash', 'biginteger', [
                'default' => '0',
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('user_birthday', 'string', [
                'default' => '',
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('user_lastvisit', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_lastmark', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_lastpost_time', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_lastpage', 'string', [
                'default' => '',
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('user_last_confirm_key', 'string', [
                'default' => '',
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('user_last_search', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_warnings', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('user_last_warning', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_login_attempts', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('user_inactive_reason', 'integer', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('user_inactive_time', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_posts', 'integer', [
                'default' => '0',
                'limit' => 8,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_lang', 'string', [
                'default' => '',
                'limit' => 30,
                'null' => false,
            ])
            ->addColumn('user_timezone', 'string', [
                'default' => '',
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('user_dateformat', 'string', [
                'default' => 'd M Y H:i',
                'limit' => 64,
                'null' => false,
            ])
            ->addColumn('user_style', 'integer', [
                'default' => '0',
                'limit' => 8,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_rank', 'integer', [
                'default' => '0',
                'limit' => 8,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_colour', 'string', [
                'default' => '',
                'limit' => 6,
                'null' => false,
            ])
            ->addColumn('user_new_privmsg', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('user_unread_privmsg', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('user_last_privmsg', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_message_rules', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_full_folder', 'integer', [
                'default' => '-3',
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('user_emailtime', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_topic_show_days', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_topic_sortby_type', 'string', [
                'default' => 't',
                'limit' => 1,
                'null' => false,
            ])
            ->addColumn('user_topic_sortby_dir', 'string', [
                'default' => 'd',
                'limit' => 1,
                'null' => false,
            ])
            ->addColumn('user_post_show_days', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_post_sortby_type', 'string', [
                'default' => 't',
                'limit' => 1,
                'null' => false,
            ])
            ->addColumn('user_post_sortby_dir', 'string', [
                'default' => 'a',
                'limit' => 1,
                'null' => false,
            ])
            ->addColumn('user_notify', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_notify_pm', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_notify_type', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('user_allow_pm', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_allow_viewonline', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_allow_viewemail', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_allow_massemail', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_options', 'integer', [
                'default' => '230271',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_avatar', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('user_avatar_type', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('user_avatar_width', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_avatar_height', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_sig', 'text', [
                'default' => null,
                'limit' => 16777215,
                'null' => false,
            ])
            ->addColumn('user_sig_bbcode_uid', 'string', [
                'default' => '',
                'limit' => 8,
                'null' => false,
            ])
            ->addColumn('user_sig_bbcode_bitfield', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('user_jabber', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('user_actkey', 'string', [
                'default' => '',
                'limit' => 32,
                'null' => false,
            ])
            ->addColumn('user_newpasswd', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('user_form_salt', 'string', [
                'default' => '',
                'limit' => 32,
                'null' => false,
            ])
            ->addColumn('user_new', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_reminded', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('user_reminded_time', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('role_id', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('board_announcements_status', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'username_clean',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'user_birthday',
                ]
            )
            ->addIndex(
                [
                    'user_email_hash',
                ]
            )
            ->addIndex(
                [
                    'user_type',
                ]
            )
            ->create();

        $this->table('play_preference_response_history')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('play_preference_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('rating', 'integer', [
                'default' => null,
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('created_on', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'user_id',
                    'play_preference_id',
                ]
            )
            ->addIndex(
                [
                    'play_preference_id',
                ]
            )
            ->create();

        $this->table('play_preference_responses')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('play_preference_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('rating', 'integer', [
                'default' => null,
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('note', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('created_on', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'play_preference_id',
                    'user_id',
                ]
            )
            ->addIndex(
                [
                    'play_preference_id',
                ]
            )
            ->create();

        $this->table('play_preferences')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('created_by_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created_on', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('updated_by_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('updated_on', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->create();

        $this->table('request_templates')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('description', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('content', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('roles')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'name',
                ],
                ['unique' => true]
            )
            ->create();

        $this->table('scene_characters')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('scene_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('character_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('note', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('added_on', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'scene_id',
                ]
            )
            ->addIndex(
                [
                    'character_id',
                ]
            )
            ->create();

        $this->table('scene_requests')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('scene_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('request_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('note', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('added_on', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'scene_id',
                ]
            )
            ->addIndex(
                [
                    'request_id',
                ]
            )
            ->create();

        $this->table('scene_statuses')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 5,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->create();

        $this->table('scenes')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('summary', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('run_by_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('run_on_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created_by_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('created_on', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('updated_by_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('updated_on', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('scene_status_id', 'integer', [
                'default' => null,
                'limit' => 5,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(
                [
                    'run_by_id',
                ]
            )
            ->addIndex(
                [
                    'slug',
                ]
            )
            ->addIndex(
                [
                    'run_on_date',
                ]
            )
            ->addIndex(
                [
                    'scene_status_id',
                ]
            )
            ->create();
    }

    public function down()
    {
        $this->dropTable('beat_types');
        $this->dropTable('character_powers');
        $this->dropTable('characters');
        $this->dropTable('configurations');
        $this->dropTable('group_types');
        $this->dropTable('groups');
        $this->dropTable('permissions');
        $this->dropTable('phpbb_groups');
        $this->dropTable('phpbb_users');
        $this->dropTable('play_preference_response_history');
        $this->dropTable('play_preference_responses');
        $this->dropTable('play_preferences');
        $this->dropTable('request_templates');
        $this->dropTable('roles');
        $this->dropTable('scene_characters');
        $this->dropTable('scene_requests');
        $this->dropTable('scene_statuses');
        $this->dropTable('scenes');
    }
}
