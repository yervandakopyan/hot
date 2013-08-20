<?

class location_base extends db { 
	
	public $TBL_STATE=state;
	public $TBL_COUNTRY=country;

    function getState() {
    	$where = $this->sqlBuild();
    	$sql = "select * from {$this->TBL_STATE} where is_active {$where}";
    	$stmt=$this->db->prepare($sql);
    	$stmt->execute();
    	while($query=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
    		return $query;
    	}
    }

    function getCountry() {
    	$where = $this->sqlBuild();
        $sql = "select * from {$this->TBL_COUNTRY} where is_active {$where}";
        $stmt=$this->db->prepare($sql);
        $stmt->execute();
        while($query=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
            return $query;
        }
    }
}

?>