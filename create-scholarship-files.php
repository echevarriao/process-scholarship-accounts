<?php

/* set up variables for code */

$host = "PUT_HOST_NAME";
$user = "USER_ACCOUNT";
$pass = "USER_PASS";
$stmt = null;
$dtbs = "DATA_BASE";
$dsn = "";
$pdo = null;

/* extract php data based on accounts only */
$scholarships = array();
/* sample data:
 *
  $scholarships = array(
  array('AccountNumber' => '123456XYZ'),
  array('AccountNumber' => '123456XYZ'),
  .
  .
  .
  .
  .
  .
  .
  array('AccountNumber' => '123456XYZ'),
  array('AccountNumber' => '123456XYZ')
  
);
 *
 */ 


/*
 * Connect to database
 *
 */ 

$dsn = "mysql:host=$host;dbname=$dtbs";
$pdo = new PDO($dsn, $user, $pass);

  /* lets loop through the accounts */

for($i = 0; $i < count($scholarships); $i++){

  $account = $scholarships[$i]['AccountNumber'];

  $stmt = $pdo->prepare("select * from scholarships where AccountNumber = :account");

  $stmt->bindParam(":account", $account, PDO::PARAM_STR, 32);

  $stmt->execute();

  /* create file based on account number */

  $fp = fopen("account-$account.txt", "w");

  /* output columns */
  
	fprintf($fp, "F_FirstName,F_LastName,F_PeopleSoftId,F_Email,F_City,F_State,F_PostalCode,F_Amount,F_Semester,F_Year\n");

  /* output data to file */
  
  while($row = $stmt->fetch()){

  $m_zip = $row['F_PostalCode'];

  /* if the zip code string is less then 5, add additional '0' because it is a Connecticut postal code */
  
  if(strlen($m_zip) < 5){

  $row['F_PostalCode'] = "0" . $row['F_PostalCode'];
    
	fprintf($fp, "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s", $row['F_FirstName'],$row['F_LastName'],$row['F_PeoplesoftId'],$row['F_email'],$row['F_City'],$row['F_State'],$row['F_PostalCode'],$row['F_Amount'],$row['F_Semester'],$row['F_Year']);
    
  } else {
    
  /* if the zip code string is not less then 5, print postal code as normal */
  
	fprintf($fp, "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s", $row['F_FirstName'],$row['F_LastName'],$row['F_PeoplesoftId'],$row['F_email'],$row['F_City'],$row['F_State'],$row['F_PostalCode'],$row['F_Amount'],$row['F_Semester'],$row['F_Year']);

  }

  /* add new line after each row */
	
	fprintf($fp, "\n");
	
} // end while loop

  /* close file */

fclose($fp);

} // end for loop
