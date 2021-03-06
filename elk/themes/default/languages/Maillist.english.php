<?php

// Email posting errors
$txt['error_locked'] = 'This topic has been locked and can no longer be replied to';
$txt['error_locked_short'] = 'Topic Locked';
$txt['error_cant_start'] = 'Not authorized to start a new topic on the supplied board';
$txt['error_cant_start_short'] = 'Cant Start New Topic';
$txt['error_cant_reply'] = 'Not authorized to reply';
$txt['error_cant_reply_short'] = 'Off Limits Topic';
$txt['error_topic_gone'] = 'The topic could not be found - it may have been deleted or merged.';
$txt['error_topic_gone_short'] = 'Deleted Topic';
$txt['error_not_find_member'] = 'Your email address could not be found in the member database, only members may post.';
$txt['error_not_find_member_short'] = 'Email ID not in Database';
$txt['error_key_sender_match'] = 'The email key, while valid, was not sent to the email address that replied with the key.  You must reply from the same email that received the message';
$txt['error_key_sender_match_short'] = 'Key Mismatch';
$txt['error_not_find_entry'] = 'It appears that you already replied to this email.  If you need to modify your post please use the web interface, if you are making another reply to this topic please reply to the latest notification';
$txt['error_not_find_entry_short'] = 'Key Expired';
$txt['error_pm_not_found'] = 'The personal message you were replying to could not be found.';
$txt['error_pm_not_found_short'] = 'PM Missing';
$txt['error_pm_not_allowed'] = 'You do not have permission to send personal messages!';
$txt['error_pm_not_allowed_short'] = 'Not authorized to PM';
$txt['error_no_message'] = 'We could not find any message in the Email, you must supply one in order to post';
$txt['error_no_message_short'] = 'Blank Message';
$txt['error_no_subject'] = 'You must supply a Subject to start a new post, none was found';
$txt['error_no_subject_short'] = 'No Subject';
$txt['error_board_gone'] = 'The board you tried to post to was either invalid of off limits to you';
$txt['error_board_gone_short'] = 'Invalid or protected Board';
$txt['error_missing_key'] = 'Unable to find the key in the reply.  In order for emails to be accepted, they must be sent in reply to a valid notification email and the reply must be done from the same email address the notification was sent to.';
$txt['error_missing_key_short'] = 'Missing Key';
$txt['error_found_spam'] = 'Warning: Your post has been classified as potential spam by your spam filter and has not been posted.';
$txt['error_found_spam_short'] = 'Potential Spam';
$txt['error_pm_not_find_entry'] = 'It appears that you already replied to this personal message.  If you need to make another reply please use the web interface or wait until you receive another reply.';
$txt['error_pm_not_find_entry_short'] = 'PM Key Expired';
$txt['error_not_find_board'] = 'Attempted to start a new topic to a non existing Board, potential hacking attempt';
$txt['error_not_find_board_short'] = 'No Such Board';
$txt['error_no_pm_attach'] = '[PM attachments are not supported]';
$txt['error_no_attach'] = '[Email attachments are disabled]';
$txt['error_in_maintenance_mode'] = 'Email received while in maintenance mode and could not be posted at that time';
$txt['error_in_maintenance_mode_short'] = 'In Maintenance';
$txt['error_email_notenabled_short'] = 'Not Enabled';
$txt['error_email_notenabled'] = 'The post by email function was not enabled, the email could not be processed';
$txt['error_permission'] = 'The poster does not have post by email permissions on this board';

// Maillist page items
$txt['ml_admin_configuration'] = 'Maillist Configuration';
$txt['ml_configuration_desc'] = 'This section allows you to set some preferences for all posting by email related activities';
$txt['ml_email'] = 'Post by Email';
$txt['ml_emailerror_none'] = 'There are no failed entries requiring moderation';
$txt['ml_email_desc'] = 'This section allows you to set some preferences for post by email related activities';
$txt['ml_site_settings'] = 'Site Email Details';
$txt['ml_emailerror'] = 'Failed Emails';
$txt['ml_emailfilters'] = 'Filters';
$txt['ml_emailparsers'] = 'Parsers';
$txt['ml_emailsettings'] = 'Settings';

