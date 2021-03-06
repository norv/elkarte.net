<?php
// Version: 1.0; MaillistTemplates

// Do not translate anything that is between {}, they are used as replacement variables and MUST remain exactly how they are.
// 		Additionally do not translate the @additioinal_params: line or the variable names in the lines that follow it.  You may
//		translate the description of the variable.
//		Do not translate @description:, however you may translate the rest of that line.

/*
	@additional_params: pbe_notify_boards_once_body
		FORUMURL: The url to the forum
		FORUMNAMESHORT: Short or nickname for the forum
		BOARDNAME: Name of the board the post was made in
		TOPICSUBJECT: The subject of the topic causing the notification
		TOPICLINK: A link to the topic.
		MESSAGE: This is the body of the message.
		SIGNATURE: The signature of the member who made the post
		UNSUBSCRIBELINK: Link to unsubscribe from notifications.
		EMAILREGARDS: The site name signature
	@description: A memeber wants to be notified of new topics on a board they are watching
*/
$txt['pbe_notify_boards_once_body_subject'] = '[{FORUMNAMESHORT}] {TOPICSUBJECT}';
$txt['pbe_notify_boards_once_body_body'] = 'A new topic, \'{TOPICSUBJECT}\', has been started in \'{BOARDNAME}\'.

{MESSAGE}

{SIGNATURE}


------------------------------------
Posting Information: More topics may be posted, but you won\'t receive more email notifications (on this topic) until you return to the board and read some of them.

{FORUMNAMESHORT} Links:

<*> To visit {FORUMNAMESHORT} on the web, go to:
    {FORUMURL}

<*> You can see this message with attachments and/or images (if any) by using this link:
    {TOPICLINK}

<*> Unsubscribe to this by using this link:
    {UNSUBSCRIBELINK}

{EMAILREGARDS}';

/*
	@additional_params: pbbe_notify_boards_once
		TOPICSUBJECT: The subject of the topic causing the notification
		TOPICLINK: A link to the topic.
		FORUMNAMESHORT: Short or nickname for the forum
		UNSUBSCRIBELINK: Link to unsubscribe from notifications.
		BOARDNAME: Name of the board the post was made in
		EMAILREGARDS: The site name signature
	@description: A member wants to be notified of the first new post in a topic on a board they have subscribed to
*/
$txt['pbe_notify_boards_once_subject'] = '[{FORUMNAMESHORT}] {TOPICSUBJECT}';
$txt['pbe_notify_boards_once_body'] ='A new topic, \'{TOPICSUBJECT}\', has been started in \'{BOARDNAME}\'.

You can see it at
    {TOPICLINK}


------------------------------------
Posting Information: More topics may be posted, but you won\'t receive more email notifications (on this topic) until you return to the board and read some of them.

{FORUMNAMESHORT} Links:

<*> To visit {FORUMNAMESHORT} on the web, go to:
    {FORUMURL}

<*> Unsubscribe to this by using this link:
    {UNSUBSCRIBELINK}

{EMAILREGARDS}';

/*
	@additional_params: pbe_notify_boards_body
		TOPICSUBJECT: The subject of the topic causing the notification
		TOPICLINK: A link to the topic.
		MESSAGE: This is the body of the message.
		UNSUBSCRIBELINK: Link to unsubscribe from notifications.
		FORUMURL: The url to the forum
		FORUMNAMESHORT: Short or nickname for the forum
		BOARDNAME: Name of the board the post was made in
		SIGNATURE: The signature of the member who made the post
		EMAILREGARDS: The site name signature
	@description: A new topic has been started in a subscribed board, includes the topic body
*/
$txt['pbe_notify_boards_body_subject'] = '[{FORUMNAMESHORT}] {TOPICSUBJECT}';
$txt['pbe_notify_boards_body_body'] = '
{MESSAGE}

{SIGNATURE}


------------------------------------
Posting Information: {POSTERNAME} started the topic \'{TOPICSUBJECT}\' on the \'{BOARDNAME}\' Board.

{FORUMNAMESHORT} Links:

<*> To visit {FORUMNAMESHORT} on the web, go to:
    {FORUMURL}

<*> You can see this message with attachments and/or images (if any) by using this link:
    {TOPICLINK}

<*> You can go to your first unread message in the thread by using this link:
    {TOPICLINKNEW}

<*> Unsubscribe to this by using this link:
    {UNSUBSCRIBELINK}

{EMAILREGARDS}';

/*
	@additional_params: pbe_notify_boards
		TOPICSUBJECT: The subject of the topic causing the notification
		TOPICLINK: A link to the topic.
		UNSUBSCRIBELINK: Link to unsubscribe from notifications.
		FORUMURL: The url to the forum
		FORUMNAMESHORT: Short or nickname for the forum
		BOARDNAME: Name of the board the post was made in
		EMAILREGARDS: The site name signature
	@description: Notification only of a new topic has been started in a subscribed board
*/
$txt['pbe_notify_boards_subject'] = '[{FORUMNAMESHORT}] {TOPICSUBJECT}';
$txt['pbe_notify_boards_body'] = 'A new topic, \'{TOPICSUBJECT}\', has been started in \'{BOARDNAME}\'.

You can see it at:
    {TOPICLINK}

------------------------------------
{FORUMNAMESHORT} Links:

