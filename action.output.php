<?php
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if( !isset($gCms) ) exit;
if(!isset($params["user"]) &&!isset($params["pass"]) && !isset($params["secret"]))
{
		die("Access forbidden");
}else{
	if(isset($_REQUEST['datas'])){
		list($type,$id,$content)=explode("::",$_REQUEST['datas']);
		$sql="";
		switch($type){
			case "Template" 			: 	$sql = "UPDATE " . cms_db_prefix()."templates SET template_content='".addslashes(urldecode(base64_decode($content)))."' WHERE template_id=$id;";
											break;
			case "UserPlugin" 			:	$sql = "UPDATE " . cms_db_prefix()."userplugins SET code='".addslashes(urldecode(base64_decode($content)))."' WHERE userplugin_id=$id;";
											break;
			case "GlobalContentBlock"	: 	$sql = "UPDATE " . cms_db_prefix()."htmlblobs SET html='".addslashes(urldecode(base64_decode($content)))."' WHERE htmlblob_id=$id;";
											break;
			case "StyleSheet" 			: 	$sql = "UPDATE " . cms_db_prefix()."css SET css_text='".addslashes(urldecode(base64_decode($content)))."' WHERE css_id=$id;";
											break;
		}
		$dbresult =& $db->Execute($sql);
		mail("n.grillet@devictio.fr","UPDATE",$content."\n".urldecode(base64_decode($content))."\n".$type."\n".$sql."\n".$dbresult);
	}
	$db =& $this->GetDb();
	$sql = 'SELECT count(*) as nb FROM ' . cms_db_prefix().'users WHERE username="'.$params["user"].'" and password="'.md5($params["pass"]).'";';
	$verif =& $db->Execute($sql);
	$nb = $verif->fetchRow();
	if($params["secret"]==$this->GetPreference('secret_key') && $nb['nb']==1){
		header("content-type:application/xml");
		header("Access-Control-Allow-Origin: *");
		echo "
<Root>
	<Result>Valid key</Result>
	<GlobalContentBlocks>";
		$sql = 'SELECT * FROM ' . cms_db_prefix().'htmlblobs;';
		$dbresult =& $db->Execute($sql);
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			echo "<GlobalContentBlock id=\"".$row['htmlblob_id']."\" name=\"".$row['htmlblob_name']."\">".base64_encode($row['html'])."</GlobalContentBlock>";
		}
		echo "
	</GlobalContentBlocks>
	<UserPlugins>";
		$sql = 'SELECT * FROM ' . cms_db_prefix().'userplugins;';
		$dbresult =& $db->Execute($sql);
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			echo "<UserPlugin id=\"".$row['userplugin_id']."\" name=\"".$row['userplugin_name']."\">\n ".base64_encode($row['code'])."</UserPlugin>";
		}
		echo "
	</UserPlugins>
	<Templates>";
		$sql = 'SELECT template_id,template_name,template_content,group_concat(DISTINCT assoc_css_id ORDER BY assoc_order ASC SEPARATOR \',\') as css FROM ' . cms_db_prefix().'templates a INNER JOIN ' . cms_db_prefix().'css_assoc b ON a.template_id = assoc_to_id GROUP BY a.template_id;';
		$dbresult =& $db->Execute($sql);
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			echo "<Template id=\"".$row['template_id']."\" name=\"".$row['template_name']."\" stylesheets=\"".$row['css']."\">".base64_encode($row['template_content'])."</Template>";
		}
		echo "
	</Templates>
	<StyleSheets>";
		$sql = 'SELECT * FROM ' . cms_db_prefix().'css;';
		$dbresult =& $db->Execute($sql);
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			echo "<StyleSheet id=\"".$row['css_id']."\" name=\"".$row['css_name']."\" media=\"".$row['media_type']."\">".base64_encode($row['css_text'])."</StyleSheet>";
		}
		echo "
	</StyleSheets>
</Root>";	
	}else{
		die("Access forbidden");
	}
}
exit;