// Settings tab
$txt['maillist_enabled'] = 'Enable Maillist Functions (master on/off setting)';
$txt['pbe_post_enabled'] = 'Allow posting to the forum by Email';
$txt['pbe_pm_enabled'] = 'Allow replying to PM\'s by Email';
$txt['pbe_no_mod_notices'] = 'Turn off moderation notices';
$txt['pbe_no_mod_notices_desc'] = 'Do not send notifications of moved, locked, deleted, merged, etc.  These consume your email quota with no real purpose';
$txt['settings_title'] = 'From this page you can choose how you want the post by email modification to behave';
$txt['saved'] = 'Information Saved';

// General Sending Settings
$txt['maillist_outbound'] = 'General Sending Settings';
$txt['maillist_outbound_desc'] = 'Use these settings to modify how outbound emails appear to the user and where a reply will be sent to.  ';
$txt['maillist_group_mode'] = 'Enable group maillist mode';
$txt['maillist_digest_enabled'] = 'Enable enhanced daily digest (provides topic snips in the digest)';
$txt['maillist_sitename'] = 'Site Name to use for the email (not the email address)';
$txt['maillist_sitename_desc'] = 'This is the name for the email address, something familiar to the users as this will appear in several areas of the outbound email, including the subject line - [Site Name] subject';
$txt['maillist_sitename_post'] = 'i.e. &lt;<strong>Site Name</strong>&gt;emailpost@yourdomain.com';
$txt['maillist_sitename_address'] = 'Reply-To and From email address';
$txt['maillist_sitename_address_desc'] = 'The email address that replied to messages will be sent. If empty the notification (if set) or webmaster one will be used.';
$txt['maillist_sitename_regards'] = 'Email "signature"';
$txt['maillist_sitename_regards_desc'] = 'What to put at the end of outbound emails, something like "Regards, the Site Name Team"';
$txt['maillist_sitename_address_post'] = 'i.e. emailpost@yourdomain.com';
$txt['maillist_sitename_help'] = 'Help email address';
$txt['maillist_sitename_help_desc'] = 'Used for the "List Owner" header to help prevent outbound email from being flagged as spam.';
$txt['maillist_sitename_help_post'] = 'i.e. help@yourdomain.com';
$txt['maillist_mail_from'] = 'Notifications email address';
$txt['maillist_mail_from_desc'] = 'The email address used for password reminders, notifications, etc.  If left empty the webmaster address will be used (this is the default)';
$txt['maillist_mail_from_post'] = 'i.e. noreply@yourdomain.com';

// Imap settings
$txt['maillist_imap'] = 'IMAP Settings';
$txt['maillist_imap_host'] = 'Mailbox Server Name';
$txt['maillist_imap_host_desc'] = 'Enter a server name, optional port. For example: imap.gmail.com:993';
$txt['maillist_imap_mailbox'] = 'Mailbox Name';
$txt['maillist_imap_mailbox_desc'] = 'Enter a mailbox name on the server, For example: INBOX';
$txt['maillist_imap_uid'] = 'Mailbox Username';
$txt['maillist_imap_uid_desc'] = 'User name to login to the mailbox.';
$txt['maillist_imap_pass'] = 'Mailbox Password';
$txt['maillist_imap_pass_desc'] = 'Password to login to the mailbox.';
$txt['maillist_imap_connection'] = 'Mailbox Connection';
$txt['maillist_imap_connection_desc'] = 'Type of connection to use, IMAP or POP3 (in normal, tls or ssl mode).';
$txt['maillist_imap_unsecure'] = 'IMAP';
$txt['maillist_pop3_unsecure'] = 'POP3';
$txt['maillist_imap_tls'] = 'IMAP/TLS';
$txt['maillist_imap_ssl'] = 'IMAP/SSL';
$txt['maillist_pop3_tls'] = 'POP3/TLS';
$txt['maillist_pop3_ssl']= 'POP3/SSL';
$txt['maillist_imap_delete'] = 'Delete Messages';
$txt['maillist_imap_delete_desc'] = 'Attempt to remove mailbox messages that have been retrieved.';
$txt['maillist_imap_reason'] = 'The following should be left BLANK if you intend to pipe messages into the forum (recommended)';
$txt['maillist_imap_missing'] = 'IMAP functions are not installed on your system, no settings are available';
$txt['maillist_imap_cron'] = 'Fake-Cron (scheduled task)';
$txt['maillist_imap_cron_desc'] = 'If you can\'t run a cron job on you system, as a last resort check this to instead run this as an SMF scheduled task';
$txt['scheduled_task_desc_pbeIMAP'] = 'Runs the post by email IMAP mailbox program to read new email from the designated inbox';

