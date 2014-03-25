'use strict';


// Declare app level module which depends on filters, and services
angular.module('myIssueGitlab', [
  'ngRoute',
  'AccountCtrl',
  'ProjectCtrl',
  'AccountProjectCtrl'
]).
config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/account', {templateUrl: 'app/partials/account/account-list.html'});
  $routeProvider.when('/project', {templateUrl: 'app/partials/project/project-list.html'});
  $routeProvider.when('/account-project', {templateUrl: 'app/partials/account-project/account-project-list.html'});
  $routeProvider.otherwise({redirectTo: '/account'});
}]);
