<?php

namespace FDT2k\Noctis\Core\Response;
use \FDT2k\Noctis\Core\Env as Env;
class JSONResponse extends Response{

	var $mime = 'application/json';
	var $result = false;
	var $nocache =false;
	//var $data = new Object();;
	function __construct($buffer=''){
		$this->data= $buffer;
		//$this->result = true;
		//$this->build_response();
		$this->setApiMode(true);
	}

	function nocache(){
		$this->nocache = true;
		return $this;
	}
	function build_response(){
		if($this->isApiMode()){
			$response = array();
			$response['result']	=	!$this->hasError();
			$response['data']=$this->getData();
			$response['error']=$this->error_message;
			$response['error_code']= $this->error_code;
			return $response;
		}else{
			$response = $this->data;
			return $response;
		}
	}

	function output_headers(){
		header('Content-type:'.$this->mime);
		header('HTTP/1.0 '.$this->getResponseCode());
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: origin, content-type, accept, Authorization");
		header("Access-Control-Allow-Methods: PUT,GET,POST,DELETE, OPTIONS");
		if($this->nocache){
			header('Pragma: no-cache');
			header('Cache-Control: no-cache');
			header('Expires: 0');
		}
	}


	function output(){
	#var_dump($this->mime);

		$response = $this->build_response();
		//var_dump($response);
		$output = json_encode($response);


		if(!$output){
			throw new \FDT2k\Noctis\Core\Exception("No output ",0);
		}else{
			$this->output_headers();

			echo $output;
		}
	}
}