// General Receiving Settings
$txt['maillist_inbound'] = 'General Receiving Settings';
$txt['maillist_inbound_desc'] = 'Use these settings to determine the actions the system will take when an new topic email is received.  This does not affect replies to our notifications';
$txt['maillist_newtopic_change'] = 'Allow the starting of a new topic by changing the reply subject';
$txt['maillist_newtopic_needsapproval'] = 'Require New Topic approval';
$txt['maillist_newtopic_needsapproval_desc'] = 'Require all new topics sent by email to be approved before they are posted to prevent email spoofing';
$txt['recommended'] = 'This is recommended';
$txt['receiving_address'] = 'Receiving email addresses';
$txt['receiving_board'] = 'Board to Post it to';
$txt['reply_add_more'] = 'Add another address';
$txt['receiving_address_desc'] = 'Enter a list of email address followed by board to where received email should be posted,  This is needed to start a NEW topic in a specific board, members must send an email to that email address and it will post in the corresponding board.  To remove and existing item, just clear the email address and save';
$txt['email_not_valid'] = 'The email address (%s) is not valid';
$txt['board_not_valid'] = 'You have entered an invalid board id (%d)';

// Other settings
$txt['misc'] = 'Other Settings';
$txt['maillist_allow_attachments'] = 'Allow email file attachments to be posted (will not work for PM\'s)';
$txt['maillist_key_active'] = 'Days to keep keys active in the database';
$txt['maillist_key_active_desc'] = 'i.e. How long after a notification is sent are you willing to accept a response';
$txt['maillist_sig_keys'] = 'Words that signify the start of someones signature';
$txt['maillist_sig_keys_desc'] = 'Separate words with a | character, suggested to use "best|regard|thank" lines starting with these will be triggered as the start of a signature line';
$txt['maillist_leftover_remove'] = 'Lines that are left over from emails';
$txt['maillist_leftover_remove_desc'] = 'Separate words with a | character suggested to use "To: |Re: |Sent: |Subject: |Date: |From: ", Most things get removed by the parser but some things end up in quotes.  Don\'t add to this unless you know what you are doing.';
$txt['maillist_short_line'] = 'Short line length, used to unwrap emails';
$txt['maillist_short_line_desc'] = 'Changing this from the default may cause unusual results, change with caution';

// Failed log actions
$txt['approved'] = 'Email was approved and posted';
$txt['error_approved'] = 'There was an error trying to approve this email';
$txt['id'] = '#';
$txt['error'] = 'Error';
$txt['subject'] = 'Subject';
$txt['to'] = 'To:';
$txt['key'] = 'Key';
$txt['message_id'] = 'Message';
$txt['from'] = 'From';
$txt['message_type'] = 'Type';
$txt['message_action'] = 'Actions';
$txt['emailerror_title'] = 'Failed Email Log';
$txt['show_notice'] = 'Email Details';
$txt['private'] = 'Private';
$txt['show_notice_text'] = 'Post text';
$txt['noaccess'] = 'Private Messages can not be reviewed';
$txt['badid'] = 'Invalid or missing email ID';
$txt['delete_warning'] = 'Are you sure you want to delete this entry?';
$txt['filter_delete_warning'] = 'Are you sure you want to remove this Filter?';
$txt['parser_delete_warning'] = 'Are you sure you want to remove this Parser?';
$txt['bounce'] = 'Bounce';
$txt['heading'] = 'This is the failed post by email listing, from here you can choose to view, approve (if possible), delete or bounce back to the sender';
$txt['cant_find'] = 'The email file could not be found';
$txt['no_subject'] = 'No Subject';
$txt['cant_approve'] = 'The error does not allow for the item to be approved (can\'t auto repair)';
$txt['actions'] = 'Actions';
$txt['email_attachments'] = '[There are %d Email attachments in this message]';
$txt['email_failure'] = 'Failure Reason';

