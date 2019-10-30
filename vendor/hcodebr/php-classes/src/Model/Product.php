<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;



//classe model tem getters e setters
class Product extends Model{

	public function listAll(){

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_products ORDER BY desproduct");
	}

	public static function checkList($list){

		foreach ($list as &$row) {

			$p = new Product();
			$p->setData($row);
			$row = $p->getValues();
			
		}

		return $list;


	}


	public function save(){

		$sql = new Sql();

		$results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
					":idproduct"=>$this->getidproduct(),
					":desproduct"=>$this->getdesproduct(),
					":vlprice"=>$this->getvlprice(),
					":vlwidth"=>$this->getvlwidth(),
					":vlheight"=>$this->getvlheight(),
					":vllength"=>$this->getvllength(),
					":vlweight"=>$this->getvlweight(),
					":desurl"=>$this->getdesurl()

	));

		$this->setData($results[0]);

	}

	public function get($idproduct){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", [':idproduct'=>$idproduct]);

		$this->setData($results[0]);
	}

public function delete(){

	$sql = new Sql();

	$sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct", [
		':idproduct'=>$this->getidproduct()]);

}


public function checkPhoto(){

	if (file_exists(
			$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
			"res" . DIRECTORY_SEPARATOR . 
			"site" . DIRECTORY_SEPARATOR . 
			"img" . DIRECTORY_SEPARATOR . 
			"products" . DIRECTORY_SEPARATOR . 
			$this->getidproduct()."-".$this->getdesurl().".jpg"
			)) {
		
		$url = "/res/site/img/products/".$this->getidproduct()."-".$this->getdesurl().".jpg";

	} else {

		$url =  "/res/site/img/sem-foto.jpg";
	}

	return $this->setdesphoto($url); // retorna e seta no objeto
}

public function getValues(){

	$this->checkPhoto();

	$values = parent::getValues(); // vai fazer o q a classe pai faz

	return $values;

}

public function setPhoto($file){

	$extesion = explode('.', $file['name']); //pega nome do arquivo pega o ponto e faz um array
	$extesion = end($extesion); // pega a ultima posição do array

	switch ($extesion) {
		case 'jpg':

			case 'jpeg':
			$image = imagecreatefromjpeg($file["tmp_name"]);
			break;

			case 'gif':
			$image = imagecreatefromgif($file["tmp_name"]);
			break;
			case 'png':
			$image = imagecreatefrompng($file["tmp_name"]);
			break;
		
	}

	$dest = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
			"res" . DIRECTORY_SEPARATOR . 
			"site" . DIRECTORY_SEPARATOR . 
			"img" . DIRECTORY_SEPARATOR . 
			"products" . DIRECTORY_SEPARATOR . 
			$this->getidproduct()."-".$this->getdesurl().".jpg";

	imagejpeg($image, $dest);
	imagedestroy($image);

	$this->checkPhoto();


}

public function getFromURL($desurl){

	$sql = new Sql();

	$rows = $sql->select("SELECT * FROM tb_products WHERE desurl = :desurl LIMIT 1",[
	
	'desurl'=>$desurl]);

	$this->setData($rows[0]);
}

public function getCategories()
	{
		$sql = new Sql();

		return $sql->select("
			SELECT * FROM tb_categories a INNER JOIN tb_productscategories b ON a.idcategory = b.idcategory WHERE b.idproduct = :idproduct
		", [

			':idproduct'=>$this->getidproduct()
		]);

	}

}

 ?>