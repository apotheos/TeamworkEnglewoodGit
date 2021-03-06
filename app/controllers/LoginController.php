<?php

class LoginController extends BaseController {

    /**
    *
    * Drive the Login Process, online or offline depending on config variable
    *
    */
    public function gitHubLoginPost()
    {
		if(Config::get('oauth.online'))
		{
			//Standard Online Session
			$gitHubLogin = new Login();
			$validUser = $gitHubLogin->processUser();
			if($validUser)
			{
				
				//Begin Session
				$user = $gitHubLogin->getUserName();
				Session::put('uid',$gitHubLogin->getUserName());
				Session::put('tableId', $gitHubLogin->getTableId());
				Session::put('email', $gitHubLogin->getEmail());
				Session::put('token', $gitHubLogin->getToken());
				return Redirect::to(URL::to("/user/$user/projects"));
			}
			else
			{
				$org = Config::get('oauth.organization');
				echo "<script type='text/javascript'>alert('Login Failed: You are Not a Member of $org on GitHub. Please join $org and try again.');</script>";
				return Redirect::to(URL::to("/"));
			}
		}
		else
		{
			//Offline Testing Session
			$user = Config::get('oauth.offlineUserName');
			$userId = Config::get('oauth.offlineTableId');
			$token = Config::get('oauth.offlineToken');
			
			Session::put('uid', $user);
			Session::put('tableId', $userId);
			Session::put('token', $token);
			
			//echo "<script type='text/javascript'>alert('Attempting Route');</script>";
			return Redirect::to(URL::to("/user/$user/projects"));
		}
    }
	
	/**
    *
    * This will logout a user. The session will be flushed of all variables
    * and will then be redirected to the login page. 
    *
    */
	public function logoutPost()
	{
		Session::flush();
		return Redirect::to(URL::to("/"));
	}
}