// Filters
$txt['filters'] = 'Email Filters';
$txt['add_filter'] = 'Add Filter';
$txt['edit_filter'] = 'Edit Existing Filter';
$txt['no_filters'] = 'You have not defined any filters';
$txt['error_no_filter'] = 'Unable to find/load specified filter';
$txt['regex_invalid'] = 'The Regex is not valid';
$txt['filter_to'] = 'Replacement Text';
$txt['filter_to_desc'] = 'Replace the found text with this';
$txt['filter_from'] = 'Search Text';
$txt['filter_from_desc'] = 'Enter the text you want to search for';
$txt['filter_type'] = 'Type';
$txt['filter_type_desc'] = 'Standard will find the exact phase and replace it with the text in the replace field.  Regular Expression is the wildcard option of Standard, but must in in regex format.';
$txt['filter_name'] = 'Name';
$txt['filter_name_desc'] = 'Optionally enter a name to help you remember what this filter does';
$txt['filters_title'] = 'From this area you can add, edit or remove email filters. Filters search for specific text in a reply and then replace that with the text of your choosing, usually nothing.';
$txt['filter_invalid'] = 'The definition is not valid and could not be saved';
$txt['error_no_id_filter'] = 'The filter id is not valid';
$txt['saved_filter'] = 'The filter was saved successfully';

// Parsers
$txt['saved_parser'] = 'The parser was saved successfully';
$txt['error_no_id_parser'] = 'The parser id is not valid';
$txt['add_parser'] = 'Add Parser';
$txt['edit_parser'] = 'Edit Existing Parser';
$txt['parsers'] = 'Email Parsers';
$txt['parser_from'] = 'Find Original Email';
$txt['parser_from_desc'] = 'Enter the starting id of the original email, the system will cut the message at this point leaving only the new message (if possible).  If using a regular expression it must be properly delimited';
$txt['parser_type'] = 'Type';
$txt['parser_type_desc'] = 'Standard will find the exact phase and cut the email at that point.  Regular Expression is the wildcard option of Standard, but must in in regex format.';
$txt['parser_name'] = 'Name';
$txt['parser_name_desc'] = 'Optionally enter a name to help you remember what email client this parser is for';
$txt['no_parsers'] = 'You have not defined any parsers';
$txt['parsers_title'] = 'From this area you can add, edit or remove email parsers.  Parsers look for the specific line and cut the message at that point in an effort to remove the original replied to message. If a parser results in no text (e.g a reply below or intermixed in the original message) then it will be skipped';
$txt['option_standard'] = 'Standard';
$txt['option_regex'] = 'Regular Expression';
$txt['button_edit'] = 'Edit';
$txt['button_delete'] = 'Delete';

// Bounce
$txt['bounce_subject'] = 'Failure';
$txt['bounce_error'] = 'Error';
$txt['bounce_title'] = 'Bounced Email Creator';
$txt['bounce_notify_subject'] = 'Bounce Notification Subject';
$txt['bounce_notify'] = 'Send a Bounce Notification';
$txt['bounce_notify_template'] = 'Select template';
$txt['bounce_notify_body'] = 'Bounce Notification Message';
$txt['bounce_issue'] = 'Send Bounce';
$txt['bad_bounce'] = 'The bounce message and/or subject is blank and can not be sent';

// Subject tags
$txt['RE:'] = 'RE:';
$txt['FW:'] = 'FW:';
$txt['FWD:'] = 'FWD:';
$txt['SUBJECT:'] = 'SUBJECT:';

// Quote strings
$txt['email_wrote'] = 'Wrote';
$txt['email_quoting'] = 'Quoting';
$txt['email_quotefrom'] = 'Quote from';
$txt['email_on'] = 'On';
$txt['email_at'] = 'at';

// Our digest strings for the digest "template"
$txt['digest_preview'] = "\n     <*> Topic Preview:\n     ";
$txt['digest_see_full'] = "\n\n     <*> See the full Topic at the following link:\n     <*> ";
$txt['digest_reply_preview'] = "\n     <*> Latest Reply Preview:\n     ";
$txt['digest_unread_reply_link'] = "\n\n     <*> See your unread replies to this topic at the following link:\n     <*> ";
$txt['message_attachments'] = '<*> This message has (%d) images/files associated with it.' . "\n" . '<*> To see them please follow this link: %s';

