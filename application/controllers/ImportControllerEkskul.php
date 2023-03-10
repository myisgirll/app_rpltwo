<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImportControllerEkskul extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library(array('excel','session'));
	}

	public function index()
	{
		$this->load->model('ImportModelEkskul');
		$data = array(
			'list_data'	=> $this->ImportModelEkskul->getData()
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
					$kd_ekskul = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$nama_ekskul = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$temp_data[] = array(
						'kd_ekskul'	=> $kd_ekskul,
						'nama_ekskul'	=> $nama_ekskul,
					); 	
				}
			}
			$this->load->model('ImportModelEkskul');
			$insert = $this->ImportModelEkskul->insert($temp_data);
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