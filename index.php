<?php

include('header.php');
if ($_POST['count']) {
    $count = $_POST['count'];
}
$count = $xoopsModuleConfig['TopicCount'];
$GLOBALS['xoopsOption']['template_main'] = 'BopComments_index.html';
include(XOOPS_ROOT_PATH . '/header.php');

//テーブル表題の言語情報を取得する
$header['place'] = _BopComments_place;
$header['topic'] = _BopComments_topic;
$header['poster'] = _BopComments_poster;
$header['reply'] = _BopComments_reply;
$header['read'] = _BopComments_read;
$header['date'] = _BopComments_date;

// オプション情報を取得する
$option['micon'] = $xoopsModuleConfig['Micon'];
$option['user'] = $xoopsModuleConfig['User'];
$option['footer'] = $xoopsModuleConfig['footer'];

$data = BopComments_main($count);
switch ($xoopsModuleConfig['Mode']) {
case(1):
    foreach ($data['topic'] as $key => $value) {
        $topic = BopComments_minimum($value, $count);

        $mod[$key]['topics'] = BopComments_finalize($topic, $count, $xoopsModuleConfig['User'], $xoopsModuleConfig['catmax'], $xoopsModuleConfig['topicmax'], $xoopsModuleConfig['timestamp']);

        $mod[$key]['name'] = $data['footer']['name'][$key];

        $mod[$key]['img'] = $data['footer']['img'][$key];
    }

    $option['micon'] = 0;
    $option['footer'] = 0;
    $tempfile = 'db:BopComments_Content_' . $xoopsModuleConfig['Template'] . '.html';

    $template = new XoopsTpl();
    $template->assign('tempfile', $tempfile);
    $template->assign('option', $option);
    $template->assign('header', $header);
    $template->assign('module', $mod);
    $content = $template->fetch('db:BopComments_module.html');
    break;
case(2):
default:
    $topics = BopComments_arrayMarge($data['topic']);
    $topic = BopComments_finalize($topics, $count, $xoopsModuleConfig['User'], $xoopsModuleConfig['catmax'], $xoopsModuleConfig['topicmax'], $xoopsModuleConfig['timestamp']);
    $footer = $data['footer'];
    $footer['option']['row'] = $xoopsModuleConfig['footer_row'];

    $template = new XoopsTpl();
    $template->assign('option', $option);
    $template->assign('header', $header);
    $template->assign('topics', $topic);
    $template->assign('footer', $footer);
    $content = $template->fetch('db:BopComments_Content_' . $xoopsModuleConfig['Template'] . '.html');
}
$xoopsTpl->assign('content', $content);
$xoopsTpl->assign('rsson', $xoopsModuleConfig['rss']);
$xoopsTpl->assign('rss_img', XOOPS_URL . '/modules/BopComments/images/rss.gif');
$xoopsTpl->assign('rss_link', XOOPS_URL . '/modules/BopComments/backend.php');
$xoopsTpl->assign('rss', _BopComments_rss);
$xoopsTpl->assign('footer', _BopComments_footer);

include(XOOPS_ROOT_PATH . '/footer.php');