// Profile Notifications Page
$txt['notify_settings'] = 'Notification Settings:';
$txt['notify_save'] = 'Save settings';
$txt['notify_important_email'] = 'Receive forum newsletters, announcements and important notifications by email.';
$txt['notify_regularity'] = 'For topics and boards I\'ve requested notification on, notify me';
$txt['notify_regularity_instant'] = 'Instantly';
$txt['notify_regularity_first_only'] = 'Instantly - but only for the first unread reply';
$txt['notify_regularity_daily'] = 'Daily';
$txt['notify_regularity_weekly'] = 'Weekly';
$txt['auto_notify'] = 'Turn notification on when you post or reply to a topic.';
$txt['notify_send_types'] = 'For topics and boards I\'ve requested notification on, notify me of';
$txt['notify_send_type_everything'] = 'Replies and moderation';
$txt['notify_send_type_everything_own'] = 'Moderation only if I started the topic';
$txt['notify_send_type_only_replies'] = 'Only replies';
$txt['notify_send_type_nothing'] = 'Nothing at all';
$txt['notify_send_body'] = 'When sending notification of a reply to a topic, send the post in the email (but please don\'t reply to these emails.)';

$txt['notifications_topics'] = 'Current Topic Notifications';
$txt['notifications_topics_list'] = 'You are being notified of replies to the following topics';
$txt['notifications_topics_none'] = 'You are not currently receiving any notifications from topics.';
$txt['notifications_topics_howto'] = 'To receive notifications from a topic, click the &quot;notify&quot; button while viewing it.';
$txt['notifications_boards'] = 'Current Board Notifications';
$txt['notifications_boards_list'] = 'You are being notified of new topics posted in the following boards';
$txt['notifications_boards_none'] = 'You aren\'t receiving notifications on any boards right now.';
$txt['notifications_boards_howto'] = 'To request notifications from a specific board, click the &quot;notify&quot; button in the index of that board.';

$txt['notify_settings'] = 'Email Notification Settings:';
$txt['notify_save'] = 'Save settings';
$txt['notify_important_email'] = 'Receive forum newsletters, announcements and important notifications by email.';
$txt['notify_regularity'] = 'For topics and boards I\'ve requested notification on, notify me';
$txt['notify_regularity_instant'] = 'Instantly for all posts';
$txt['notify_regularity_first_only'] = 'Instantly - but only for the first (new) post in a topic.';
$txt['notify_regularity_daily'] = 'Daily Summary';
$txt['notify_regularity_weekly'] = 'Weekly Summary';
$txt['auto_notify'] = 'Turn email topic notification on when you post in a topic. This is <b>not</b> recommended when you use "board" notifications.</p>';
$txt['notify_send_types'] = 'For topics and boards I\'ve requested notification on, email me';
$txt['notify_send_type_everything'] = 'Messages and moderation items';
$txt['notify_send_type_everything_own'] = 'Messages and moderation items only if I started the topic';
$txt['notify_send_type_only_replies'] = 'All messages';
$txt['notify_send_type_nothing'] = 'Nothing at all';
$txt['notify_send_body'] = 'When sending email notifications, send the full text of the post in the email (NOT available with Daily / Weekly summary).';

$txt['notifications_topics'] = 'Current Topic Notifications';
$txt['notifications_topics_list'] = 'You are being notified of replies to the following topics';
$txt['notifications_topics_none'] = 'You are not currently receiving any notifications from topics.';
$txt['notifications_topics_howto'] = 'To receive email notifications from only specific topics, click the <b>&quot;notify&quot;</b> button while viewing a topic.';
$txt['notifications_boards'] = 'Current Board Notifications';
$txt['notifications_boards_list'] = 'You are being notified of new topics posted in the following boards';
$txt['notifications_boards_none'] = 'You aren\'t receiving notifications on any boards right now.';
$txt['notifications_boards_howto'] = 'To request email notifications from a specific board, click the <b>&quot;notify&quot;</b> button from the index (topic listing) of that board.';
$txt['notifications_boards_current'] = 'You are receiving notifications on the boards shown in bold.  Use the checkboxes to turn these off or add additional boards to your notification list';
$txt['notifications_boards_update'] = 'Update';
$txt['notifications_update'] = 'Unnotify';

