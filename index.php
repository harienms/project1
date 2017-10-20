<?php

	//this step turns on the debugger
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);



	//This step will work when the program fails to call a missing class 
	class Manage {
		public static function autoload($class) {

			include $class . '.php';
		}
	}

	spl_autoload_register(array('Manage', 'autoload'));

	// program object main
	$obj = new main();


	class main {

		public function __construct()
		{
		
			$pageRequest = 'homepage';
			//checking for parameters if there are any
			if(isset($_REQUEST['page'])) {
				
				$pageRequest = $_REQUEST['page'];
			}

			 $page = new $pageRequest;


			if($_SERVER['REQUEST_METHOD'] == 'GET') {
				$page->get();
			} else {
				$page->post();
			}

		}

	}

	abstract class page {
		protected $html;

		public function __construct()
		{
			$this->html .= '<html>';
			$this->html .= '<link rel="stylesheet" href="styles.css">';
			$this->html .= '<body>';
		}
		public function __destruct()
		{
			$this->html .= '</body></html>';
			stringFunctions::printThis($this->html);
		}

		public function get() {
			echo 'default get message';
		}

		public function post() {
			print_r($_POST);
		}
	}

	class homepage extends page {

		public function get() {
			$form = '<form action="index.php?page=homepage" method="post" enctype="multipart/form-data">';
			$form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
			$form .= '<input type="submit" value="Upload" name="submit">';
			$form .= '</form> ';
			$this->html .= '<h1>Upload Form</h1>';
			$this->html .= $form;
		}

  

		public function post() {
		$directory_to_upload = "uploads/";
		$file_basename = $directory_to_upload . basename($_FILES["fileToUpload"]["name"]);
		$fileType = pathinfo($file_basename,PATHINFO_EXTENSION);
		$fileName=pathinfo($file_basename,PATHINFO_BASENAME);
		move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $file_basename);
		header("Location: index.php?page=htmlTable&filename=$file_basename");


        }
	}	
 


	class htmlTable extends page {
	    
		    public function get() {

			$filename = $_REQUEST['filename'];
			$file = fopen($filename,"r");
			$table="";
			$x=0;
			$table .='<table border="2">';
				while(! feof($file))
				{
                 $data=fgetcsv($file);
                 $count=count($data);
				 $table .='<tr>';
				      for($x=0;$x<$count;$x++)
					  {
						  $table.='<td>'.$data[$x].'</td>';
					  }
				 $table.='</tr>';
				}
			$table.='</table>';
			fclose($file);
			stringFunctions::printThis($table);
				

	  }	
	
	
	
	
	
	
	  }


?>