<?php
$gCms = cmsms();
$config = $gCms->GetConfig();
$user_name = $_SESSION['login_user_username'];
$this->smarty->assign('secret_key',$this->GetPreference('secret_key'));	
$this->smarty->assign('user',$user_name);	
$this->smarty->assign('pass',"*******");	
$this->smarty->assign('url',$config['root_url']."/");	
echo $this->ProcessTemplate('admin.tpl');
?>