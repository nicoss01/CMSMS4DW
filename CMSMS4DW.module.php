<?php
//http://www.devictio.fr/index.php?mact=PDFGenerator,cntnt01,pdf,0&cntnt01url=aHR0cDovL3d3dy5kZXZpY3Rpby5mci9tdWx0aW1lZGlhL2Zvcm1hdGlvbi1hY3JvYmF0LXByby5waHA%2Fc2hvd3RlbXBsYXRlPWZhbHNl&cntnt01returnid=47&page=47&showtemplate=false
class CMSMS4DW extends CMSModule

{

	function GetName()

	{

		return 'CMSMS4DW';

	}

	function GetFriendlyName()

	{

		return "CMSMS4DW";

	}

	function GetVersion()

	{

		return '1.1';

	}

	function MinimumCMSVersion()

	{

		return '1.7';

	}

	function GetHelp()

	{

		return $this->Lang('help');

	}

	function GetAuthor()

	{

		return 'Nicolas Grillet';

	}

	function GetAuthorEmail()

	{

		return 'n.grillet01@gmail.com';

	}

	function GetChangeLog()

	{

		return $this->Lang('changelog');

	}

	function IsPluginModule()

	{

		return true;

	}

	function HasAdmin()

	{

		return true;

	}

	function GetAdminSection()

	{

		return 'extensions';

	}

	function GetAdminDescription()

	{

		return $this->Lang('moddescription');

	}

	function VisibleToAdminUser() 

	{

		return ($this->CheckPermission('View CMSMS4DW') || $this->CheckPermission('Administrate CMSMS4DW'));

	}	

	function GetDependencies()

	{

		return array();

	}

	function Install()

	{

		$this->SetPreference('secret_key',md5(uniqid()));

		$this->CreatePermission('CMSMS4DW Admin', 'Manage CMSMS4DW');
		$this->CreatePermission('CMSMS4DW View', 'View CMSMS4DW');

	}

	function SetParameters() 

	{

		$this->RestrictUnknownParams();

		$this->RegisterModulePlugin();

		$this->CreateParameter('secret_key', "", $this->Lang('secret_key'));
		$this->CreateParameter('user', "", $this->Lang('secret_key'));
		$this->CreateParameter('pass', "", $this->Lang('secret_key'));
		$this->CreateParameter('secret', "", $this->Lang('secret_key'));

		$this->SetParameterType('secret_key',CLEAN_STRING);
		$this->SetParameterType('user',CLEAN_STRING);
		$this->SetParameterType('pass',CLEAN_STRING);
		$this->SetParameterType('secret',CLEAN_STRING);

	}

	function InstallPostMessage()

	{

		return $this->Lang('postinstall');

	}

	function Uninstall()

	{

		$this->RemovePreference('secret_key');

		$this->RemovePermission('CMSMS4DW Admin');
		$this->RemovePermission('CMSMS4DW View');

	}

	function UninstallPreMessage()

	{

		return $this->Lang('uninstall_confirm');

	}

	function UninstallPostMessage()

	{

		return $this->Lang('postuninstall');

	}

}

?>