<?php
//insert.php
include("DB.php");
$form_data = json_decode(file_get_contents("php://input"));
// print_r($form_data);
$data = array();
$error = array();

	if($form_data->action == "Insert")
	{

				if(empty($form_data->name))
				{
				 $error["name"] = "Name is required";
				}

				if(empty($form_data->address))
				{
				 $error["address"] = "Address is required";
				}
				if(empty($form_data->salary))
				{
				 $error["salary"] = "Salary is required";
				}

				if(!empty($error))
				{
				 $data["error"] = $error;
				}
				else
				{
				 $name = mysqli_real_escape_string($connect, $form_data->name); 
				 $address = mysqli_real_escape_string($connect, $form_data->address);
				 $salary = mysqli_real_escape_string($connect, $form_data->salary); 
				 $query = "
				  INSERT INTO employees(name, address, salary, date) VALUES ('$name', '$address', '$salary', now())";
				 if(mysqli_query($connect, $query))
				 {
				  $data["message"] = "Data Inserted";         
				 }
				}

				echo json_encode($data);

	}


if($form_data->action == "fetch_single_data")
{
	 $query = "SELECT * FROM employees WHERE id='".$form_data->id."'";
	 $res = mysqli_query($connect, $query);
	 $row = mysqli_fetch_array($res);
	  $data['name'] = $row['name'];
	  $data['address'] = $row['address'];
	  $data['salary'] = $row['salary'];
 
 echo json_encode($data);
}

if($form_data->action == 'Edit')
{

	$name = mysqli_real_escape_string($connect, $form_data->name); 
	$address = mysqli_real_escape_string($connect, $form_data->address);
	$salary = mysqli_real_escape_string($connect, $form_data->salary); 
	$query = "UPDATE employees SET name = '$name', address = '$address', salary = '$salary' WHERE id='".$form_data->id."'";
	if(mysqli_query($connect, $query))
	{
		$data["message"] = "Data Updated";         
	}
echo json_encode($data);
}		


if($form_data->action == 'Delete')
{
	$query = "Delete from employees  WHERE id='".$form_data->id."'";
	if(mysqli_query($connect, $query))
	{
		$data["message"] = "Deleted Successfully";         
	}
	echo json_encode($data);
}	

if($form_data->action == 'Get_All'){
$query = "SELECT * FROM employees ORDER BY id";
$result = mysqli_query($connect, $query);  

if(mysqli_num_rows($result) > 0)  
 {  
      while($row = mysqli_fetch_array($result))  
      {  

           $data[] = $row;  
      
      } 

      echo json_encode($data);  
 }  			
}
?>