// Help
$txt['maillist_help'] = 'For help in setting up the maillist feature, please visit the maillist section on the <a href="https://github.com/elkarte/Elkarte/wiki/Maillist-Feature">Elkarte Wiki</a>';
$txt['maillist_help_short'] = 'This feature allows users of your forum to reply to the sites email notifications and have those replies post on the forum.  Please visit the Wiki for full instructions';

// Email bounce templates
$txt['ml_bounce_templates_title'] = 'Custom bounce email templates';
$txt['ml_bounce_templates_none'] = 'No custom bounce templates have been created yet';

$txt['ml_bounce_templates_time'] = 'Time Created';
$txt['ml_bounce_templates_name'] = 'Template';
$txt['ml_bounce_templates_creator'] = 'Created By';
$txt['ml_bounce_template_add'] = 'Add Template';
$txt['ml_bounce_template_modify'] = 'Edit Template';
$txt['ml_bounce_template_delete'] = 'Delete Selected';
$txt['ml_bounce_template_delete_confirm'] = 'Are you sure you want to delete the selected templates?';
$txt['ml_bounce_subject'] = 'Notification Subject';
$txt['ml_bounce_body'] = 'Notification Message';

$txt['ml_bounce_template_desc'] = 'Use this page to fill in the details of the template. Note that the subject for the email is not part of the template.';
$txt['ml_bounce_template_title'] = 'Template Title';
$txt['ml_bounce_template_title_desc'] = 'A name for use in the template selection list';
$txt['ml_bounce_template_body'] = 'Template Content';
$txt['ml_bounce_template_body_desc'] = 'The content of the bounced message. Note that you can use the following shortcuts in this template.<ul style="margin-top: 0px;"><li>{MEMBER} - Member Name.</li><li>{FORUMNAME} - Forum Name.</li><li>{FORUMNAMESHORT} - Short name for the site.</li><li>{ERROR} - The error that the email generated</li><li>{SUBJECT} - The subject of the email that failed</li><li>{SCRIPTURL} - Web address of the forum.</li><li>{EMAILREGARDS} - Maillist email sign-off.</li><li>{REGARDS} - Standard forum sign-off.</li></ul>';
$txt['ml_bounce_template_personal'] = 'Personal Template';
$txt['ml_bounce_template_personal_desc'] = 'If you select this option only you will be able to see, edit and use this template. If not selected all moderators will be able to use this template.';
$txt['ml_bounce_template_error_no_title'] = 'You must set a descriptive title.';
$txt['ml_bounce_template_error_no_body'] = 'You must set a email template body.';

$txt['ml_bounce'] = 'Email Templates';
$txt['ml_bounce_description'] = 'From this section you can add and modify the email bounce templates used when rejecting a post by email.';
$txt['ml_bounce_title'] = 'Bounce';
$txt['ml_bounce_subject'] = 'Your email could not be posted';
$txt['ml_bounce_body'] = 'Hi. This is the post-by-email program at {FORUMNAMESHORT}' . "\n\n" . 'I\'m afraid I wasn\'t able to deliver and/or post your message with the title of: {SUBJECT}.' . "\n\n" . 'The error I received while trying was: {ERROR}' . "\n\n" . 'This is a permanent error; I\'ve given up. Sorry it didn\'t work out.' . "\n\n" . '{EMAILREGARDS}';
$txt['ml_inform_title'] = 'Notify';
$txt['ml_inform_subject'] = 'There was a problem with your email';
$txt['ml_inform_body'] = '{MEMBER},' . "\n\n" . 'The email that you sent to {FORUMNAMESHORT} generated an error which caused delays in its posting.  The error was: {ERROR}' . "\n\n" . 'To prevent future delays in posting you should fix this error.' . "\n\n" . '{EMAILREGARDS}';
$txt['ml_bounce_template_body_default'] = 'Hi. This is the post-by-email program at {FORUMNAMESHORT}' . "\n\n" . 'I\'m afraid I wasn\'t able to deliver and/or post your message with the title of: {SUBJECT}.' . "\n\n" . 'The error I received while trying was: {ERROR}' . "\n\n" . 'This is a permanent error; I\'ve given up. Sorry it didn\'t work out.' . "\n\n" . '{EMAILREGARDS}';
$txt['ml_bounce_template_subject_default'] = 'There was a error processing your email';
