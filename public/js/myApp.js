var myApp = angular.module('myApp',['ngAnimate']);

myApp.controller('ContactUsFormCustomController', ['$scope','$http', '$filter', function($scope, $http, $filter) {

	// Table Controls
	
	$scope.sortBy     = 'timestamp_add'; // set the default sort type
	$scope.sortReverse  = true;  // set the default sort order
	$scope.vlimit = 4;
	$scope.vbegin = 0;
	$scope.editId = 0;

	$scope.activePageRange = function(startIdx, endIdx) {
		$scope.vlimit = endIdx - startIdx;
		$scope.vbegin = startIdx;
		$scope.tableMenuActivePage(startIdx, startIdx + $scope.vlimit);
	}

	$scope.orderBy = function(inSortBy, inSortReverse) {
		
		
		//$scope.clearFieldErrorStyleForGroup(fullNameField, emailField, commentField);
		
		$scope.comments = $filter('orderBy')($scope.comments, inSortBy, inSortReverse);
		$scope.sortBy = inSortBy;
		$scope.sortReverse = inSortReverse;
	}

	$scope.searchByAction = function() {
		$scope.comments = $filter('filter')($scope.bComments,$scope.searchBy);
		$scope.orderBy($scope.sortBy, $scope.sortReverse);
	}
	
	$scope.tableMenuActivePage = function(startIdx, vlimit) {
		
		var tableMenuItems = angular.element( document.querySelector( '#tableMenu_contactUsAdmin' ) ).children();
		
		var cnt = tableMenuItems.length;
		var i = 0;

		for (;i < cnt;i++){
			var item = tableMenuItems[i];
			var min = item.attributes['min'].value;
			var max = item.attributes['max'].value;
			//window.alert((min + ' = ' + startIdx + '   ' + max + ' = ' + vlimit));
			item = angular.element(item);
			item.removeClass("active");
			if((min == startIdx) && (max == vlimit)) {
				item.addClass("active");
			}
			
		}
		
	}

	// Data Controls

	// Get data
	$http.get('controller/contactUsFormAdmin_controller.php', { params: { "action": "get" }}).success(function(data) {
		$scope.comments = data;
		$scope.bComments = angular.copy(data);
		$scope.orderBy('timestamp_add', false);
	});
	
	// Remove data
	$scope.removeRow = function (idx, delId) {
	
		var rowIdx = $scope.getRowIdx(delId);

		if( window.confirm("Delete row?")) {

			$http.get('controller/contactUsFormAdmin_controller.php', { params: { "action": "del", "id": delId}}).success(function(serverMsg) {
				$scope.comments.splice(rowIdx, 1);
				//console.log('Server Message: ' + serverMsg);

			});
		}

	}
	
	// Error handling
	$scope.rowErrors = [];
	
	$scope.nameErrors = [];
	$scope.emailErrors = [];
	$scope.commentErrors = [];
	
	$scope.errorHandler = function(errorArray, column) {
		
		//console.log(errorArray);
		
		if(errorArray != 0){
			column.addClass('alert-danger');
		}
	}
	
	$scope.errorHandlerForGroup = function(rowErrorsArray,fullNameField, emailField, commentField) {
		
		$scope.errorHandler(rowErrorsArray.error.fullName, fullNameField);
		$scope.errorHandler(rowErrorsArray.error.email, emailField);
		$scope.errorHandler(rowErrorsArray.error.comment, commentField);
		
		$scope.nameErrors = rowErrorsArray.error.fullName;
		$scope.emailErrors = rowErrorsArray.error.email;
		$scope.commentErrors = rowErrorsArray.error.comment;
		
		$scope.rowErrors = rowErrorsArray.error;
		
	}
	
	// Set data
	$scope.setRow = function (idx, setId) {
	
		var rowIdx = $scope.getRowIdx(setId);
		var rowJSON = $scope.comments[rowIdx];
		
		var fullNameField = angular.element( document.querySelector( '#row_fullName_' + setId ) );
		var emailField = angular.element( document.querySelector( '#row_email_' + setId ) );
		var commentField = angular.element( document.querySelector( '#row_comment_' + setId ) );
		
		var fullNameColumnHTML = fullNameField.html();
		var emailColumnHTML = emailField.html();
		var commentColumnHTML = commentField.html();
		
		$http.get('controller/contactUsFormAdmin_controller.php', {
			params: { 
				"action": "set", 
				"id": setId,
				"fullName": fullNameColumnHTML,
				"email": emailColumnHTML,
				"comment": 	commentColumnHTML}}).success(function(serverMsg) {
					console.log('Server Message: ' + serverMsg);
					//window.alert(setId);
					if(serverMsg.error) {
						
						$scope.errorHandlerForGroup(serverMsg, fullNameField, emailField, commentField);
						$scope.editId = setId;
						
					} else {
			
						//Clear error styles if any
						$scope.clearFieldErrorStyleForGroup(fullNameField, emailField, commentField);
						
						rowJSON.fullName = fullNameColumnHTML;
						rowJSON.email = emailColumnHTML;
						rowJSON.comments = commentColumnHTML;
						
						$scope.restoreRow(0,setId,rowJSON);
					}
					
				});

	}
	
	$scope.clearFieldErrorStyleForGroup = function(fullNameField, emailField, commentField){
		
		$scope.clearFieldErrorStyles(fullNameField);
		$scope.clearFieldErrorStyles(emailField);
		$scope.clearFieldErrorStyles(commentField);
	}
	
	$scope.clearFieldErrorStyles = function(field) {
		
		field.removeClass('alert-danger');
		
	}
	
	$scope.restoreRow = function (idx, rowId, rowJSON) {
		var rowIdx;
		var rowJSONInner = rowJSON;
		
		if(!rowJSONInner) {
			rowIdx = $scope.getRowIdx(rowId);
			rowJSONInner = $scope.bComments[rowIdx];
		}
		console.log(rowJSONInner.fullName);
		
		var fullNameField = angular.element( document.querySelector( '#row_fullName_' + rowId ) );
		var emailField = angular.element( document.querySelector( '#row_email_' + rowId ) );
		var commentField = angular.element( document.querySelector( '#row_comment_' + rowId ) );
		
		fullNameField.html(rowJSONInner.fullName);
		emailField.html(rowJSONInner.email);
		commentField.html(rowJSONInner.comments);
		
		$scope.clearFieldErrorStyleForGroup(fullNameField, emailField, commentField);
		
		$scope.rowErrors = [];
		$scope.editId = 0;
	}
	
	$scope.getRowIdx = function(rowId) {
		var cnt = $scope.comments.length;
		var i = 0;

		for(;i<cnt;i++){

			if($scope.comments[i].id === rowId) {
				return i;
			}
		}
	}

}]);