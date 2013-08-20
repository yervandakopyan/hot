<?php

class image_image extends db { 
	
	public $TBL_IMAGES=user_images;
	
	function get() {
    	$where = $this->sqlBuild();
    	$sql = "select * from {$this->TBL_IMAGES} where is_active {$where}";
    	$stmt=$this->db->prepare($sql);
    	$stmt->execute();
    	while($query=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
    		return $query;
    	}
    }

    function addImage() {
    	$sql = "INSERT INTO " .$this->TBL_IMAGES . " ".$this->insert($this->args).";";
		$prepare = $this->db->prepare($sql);
		$prepare->execute($this->bind($this->args));
    }

    function updateImage($id_image) {
        $sql = "update {$this->TBL_IMAGES} set ";
        $sql.=$this->sql_select($this->args);
        $sql.=" where id_image = '{$id_image}'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }

    function getImageCount() {
    	$where = $this->sqlBuild();
    	$sql = "select count(id_image) as count from {$this->TBL_IMAGES} where is_active {$where}";
    	$stmt=$this->db->prepare($sql);
    	$stmt->execute();
    	while($query=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
    		return $query;
    	}
    }

    function deleteImage($id_image) {
        $where = $this->sqlBuild();
        $sql = "update {$this->TBL_IMAGES} set is_active='0' where id_image = '{$id_image}'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }

}

?>