<*> To visit {FORUMNAMESHORT} on the web, go to:
    {FORUMURL}

<*> You can go to your first unread message in the thread by using this link:
    {TOPICLINKNEW}

<*> Unsubscribe to this by using this link:
    {UNSUBSCRIBELINK}

{EMAILREGARDS}';

/*
	@additional_params: pbe_notification_reply_body
		TOPICSUBJECT: The subject of the topic causing the notification
		TOPICLINK: A link to the topic.
		MESSAGE: This is the body of the message.
		UNSUBSCRIBELINK: Link to unsubscribe from notifications.
		FORUMURL: The url to the forum
		FORUMNAMESHORT: Short or nickname for the forum
		BOARDNAME: Name of the board the post was made in
		SIGNATURE: The signature of the member who made the post
		EMAILREGARDS: The site name signature
	@description: A reply has been made to a topic in a subscribed board, includes the reply body
*/
$txt['pbe_notification_reply_body_subject'] = 'Re: [{FORUMNAMESHORT}] {TOPICSUBJECT}';
$txt['pbe_notification_reply_body_body'] = '
{MESSAGE}

{SIGNATURE}


------------------------------------
Posting Information: {POSTERNAME} replied to the topic \'{TOPICSUBJECT}\' on the \'{BOARDNAME}\' Board.

{FORUMNAMESHORT} Links:

<*> To visit {FORUMNAMESHORT} on the web, go to:
    {FORUMURL}

<*> You can see this message with attachments and/or images (if any) by using this link:
    {TOPICLINK}

<*> You can go to your first unread message in the thread by using this link:
    {TOPICLINKNEW}

<*> Unsubscribe to this by using this link:
    {UNSUBSCRIBELINK}

{EMAILREGARDS}';

/*
	@additional_params: pbe_notification_reply_once
		TOPICSUBJECT: The subject of the topic causing the notification
		TOPICLINK: A link to the topic.
		UNSUBSCRIBELINK: Link to unsubscribe from notifications.
		FORUMURL: The url to the forum
		FORUMNAMESHORT: Short or nickname for the forum
		EMAILREGARDS: The site name signature
	@description: A reply has been made to topic in a subscribed board, notification without body
*/
$txt['pbe_notification_reply_once_subject'] = 'Re: [{FORUMNAMESHORT}] {TOPICSUBJECT}';
$txt['pbe_notification_reply_once_body'] = '{POSTERNAME} replied to a topic you are watching

View the reply with attachments and/or images (if any) at:
    {TOPICLINK}

------------------------------------
Posting Information: More replies may be posted, but you won\'t receive any more notifications until you read the topic.

{FORUMNAMESHORT} Links:

<*> To visit {FORUMNAMESHORT} on the web, go to:
    {FORUMURL}

<*> You can go to your first unread message in the thread by using this link:
    {TOPICLINKNEW}

<*> Unsubscribe to this by using this link:
    {UNSUBSCRIBELINK}

{EMAILREGARDS}';

/*
	@additional_params: pbe_notification_reply_body_once
		TOPICSUBJECT: The subject of the topic causing the notification
		TOPICLINK: A link to the topic.
		MESSAGE: This is the body of the message.
		UNSUBSCRIBELINK: Link to unsubscribe from notifications.
		FORUMURL: The url to the forum
		FORUMNAMESHORT: Short or nickname for the forum
		BOARDNAME: Name of the board the post was made in
		SIGNATURE: The signature of the member who made the post
		EMAILREGARDS: The site name signature
	@description: Full body email notifcation for the first new reply in a topic
*/
$txt['pbe_notification_reply_body_once_subject'] = 'Re: [{FORUMNAMESHORT}] {TOPICSUBJECT}';
$txt['pbe_notification_reply_body_once_body'] = '
{MESSAGE}

{SIGNATURE}


------------------------------------
Posting Information: {POSTERNAME} replied to the topic \'{TOPICSUBJECT}\' on the \'{BOARDNAME}\' Board.
More replies may be posted, but you won\'t receive any more notifications until you read the topic.

{FORUMNAMESHORT} Links:

<*> To visit {FORUMNAMESHORT} on the web, go to:
    {FORUMURL}

<*> You can see this message with attachments and/or images (if any) by using this link:
    {TOPICLINK}

<*> You can go to your first unread message in the thread by using this link:
    {TOPICLINKNEW}

<*> Unsubscribe to this by using this link:
    {UNSUBSCRIBELINK}

{EMAILREGARDS}';

/*
	@additional_params: pbe_new_pm_body
		SUBJECT: The personal message subject.
		SENDER:  The user name for the member sending the personal message.
		MESSAGE:  The text of the personal message.
		REPLYLINK:  The link to directly access the reply page.
		FORUMNAMESHORT: Short or nickname for the forum
	@description: A notification email sent to the receivers of a personal message
*/
$txt['pbe_new_pm_body_subject'] = 'New Personal Message: {SUBJECT}';
$txt['pbe_new_pm_body_body'] = 'You have received a personal message from {SENDER} on {FORUMNAMESHORT}
The message they sent you is:

{MESSAGE}

------------------------------------
{FORUMNAMESHORT} Links:

<*> To visit {FORUMNAMESHORT} on the web, go to:
    {FORUMURL}

<*> Reply to this Personal Message here::
    {REPLYLINK}

{EMAILREGARDS}';