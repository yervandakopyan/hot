<?

class mail_base extends db {
	
	public function sendMail(array $mail_info) {
		$file = $mail_info['template'];
	
		$file_content = file_get_contents($file, FILE_USE_INCLUDE_PATH);
		
		$search_array=array_keys($mail_info);
		$replace_array=array_values($mail_info);
		
		$message =str_replace($search_array, $replace_array, $file_content);
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= 'Bcc: akopyany@gmail.com' . "\r\n";
		
		$to=$mail_info['mail_to'];
		$headers .= 'From: <info@listpalace.com>' . "\r\n";
		$subject=$mail_info['subject'];
		mail($to,$subject,$message,$headers);
		
		
	
	}
}