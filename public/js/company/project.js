angular.module('project', [ 'ngRoute', 'ngResource' ])

.factory('ProjectsDS', [ '$resource', function($resource) {
	return $resource('http://company/projects/:id', {
        id: '@_id'
    }, {
        update: {
            method: 'PUT'
        }
    });
} ])

.service('Projects', function($q, ProjectsDS) {

	var self = this;
	this.fetch = function() {
		
		var deferred = $q.defer();

		ProjectsDS.get(function(data) {

			self.projects = data._embedded.projects;
			deferred.resolve(self.projects);
		});
		return deferred.promise;
	}

})
.controller('ProjectListController', function($scope, Projects, ProjectsDS) {
    var projectList = this;
    projectList.projectsFormShow = false;
    
    projectList.fetch = function() {
		Projects.fetch().then(function(projects){
		projectList.projects = projects;
	})};
	
	projectList.fetch();
	
	projectList.setProject = function(project){
		projectList.resetForm()
		projectList.project = project;
		projectList.projectsFormShow = true;
		projectList.fetch();
	};
	
	projectList.resetForm = function(){
		projectList.projectsFormShow = !projectList.projectsFormShow;
		projectList.project={};
		projectForm = $scope.projectsForm;
		projectForm.$setPristine();
		projectForm.$setUntouched();
		projectForm.$rollbackViewValue();
		projectForm.$setValidity();
		
		projectForm.name.$setPristine();
		projectForm.site.$setPristine();
		projectForm.description.$setPristine();

		projectList.fetch();
	};

	projectList.destroy = function(id) {
		ProjectsDS.delete({id: projectList.project.id}, (function(data) {
    		data.$promise.then(function(data) {
    			
    			projectList.resetForm();
    		});
	    }));
    };
    
    projectList.add = function(projectForm) {
  	  	ProjectsDS.save(projectList.project, (function(data) {
  	  		data.$promise.then(function(data) {
  	  			  			
  	  		projectList.resetForm();
  	  		});
         }));
    };

    projectList.save = function() {
    	ProjectsDS.update({id: projectList.project.id}, projectList.project, (function(data) {
    		data.$promise.then(function(data) {
    			
    			projectList.resetForm();
    		});
        }));
    };
})
.filter('projectNameDescFilter', function(){
	  return function(projectArray, searchQuery) {
	      if (!projectArray) {
	          return;
	      }
	      // If no search term exists, return the array unfiltered.
	      else if (!searchQuery) {
	          return projectArray;
	      }
	      // Otherwise, continue.
	      else {
	           // Convert filter text to lower case.
	           var query = searchQuery.toLowerCase();
	           // Return the array and filter it by looking for any occurrences of the search term in each items id or name. 
	           return projectArray.filter(function(project){
	              var name = project.name.toLowerCase().indexOf(query) > -1;
	              var description = project.description.toLowerCase().indexOf(query) > -1;
	              return name || description;
	           });
	      } 
	  }    
	})
