<?
function submitPost($subject, $body, $forum_id)
{
  global $db, $user;
  $sql = 'SELECT *
			FROM ' . FORUMS_TABLE . "
			WHERE forum_id = $forum_id";
	$result = $db->sql_query($sql);
  $post_data = $db->sql_fetchrow($result);
  $db->sql_freeresult($result);

  $message_parser = new parse_message();
  $message_parser->message = utf8_normalize_nfc($body);
  $message_parser->get_submitted_attachment_data($post_data['poster_id']);
  $message_parser->parse(false, false, false, false, false, false, false);
  $message_md5 = md5($message_parser->message);
  
  $poll = array();
  
	$data = array(
		'topic_title'			=> $subject,
		'topic_first_post_id'	=> 0,
		'topic_last_post_id'	=>  0,
		'topic_time_limit'		=> 0,
		'topic_attachment'		=> 0,
		'post_id'				=> 0,
		'topic_id'				=> 0,
		'forum_id'				=> $forum_id,
		'icon_id'				=> 0,
		'poster_id'				=> 8,
		'enable_sig'			=> 0,
		'enable_bbcode'			=> 0,
		'enable_smilies'		=> 0,
		'enable_urls'			=> 0,
		'enable_indexing'		=> 1,
		'message_md5'			=> (string) $message_md5,
		'post_time'				=> 0,
		'post_checksum'			=> '',
		'post_edit_reason'		=> '',
		'post_edit_user'		=>  0,
		'forum_parents'			=> $post_data['forum_parents'],
		'forum_name'			=> $post_data['forum_name'],
		'notify'				=> false,
		'notify_set'			=> 0,
		'poster_ip'				=> $user->ip,
		'post_edit_locked'		=> 0,
		'bbcode_bitfield'		=> '',
		'bbcode_uid'			=> $message_parser->bbcode_uid,
		'message'				=> $message_parser->message,
		'attachment_data'		=> $message_parser->attachment_data,
		'filename_data'			=> $message_parser->filename_data,

		'topic_approved'		=> false,
		'post_approved'			=> false,
	);
	
  submit_post("post", $subject, "", 0, $poll, $data, true);
}