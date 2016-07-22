angular.module('project', [ 'ngRoute', 'ngResource', 'ngAnimate', 'ui.bootstrap' ])

.factory('ProjectsDS', [ '$resource', function($resource) {
	return $resource('http://company/projects/:id', {
        id: '@_id'
    }, {
        update: {
            method: 'PUT'
        }
    });
} ])

.factory('CarouselDS', function($resource) {
	return $resource('/js/carousel/data.json');
})

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

.service('CarouselItems', function($q, CarouselDS) {

	var self = this;
	this.fetch = function() {
		
		var deferred = $q.defer();

		CarouselDS.get(function(data) {

			self.logs = data.logs;
			deferred.resolve(self.logs);
		});
		return deferred.promise;
	}

})

.controller('ProjectListController', function($scope, Projects, ProjectsDS) {
    var projectList = this;
    projectList.projectsFormShow = 0;
    
    projectList.rotateRefresh = function (id, varOnoff) {
    	
    	if (varOnoff == 'on') {
    		$('#' + id).addClass('rotateRefresh');
    	} else {
    		$('#' + id).removeClass('rotateRefresh');
    	}
        
    };
    
    projectList.fetch = function() {
    	projectList.rotateRefresh('projectsListRefresh', 'on');
		Projects.fetch().then(function(projects){
			projectList.projects = projects;
			projectList.rotateRefresh('projectsListRefresh', 'off');
	})};
	
	projectList.fetch();
	
	projectList.setProject = function(project){
		projectList.resetForm(1)
		projectList.project = project;
		projectList.fetch();
	};
	
	/*
	 * setFormVisible 0 = hide form, 1 = show form
	 */
	projectList.resetForm = function(setFormVisible){
		projectList.projectsFormShow = setFormVisible;
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
		projectList.rotateRefresh('projectsListRefresh', 'on');
		ProjectsDS.delete({id: projectList.project.id}, (function(data) {
    		data.$promise.then(function(data) {
    			
    			projectList.resetForm(0);
    		});
	    }));
    };
    
    projectList.add = function(projectForm) {
    	projectList.rotateRefresh('projectsListRefresh', 'on');
  	  	ProjectsDS.save(projectList.project, (function(data) {
  	  		data.$promise.then(function(data) {
  	  			  			
  	  		projectList.resetForm(0);
  	  		});
         }));
    };

    projectList.save = function() {
    	projectList.rotateRefresh('projectsListRefresh', 'on');
    	ProjectsDS.update({id: projectList.project.id}, projectList.project, (function(data) {
    		data.$promise.then(function(data) {
    			
    			projectList.resetForm(0);
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
	
.controller('CarouselController', function (CarouselItems) {
	
	var carouselList = this;
	carouselList.slides = [];
	
	carouselList.myInterval = 2000;
	carouselList.noWrapSlides = false;
	carouselList.active = 0;
	  
	carouselList.fetch = function() {
		CarouselItems.fetch().then(function(items){
				carouselList.items = items;
	})};
	
	carouselList.fetch();

});
