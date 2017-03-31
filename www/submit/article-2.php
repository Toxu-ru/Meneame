<?php
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//      http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called 'COPYING'.

defined('mnminclude') or die();

if (empty($link->id)) {
    returnToStep(1);
}

if ($_POST) {
    require __DIR__.'/article-2-post.php';
}

$globals['extra_vendor_js'][] = 'medium-editor/js/medium-editor.min.js';
$globals['extra_vendor_css'][] = 'medium-editor/css/medium-editor.min.css';
$globals['extra_vendor_css'][] = 'medium-editor/css/themes/default.min.css';

do_header(_('Enviar un artículo') . ' 2/3', _('Enviar un artículo'));

$link->is_new = $link->status === 'discard';

$link->discarded = $link->is_discarded();
$link->status_text = $link->get_status_text();
$link->is_sub_owner = SitesMgr::is_owner();

$link->change_status = !$link->is_new
    && ($link->votes > 0 && ($link->status !== 'published' || $current_user->user_level === 'god' || $link->is_sub_owner)
    && ((!$link->discarded && $current_user->user_id == $link->author) || $current_user->admin || $link->is_sub_owner));

if (mb_strlen($link->url_description) > 40) {
    $link->content = $link->url_description;
}

$link->chars_left = $site_properties['intro_max_len'] - mb_strlen(html_entity_decode($link->content, ENT_COMPAT, 'UTF-8'), 'UTF-8');

Haanga::Load('story/submit/article-2.html', array(
    'site_properties' => $site_properties,
    'link' => $link,
    'error' => $error,
    'warning' => $warning,
));
