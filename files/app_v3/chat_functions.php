<?php

class chatDB {
    
    public $db;
    
    //database connection
    public function __construct(){
        include 'chat_init.php';
        $mysqli_conn = new mysqli($link_server, $link_user, $link_passwd, $link_db);
        $this->db = $mysqli_conn;

    }
    
    public function __destruct(){
    }
    
    public function connection(){
        return $this->db;
    }
}
    
    

class chatApp {

    public $dblink;

    public function __construct(){
        $db = new chatDB();
        $link = $db->connection();
        $this->dblink = $link;
    }
    
    public function __destruct(){
    }

    //LANGUAGE
    // get - return array (value, state)
    // set - return true/false
    // remove - return true/false

    public function getLanguages() {
        $output  = $this->getPresenceAll();
        
        return $output;
    }
    
    public function setLanguages($language, $state) { //$state = 0 or 1
        $link = $this->dblink;
        if ($setlanguage = $link->prepare("INSERT INTO `presence`(`language`, `presence`) VALUES (?,?)")) {
            $setlanguage->bind_param("si", $language, $state);
            $setlanguage->execute();
            $setlanguage->close();
            $output = true;
        } else {
            $output = false;
        }
        
        return $output;
    }
    
    
    public function updateLanguage($language_old, $language_new) {
        $link = $this->dblink;
        if ($changelanguage = $link->prepare("UPDATE `presence` SET `language`=? WHERE `language`=?")) {
            $changelanguage->bind_param("ss", $language_new, $language_old);
            $changelanguage->execute();
            $changelanguage->close();
            $output = true;
        } else {
            $output = false;
        }
        
        return $output;
    }
    
    
    public function removeLanguage($language, $all = false){
        $link = $this->dblink;
        if ($all === true) {
            if ($del = $link->prepare("DELETE FROM `presence`")) {
                $del->execute();
                $del->close();
                $output = true;
            } else {
                $output = false;
            }
        } else {
            if ($del = $link->prepare("DELETE FROM `presence` WHERE `language` = ?")) {
                $del->bind_param("s", $language);
                $del->execute();
                $del->close();
                $output = true;
            } else {
                $output = false;
            }
        }
        
        
        return $output;
    }
    


    //PRESENCE
    // get - return 0 or 1, return array
    // set - return true/false
    
    public function getPresenceAll() {
        $link = $this->dblink;
        if ($presence = $link->prepare("SELECT `language`, `presence` FROM `presence`")) {
            $presence->execute();
            $presence->bind_result($language, $state);
            $output = [];
            while ($presence->fetch()){
                $output[$language] = $state;
            }
            $presence->close();
        
        } else {
            $output = "Error retrieving array.";
        }
        
        return $output;
    }
    
    public function getPresence($language) {
        $link = $this->dblink;
        if ($presence = $link->prepare("SELECT `presence` FROM `presence` WHERE `language` = ?")) {
            $presence->bind_param("s", $language);
            $presence->execute();
            $presence->bind_result($state);
            $presence->fetch();
            $presence->close();
            $output = $state;
        } else {
            $output = "Error retrieving array.";
        }
        
        return $output;
    }


    public function setPresence($language, $state) {
        $link = $this->dblink;
        if ($presence = $link->prepare("UPDATE `presence` SET `presence` = ? WHERE `language` = ?")) {
            $presence->bind_param("is", $state, $language);
            $presence->execute();
            $presence->close();
            $output = true;
        } else {
            $output = false;
        }
        
        return $output;
    }
    
    public function togglePresence($language) {
        $link = $this->dblink;
        if ($presence = $link->prepare("UPDATE `presence` SET `presence` = !`presence` WHERE `language` = ?")) {
            $presence->bind_param("s", $language);
            $presence->execute();
            $presence->close();
            $output = true;
        } else {
            $output = false;
        }
        
        return $output;
    }
    
    //MESSAGES
    // get - return array
    // set - return true/false
    // delete - return true/false
    
    public function getMessageHistory(){
        $link = $this->dblink;
        $output = [];
        $message = $link->prepare("SELECT `sender`, `message`, DATE_FORMAT(`time`, '%Y-%m-%d %H:%i') AS `formatted_date` FROM `messages`");
        $message->execute();
        $message->bind_result($sender, $texta, $datea);
        $message->store_result();
        while ($message->fetch()) {
            $output[] = array($sender, $texta, $datea);
        }
        
        $message->free_result();
        $message->close();
        
        return $output;
    }
    
    public function setMessage($user_name, $user_message){
        $link = $this->dblink;
        if ($message = $link->prepare("INSERT INTO `messages`(`time`, `sender`, `message`) VALUES (NOW(),?,?)")) {
            $message->bind_param("ss", $user_name, $user_message);
            $message->execute();
            $message->close();
            $output = true;
        } else {
            $output = false;
        }
        
        return $output;
    }
    
    public function clearMessageHistory(){
        $link = $this->dblink;
        if ($message = $link->prepare("DELETE FROM `messages`")) {
            $message->execute();
            $message->close();
            $output = true;
        } else {
            $output = false;
        }
        
        return $output;
    }
}



class chatSettings {
    
    //BANNER
    // get - return string
    // set - return true/false

    public function __construct(){
        $db = new chatDB();
        $link = $db->connection();
        $this->dblink = $link;
    }
    
    public function __destruct(){
    }
    
    public function getSetting($banner){
        $link = $this->dblink;
        if ($setting = $link->prepare("SELECT `value` FROM `settings` WHERE `entity` = ?")){
            $setting->bind_param("s", $banner);
            $setting->execute();
            $setting->bind_result($output);
            $setting->fetch();
            }
        $setting->close();
        
        if (empty($output)) {
            $output = "PLEASE SET";
        }
        
        return $output;
        
    }
    
    
    public function setSetting($banner, $banner_value){
        $link = $this->dblink;
        $setting = $link->prepare("UPDATE `settings` SET `value` = ? WHERE `entity` = ?");
        if ($setting->bind_param("ss", $banner_value, $banner)) {
            $setting->execute();
            $result = true;
            } else {
                $result = false;
                }
        $setting->close();
        
        return $result;
        
    }

}



//$test = new chatApp();
//$set = $test->setPresence("Portuguese", 1);
//print_r($set);
//$out = $test->getPresenceAll();
//print_r($out);
//$messgin = $test->setMessage("English Sound", "We are ready.");
//print_r($messgin);
//$messgout = $test->getMessageHistory();
//print_r($messgout);
//$messgdel = $test->clearMessageHistory();
//print_r($messgdel);
//$setlang = $test->setLanguages("Kwanyama", 0);
//print_r($setlang);
//$getlang = $test->getLanguages();
//print_r($getlang);
//$test = new chatSettings();
//$getbanner = $test->getBanner("countdown");
//print_r($getbanner);
//$toggle = $test->togglePresence("English");
//print_r($toggle);
//$update = $test->updateLanguage("English", "Eng");
//print_r($update);
?>
