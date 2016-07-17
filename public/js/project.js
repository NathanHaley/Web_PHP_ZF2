angular.module('project', [ 'ngRoute', 'ngResource' ])

.factory('ProjectsDS', [ '$resource', function($resource) {
	return $resource('../projects/:id', {
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
		
		//TODO: Can this be bound to add/delete actions?
		//if (this.projects) return $q.when(this.projects); 
		
		var deferred = $q.defer();

		ProjectsDS.get(function(data) {

			self.projects = data._embedded.projects;
			deferred.resolve(self.projects);
		});
		return deferred.promise;
	}

})

.config(function($routeProvider) {

	var resolveProjects = {
		projects : function(Projects) {
			
			var pf = Projects.fetch();
			return pf;
		}
	};

	$routeProvider.when('/', {
		controller : 'ProjectListController as projectList',
		templateUrl : 'list',
		resolve : resolveProjects
	}).when('/edit/:projectId', {
		controller : 'EditProjectController as editProject',
		templateUrl : '/company/detail',
		resolve : resolveProjects
	}).when('/new', {
		controller : 'NewProjectController as editProject',
		templateUrl : 'detail',
		resolve : resolveProjects
	}).otherwise({
		redirectTo : '/'
	});

})

.controller('ProjectListController', function(projects) {
	var projectList = this;
	projectList.projects = projects;

})

.controller('NewProjectController', function($location, projects, ProjectsDS) {
  var editProject = this;
  editProject.save = function() {
	  ProjectsDS.save(editProject.project, (function(data) { console.log('new');
          $location.path('/');
       }));
  };
})

.controller('EditProjectController',
  function($location, $routeParams, projects, $filter, ProjectsDS) {
    var editProject = this;
    var projectId = $routeParams.projectId,
        projectIndex;

    editProject.projects = projects;
    
    editProject.project =  $filter('filter')(editProject.projects, {id: projectId},true)[0];

    editProject.destroy = function() {
    	ProjectsDS.delete({id: projectId}, (function(data) {
           $location.path('/');
        }));
    };

    editProject.save = function() {
    	ProjectsDS.update({id: projectId}, editProject.project,(function(data) {
           $location.path('/');
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

