<?php
require_once('chat_init.php');
include 'chat_functions.php';


$settings = new chatSettings();
$app = new chatApp();

if (isset($_POST['language1'], $_POST['language2'])){
    $language_old = $_POST['language2'];
    $language_new = $_POST['language1'];
    $change_lang = $app->updateLanguage($language_old, $language_new);
    echo $change_lang;
}

if (isset($_POST['languageNew'])){
    $languageNew = $_POST['languageNew'];
    $add_lang = $app->setLanguages($languageNew, 0);
    echo $add_lang;
}

if (isset($_POST['languageDel'])){
    $languageDel = $_POST['languageDel'];
    $del_lang = $app->removeLanguage($languageDel);
    echo $del_lang;
}
?>
