<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('UserModel');
		$this->load->model('UpdateAkun');
		$this->load->model('ToolkitModel');
		$this->load->helper('url');
	}

	public function tersedia()
	{

		$where_user = array(
			'nim' => $_SESSION['nim']
		);
		$data['users'] = $this->UserModel->cek_login("users", $where_user)->result();
		$where_toolkit = array(
			'status' => '1'
		);
		$data['toolkit'] = $this->Toolkitmodel->toolkit_tersedia("toolkit", $where_toolkit)->result();
		// $data['toolkit'] = $this->Data_Toolkit->retrieve_where("toolkit", $where_toolkit)->result();
		$this->load->view('admin/sidebar', $data);
		$this->load->view('toolkit_tersediaa');
		$this->load->view('admin/footer');
	}
	public function dipinjam()
	{

		$where_user = array(
			'nim' => $_SESSION['nim']
		);
		$data['users'] = $this->UserModel->cek_login("users", $where_user)->result();
		$where_toolkit = array(
			'status' => '2'
		);
		$data['toolkit'] = $this->Toolkitmodel->toolkit_tersedia("toolkit", $where_toolkit)->result();

		$this->load->view('admin/sidebar', $data);
		$this->load->view('toolkit_dipinjam', $data);
		$this->load->view('admin/footer');
	}
	public function edit()
	{
		$nim = $this->uri->segment(3, 0);
		$where = array('nim' => $_SESSION['nim']);
		$where_user = array(
			'nim' => $this->input->post('nim'),
			'nama' => $this->input->post('nama'),
			'alamat' => $this->input->post('alamat'),
			'nomor_hp' => $this->input->post('nomor_hp'),
			'access' => $this->input->post('access'),
		);
		if ($this->input->post('password')) $where_user['password'] = md5($this->input->post('password'));
		$this->UpdateAkun->update_user('users', $where_user, $nim);
		$data['users'] = $this->UserModel->cek_login("users", $where)->result();
		$data['users_all'] = $this->UserModel->retrieve_user("users")->result();
		$this->load->view('admin/sidebar', $data);
		$this->load->view('kelola_akun_admin', $data);
		$this->load->view('admin/footer');
	}

	public function edit_toolkit()
	{

		$where = array('nim' => $_SESSION['nim']);
		$data['users'] = $this->UserModel->cek_login("users", $where)->result();
		$id = $this->uri->segment(3, 0);
		$data = array(

			'pemegang_id' => $this->input->post('pemegang_id'),
			'isi_toolkit' => $this->input->post('isi_toolkit'),

		);

		$this->ToolkitModel->toolkit_update("toolkit", $data, $id);


		redirect('Admin/update_toolkit');
	}
	public function update_toolkit()
	{
		$where_toolkit = array();
		$where = array(
			'nim' => $_SESSION['nim']
		);
		$data['users'] = $this->UserModel->cek_login("users", $where)->result();

		$data['toolkit'] = $this->ToolkitModel->toolkit_tersedia("toolkit", $where_toolkit)->result();





		$this->load->view('admin/header');
		$this->load->view('admin/sidebar', $data);
		$this->load->view('kelola_toolkit_admin', $data);
		$this->load->view('admin/footer');
	}

	public function update_admin()
	{
		$where = array(
			'nim' => $_SESSION['nim']
		);

		$data['users'] = $this->UserModel->cek_login("users", $where)->result();
		$data['users_all'] = $this->UserModel->retrieve_user("users")->result();
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar', $data);
		$this->load->view('kelola_akun_admin', $data,);
		$this->load->view('admin/footer');
	}
	public function delet_akun()
	{
		$nim = $this->uri->segment(3, 0);
		$this->UpdateAkun->delete('users', $nim);
		$this->update_admin();
	}
	public function delet_toolkit()
	{
		$id = $this->uri->segment(3, 0);
		$this->ToolkitModel->delete_toolkit('toolkit', $id);
		$this->update_toolkit();
		redirect('Admin/update_toolkit');
	}


	public function setuju()
	{
		$nim = $this->uri->segment(3, 0);
		$where = array(
			'approval' => 1
		);
		$this->UpdateAkun->update_user("users", $where, $nim);
		redirect('Akun/update_admin');
	}


	public function riwayat_peminjaman()
	{



		$where = array(
			'nim' => $_SESSION['nim']
		);
		$data['user'] = $this->UserModel->cek_login("user", $where)->result();

		$data['riwayat'] = $this->ToolkitModel->riwayat_peminjaman_all("peminjaman")->result();



		$this->load->view('admin/header');
		$this->load->view('admin/sidebar', $data);
		$this->load->view('riwayat_peminjaman', $data,);
		$this->load->view('admin/footer');
	}
}
