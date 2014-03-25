'use strict';

/* Project Services */

var projectServices = angular.module('ProjectServices', ['ngResource']);

projectServices.factory('ProjectResource', ['$resource',
    function($resource){
        return $resource('/mail/project/:projectId', {}, {
            get: {method:'GET'},
            save: {method:'POST'},
            update: {method:'PUT'},
            query: {method:'GET', isArray:true},
            remove: {method:'DELETE'},
            delete: {method:'DELETE'}
        });
    }]);