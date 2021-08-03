<!DOCTYPE html>
<html>
 <head>
  <title>Submit Form Data by using AngularJS with Validation using PHP</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.9/css/jquery.dataTables.min.css"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.9/css/dataTables.bootstrap.min.css"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.4.3/angular-datatables.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body  ng-app="myapp" ng-controller="formcontroller" ng-init="displayData()">
  <div class="container">
  <br /><br />
  <div class="row container-fluid">
   <h2 align="center">Employees Details</h2>
   <br /><br />
   <div class="alert alert-success alert-dismissible container-fluid" ng-show="successMessage" >
    <a href="#" class="close" data-dismiss="alert" aria-label="close" >&times;</a>
    {{successMessage}}
   </div>
   <div class="container-fluid" align="right">
    <button type="button" name="add_button" ng-click="addData()" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>Add New Employee</button>
   </div>
    <br>
   <div class="modal fade" role="dialog"  id="crudmodal" style="margin: 0px auto; max-width:720px;">
     <div class="modal-content">
      <form method="post" name="userForm" ng-submit="submitData()">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" ng-click="closeModal()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">{{title}}</h4>
         </div>
       <div class="modal-body">
         <div class="form-group">
         <label>Name <span class="text-danger">*</span></label>
         <input type="text" name="name" ng-model="insert.name" class="form-control" />
         <span class="text-danger" ng-show="errorName">{{errorName}}</span>
         </div>
         <div class="form-group">
         <label>Address <span class="text-danger">*</span></label>
         <textarea name="address" ng-model="insert.address" class="form-control"></textarea><span class="text-danger" ng-show="errorAddress">{{errorAddress}}</span>
         </div>
         <div class="form-group">
         <label>Salary <span class="text-danger">*</span></label>
         <input type="text" string-to-number name="salary" ng-model="insert.salary" class="form-control" pattern="^[0-9]+\.?[0-9]{0,2}$"/>
         <span class="text-danger" ng-show="errorSalary">{{errorSalary}}</span>
         </div>
       </div>
     <div class="modal-footer">
      <input type="hidden" name="hidden_id" value="{{hidden_id}}" />
      <input type="submit" name="insert" class="btn btn-info" ng-click="$('#crudmodal').modal('hide')"  value="{{submit_button}}"/>
      <button type="button" class="btn btn-default" ng-click="closeModal()"  data-dismiss="modal">Close</button>
     </div>
    </form>
   </div>
  </div>
  
   <div class="container-fluid" ng-init="fetchData()" class="table-responsive" style="overflow-x: unset;">
   		<table datatable="ng"  class="table table-bordered table-striped">
   			<thead>
   				<tr>
	   				<th>S. No.</th>
	   				<th>Name</th>
	   				<th>Address</th>
	   				<th>Salary</th>
	   				<th>Date</th>
	   				<th>Edit</th>
	   				<th>Delete</th>
	   			</tr>
   			</thead>
   			<tbody>
   				<tr ng-repeat="record in x">
   					<td>{{$index+1}}</td>
   					<td>{{record.name}}</td>
   					<td>{{record.address}}</td>
   					<td>{{record.salary | currency: '&#8360; '}}</td>
   					<td>{{record.date}}</td>
   					<td><button type="button" ng-click="fetchSingleData(record.id)" class="btn btn-warning btn-xs">Edit</button></td>
            <td><button type="button" ng-click="deleteData(record.id)" class="btn btn-danger btn-xs">Delete</button></td>
          </tr>
   			</tbody>
   		</table>
   </div>
  </div>
</div>
 </body>
</html>
<script>

var application = angular.module("myapp", ['datatables']);
application.controller("formcontroller", function($scope, $http){
$scope.insert = {};


  $scope.openModal = function(){
    $('#crudmodal').modal('show');
   };

   $scope.closeModal = function(){
    $('#crudmodal').modal('hide');
    $scope.insert = null;
    location.reload();
   };

  $scope.addData = function(){
  $scope.title = 'Add Data';
  $scope.submit_button = "Insert";
  $scope.openModal();
 };
 console.log($scope.insert);

 $scope.submitData = function(){ 
   $http({
   method:"POST",
   url:"code.php",
   data: {'name' : $scope.insert.name, 'address' : $scope.insert.address, 'salary' : $scope.insert.salary, 'action' : $scope.submit_button, 'id' : $scope.hidden_id}
,
  }).success(function(data){
    console.log(data);
   if(data.error)
   {
    $scope.errorName = data.error.name;
    $scope.errorAddress = data.error.address;
    $scope.errorSalary = data.error.salary;
    $scope.successMessage = null;
   }
   else
   {
    $scope.insert = null;
    $scope.errorName = null;
    $scope.errorAddress = null;
    $scope.errorSalary = null;
    $scope.insert = null;           
    $scope.successMessage = data.message;
    $('#crudmodal').modal('hide');    
    $scope.displayData(); 
   }
  });

 };

   

	 $scope.displayData = function(){  
      $http({
       method:"POST",
       url:"code.php",
       data:{'action':'Get_All'}
      }).success(function(data){  
          $scope.x = data;  
        });  
   };  

   $scope.fetchSingleData = function(id){
	  $http({
	   method:"POST",
	   url:"code.php",
	   data:{'id':id, 'action':'fetch_single_data'}
	  }).success(function(data){
      // alert(data);
     console.log(data);
	   $scope.insert.name = data.name;
	   $scope.insert.address = data.address;
	   $scope.insert.salary = data.salary;
	   $scope.hidden_id = id;
     $scope.title = 'Edit Data';
     $scope.submit_button = 'Edit';
     $scope.openModal();

	  });
	};

  
$scope.deleteData = function(id){
  if(confirm("Are you sure you want to remove it?"))
  {
    $http({
      method : "POST",
      url : "code.php",
      data : {'id' : id, 'action' : "Delete"}
    }).success(function(data){
      console.log(data);
        $scope.successMessage = data.message;
        $scope.displayData();
    });
  }
};


});


</script>