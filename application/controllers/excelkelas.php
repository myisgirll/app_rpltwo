<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class excelkelas extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library(array('excel','session'));
	}

	public function index()
	{
		$this->load->model('ImportModelKelas');
		$data = array(
			'list_data'	=> $this->ImportModelKelas->getData()
		);
		$this->load->view('import_excel.php',$data);
	}

	public function import_excel(){
		if (isset($_FILES["fileExcel"]["name"])) {
			$path = $_FILES["fileExcel"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();	
				for($row=2; $row<=$highestRow; $row++)
				{
					$nama_kelas = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$tingkat_kelas= $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$jurusan = $worksheet->getCellByColumnAndRow(3, $row)->getValue();	
                    $temp_data[] = array(
						'nama_kelas'	=> $nama_kelas,
						'tingkat_kelas'	=> $tingkat_kelas,
						'jurusan'	=> $jurusan,
		
					); 	
				}
			}
			$this->load->model('ImportModelKelas');
			$insert = $this->ImportModelKelas->insert($temp_data);
			if($insert){
				$this->session->set_flashdata('status', '<span class="glyphicon glyphicon-ok"></span> Data Berhasil di Import ke Database');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('status', '<span class="glyphicon glyphicon-remove"></span> Terjadi Kesalahan');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			echo "Tidak ada file yang masuk";
		}
	}

}