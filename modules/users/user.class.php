<?

class users_user extends db { 
	
	public $TBL_USERS=users;
	public $TBL_USER_INFO=user_info;
	public $TBL_USER_INFO_EXT=user_info_ext;
	

    function get() {
    	$where = $this->sqlBuild();
    	$sql = "select * from {$this->TBL_USERS} where is_active {$where}";
    	$stmt=$this->db->prepare($sql);
    	$stmt->execute();
    	while($query=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
    		return $query;
    	}
    }

    function addUser() {
    	$sql = "INSERT INTO " .$this->TBL_USERS . " ".$this->insert($this->args).";";
		$prepare = $this->db->prepare($sql);
		$prepare->execute($this->bind($this->args));
		return $this->db->lastInsertId();
    }

    function addUserInfo() {
    	$sql = "Insert into {$this->TBL_USER_INFO}  " . $this->insert($this->args);
    	$prepare = $this->db->prepare($sql);
		$prepare->execute($this->bind($this->args));

    }

     function addUserInfoExt() {
        $sql = "Insert into {$this->TBL_USER_INFO_EXT}  " . $this->insert($this->args);
        $prepare = $this->db->prepare($sql);
        $prepare->execute($this->bind($this->args));

    }

    function getUserInfo() {
        $where = $this->sqlBuild();
        $sql = "select * from {$this->TBL_USER_INFO} where is_active {$where}";
        $stmt=$this->db->prepare($sql);
        $stmt->execute();
        while($query=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
            return $query;
        }
    }

    function getUserInfoExt() {
        $where = $this->sqlBuild();
        $sql = "select * from {$this->TBL_USER_INFO_EXT} where is_active {$where}";
        $stmt=$this->db->prepare($sql);
        $stmt->execute();
        while($query=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
            return $query;
        }
    }

    function updateUsersLogin ($id_user) {
        $sql = "update {$this->TBL_USERS} set ";
        $sql.=$this->sql_select($this->args);
        $sql.=" where id_user = '{$id_user}'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }

    function updateUserInfo($id_user) {
        $sql = "update {$this->TBL_USER_INFO} set ";
        $sql.=$this->sql_select($this->args);
        $sql.=" where id_user = '{$id_user}'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }

    function updateUserInfoExt($id_user) {
        $sql = "update {$this->TBL_USER_INFO_EXT} set ";
        $sql.=$this->sql_select($this->args);
        $sql.=" where id_user = '{$id_user}'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }
}

?>