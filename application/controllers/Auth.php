<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends CI_Controller
 {
 	public function __construct()
 	{
		parent::__construct();
		$this->load->library('form_validation');
	}

	 public function index()
	{

		 	if ($this->session->userdata('email')) {
		 			redirect('user');
		 	}
		 	$this->form_validation->set_rules('email','Email', 'trim|required|valid_email',['required' => 'Email tidak boleh kosong.','valid_email'=> 'Email tidak terdaftar.']);
		 	$this->form_validation->set_rules('password','Password', 'trim|required',['required' => 'Password tidak boleh kosong.']);
		 	if($this->form_validation->run() == false) {
			$data['title'] = 'Halaman Login';
			$this->load->view('templates/auth_header',$data);
			$this->load->view('templates/auth_header');
			$this->load->view('auth/login');
			$this->load->view('templates/auth_footer');
			} else{
			$this->_login();
			}
	}	

	private function _login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$user = $this->db->get_where('user',['email' => $email])->row_array();
			
		//cusr
		if($user) {
		if($user['is_active'] == 1) {
		//cpw
		if(password_verify($password, $user ['password'])) {
		$data = [
		'email' => $user ['email'],
		'role_id' => $user ['role_id']
		];
		$this->session->set_userdata($data);
		if($user ['role_id'] == 1) {
		redirect('admin');

		}else {
		redirect ('user/home');
		}
		} else {
				$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Password salah. </div>');
	 			redirect('auth');
				}

		} else{
				$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Email belum aktif! </div>');
	 			redirect('auth');
				}

		} else {
				$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Email belum terdaftar! </div>');
	 			redirect('auth');
				}
	}

 	public function registration()
 	{
 		if ($this->session->userdata('email')) {
 			redirect('user');
 		}
		 		$this->form_validation->set_rules ('name','Name','required|trim',['required' => 'Nama tidak boleh kosong.']);
		 		$this->form_validation->set_rules('email','Email','required|trim|valid_email|is_unique[user.email]',['required' => 'Email tidak boleh kosong.','is_unique' => 'Email sudah terdaftar.']);
		 		$this->form_validation->set_rules ('password1','Password','required|trim|min_length[6]|matches[password2]',['matches'=> 'Password Tidak Cocok.','min_length' => 'Password Terlalu Pendek.','required' => 'Password tidak boleh kosong.']);
		 		$this->form_validation->set_rules ('password2','Password','required|trim|matches[password1]');

		 		if ( $this->form_validation->run() == false) {
		 		$data['title'] = 'Halaman Pendaftaran';
		 		$this->load->view('templates/auth_header', $data);
		 		$this->load->view('auth/registration');
		 		$this->load->view('templates/auth_footer');
 					} else {
 						$email = $this->input->post('email', true);
 			
 			$data = [
	 			'name' 			=> htmlspecialchars($this->input->post('name',true)),
	 			'email' 		=> htmlspecialchars($email),
	 			'image' 		=> 'default.jpg',
	 			'password' 		=> password_hash ($this->input->post('password1'),PASSWORD_DEFAULT),
	 			'role_id' 		=> 1,
	 			'is_active' 	=> 0,
	 			'date_created' 	=> time()
 					];
//token

 					$token = base64_encode(random_bytes(32));
 					$user_token = [
 						'email' => $email,
 						'token' => $token,
 						'date_created' => time()
 					];
	 		$this->db->insert('user', $data);
	 		$this->db->insert('user_token', $user_token);

	 		$this->_sendEmail($token, 'verify');

	 		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Selamat, tunggu kami akan aktivasi akun anda! </div>');
 			redirect('auth');

 		}
 	}
 	

 	private function _sendEmail($token, $type)
 	{
 	
		$this->load->library('email');
        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.googlemail.com';
        $config['smtp_user'] = 'karmuden2020@gmail.com';
        $config['smtp_pass'] = 'Sayakarmuden';
        $config['smtp_port'] = 465;
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $this->email->initialize($config);
        $this->email->set_newline("\r\n"); 	
		$this->email->from('karmuden2020@gmail.com', 'AKTIVASI EMAIL USER');
		$this->email->to('aktivasi@karmuden.com');


		if ($type == 'verify') {
		$this->email->subject('Pesan Verifikasi');
		$this->email->message('Klik link ini untuk verifikasi : <a href="'. base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Aktivasi</a>');
		} else if($type == 'forgot') {
		$this->email->subject('Reset Password');
		$this->email->message('Klik link ini untuk reset password : <a href="'. base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
		}
		if($this->email->send()) {
		return true;		
		} else {
		echo $this->email->print_debugger();
		die;
		}
	}


public function verify () 
{
	$email = $this->input->get('email');
	$token = $this->input->get('token');

	$user = $this->db->get_where('user', ['email' => $email])->row_array();

	if ($user) { 
		$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

		if($user_token) {

			if(time() - $user_token['date_created'] < (60*60*24)) {
				$this->db->set('is_active', 1);
				$this->db->where('email', $email);
				$this->db->update('user');
				$this->db->delete('user_token', ['email' => $email]);

				$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">'. $email .' Telah diaktifkan! </div>');
		redirect('auth');

			} else {

				$this->db->delete('user', ['email' => $email]);
				$this->db->delete('user_token', ['email' => $email]);

				$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Token kadaluarsa! </div>');
				redirect('auth');

			}


		} else {
			$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Token salah! </div>');
			redirect('auth');

		}


	} else {
		$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Aktivasi gagal, email salah! </div>');
		redirect('auth');

	}

}

 	public function logout()
 	{

 		$this->session->unset_userdata('email');
 		$this->session->unset_userdata('role_id');
 		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Anda telah keluar! </div>');
 		redirect('auth');

 	}


 	public function blocked ()
 	{
 		$this->load->view('auth/blocked');
 	}


 	public function forgotPassword()

 			{
 				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', ['valid_email' => 'Email tidak valid!', 'required' => 'Kolom harus diisi!']);
 				if ($this->form_validation->run() == false) {
 				$data['title'] = 'Lupa Password';
		 		$this->load->view('templates/auth_header',$data);
		 		$this->load->view('templates/auth_header');
		 		$this->load->view('auth/forgot-password');
		 		$this->load->view('templates/auth_footer');
		 	} else {
		 		$email = $this->input->post('email');
		 		$user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();


		 		if($user) {

		 			$token = base64_encode(random_bytes(32));
		 			$user_token = [
		 				'email' => $email,
		 				'token' => $token,
		 				'date_created' => time()
		 			];
		 			$this->db->insert('user_token', $user_token);
		 			$this->_sendEmail($token, 'forgot');

		 			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Cek email anda sekarang!</div>');
 		redirect('auth/forgotpassword');

		 		} else {
		 			$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Email belum terdaftar! atau aktifkan email anda </div>');
 		redirect('auth/forgotpassword');
		 		}

		 	}
		 }

		 //mt rs pss


		 public function resetPassword()
		 {
		 	$email = $this->input->get('email');
		 	$token = $this->input->get('token');
		

		 	$user = $this->db->get_where('user', ['email' => $email])->row_array();

		 	if($user) {
		 		$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

		 		if ($user_token) {
		 			$this->session->set_userdata('reset_email', $email);
		 			$this->changePassword();
		 		} else{

		 			$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Token salah! </div>');
 		redirect('auth');
		 		}

		 	} else {
		 		$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Reset password gagal! </div>');
 		redirect('auth');
		 	}

		 }
 	public function changePassword()
 	{

 				if(!$this->session->userdata('reset_email')){
 					redirect('auth');
 				}

 				$this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[6]|matches[password2]', ['required' => 'Password tidak boleh kosong.', 'min_length' => 'Password terlalu pendek.', 'matches' => 'Password tidak sama.']);

 				$this->form_validation->set_rules('password2', 'Password', 'trim|required|min_length[6]|matches[password1]', ['required' => 'Password tidak boleh kosong.', 'min_length' => 'Password terlalu pendek.', 'matches' => 'Password tidak sama.']);

 				if($this->form_validation->run() == false) {
 				$data['title'] = 'Ganti Password';
		 		$this->load->view('templates/auth_header',$data);
		 		$this->load->view('templates/auth_header');
		 		$this->load->view('auth/change-password');
		 		$this->load->view('templates/auth_footer');
		 	} else {

		 		$password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
		 		$email = $this->session->userdata('reset_email');

		 		$this->db->set('password', $password);
		 		$this->db->where('email', $email);
		 		$this->db->update('user');

		 		$this->session->unset_userdata('reset_email');

		 		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Password berhasil diganti! silahkan login. </div>');
 		redirect('auth');
		 	}

 	}
 }