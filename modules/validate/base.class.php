<?

class validate_base extends db { 

	public function validate_fields(array $fields) {
		foreach ($fields as $key=>$value) {
			if($value == "") {
				throw new Exception("{$key} is a required field. Please try again");
			}
		}
	}
	
	public function validate_telephone_number($original) {
		$number = preg_replace("/[^0-9]/", "", $original);
		
		if(strlen($number) < 10){
			throw new Exception("Please enter a valid phone number.");
		} else {
			$area=substr($number,0,3);
			$prefix=substr($number, 3,3);
			$line = substr($number,6);
			$phone = "({$area}) {$prefix}-{$line}";
			return $phone;
		
		}
	}
	
	
	public function getCategoryNameFromUrl($url) {
		$parts = explode("/", $url);
	
		//remove empty sections
		foreach($parts as $k=>$v) {
			if(empty($v)) {
				unset($parts[$k]);
			} else {
				$parts[$k] = strtolower($v);
			}
		}
		
		$parts = array_values($parts);
		$main_cat = $parts[1];

		$token=(str_replace("-", "_", $main_cat));
		
		return $token;
	}

	public function return_months() {
		$months_array = array(
				"01"=>"January", "02"=>"February", "03"=>"March", "04"=>"April", "05"=>"May", "06"=>"June", "07"=>"July", "08"=>"August", 
				"09"=>"September", "10"=>"October", "11"=>"November", "12"=>"December"
			);
	}
}
?>