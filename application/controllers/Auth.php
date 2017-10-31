<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{		
		$this->load->helper(array('form', 'url'));
		$this->load->library('azureauth');
		$AzureAuth = new AzureAuth;
		$provider = $AzureAuth->getProvider();
		$header['loggedin'] = $AzureAuth->logged_in($provider);
		$results = "";
		$data['results'] = $results;
		$this->load->view('tpl/header',$header);	
		$this->load->view('auth_index',$data);
		$this->load->view('tpl/footer');
	}
	public function profile()
	{		
		$this->load->helper(array('form', 'url'));
		$this->load->library('azureauth');
		$AzureAuth = new AzureAuth;
		$provider = $AzureAuth->getProvider();
		$header['loggedin'] = $AzureAuth->logged_in($provider);
		$header['profile'] = $AzureAuth->getProfile($provider);
		$results = "";
		$data['results'] = $results;
		$this->load->view('tpl/header',$header);	
		$this->load->view('auth_profile',$data);
		$this->load->view('tpl/footer');
	}
	public function redirect()
	{		
		$this->load->helper(array('form', 'url'));
		$this->load->library('azureauth');
		$AzureAuth = new AzureAuth;
		$provider = $AzureAuth->getProvider();
		if (!isset($_GET['code'])) {
			$AzureAuth->getAuthUrl($provider);
		} else {
			$AzureAuth->setCode($provider);
		}
	}
	public function logout()
	{		
		$this->load->helper(array('form', 'url'));
		$this->load->library('azureauth');
		$AzureAuth = new AzureAuth;
		$provider = $AzureAuth->getProvider();
		$AzureAuth->logout($provider);
	}
	public function login()
	{		
		$this->redirect();
	}
		
}
