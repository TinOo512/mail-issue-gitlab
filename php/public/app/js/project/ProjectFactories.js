'use strict';

/* Project Factories */

var projectFactories = angular.module('ProjectFactories', ['ProjectServices']);

projectFactories.factory('Project', function() {
    function Project() {
        this.id = null;
        this.aliasName = null;
        this.folderName = null;
        this.projectName = null;
        this.idGitlabProject = null;
    }

    return Project;
});

projectFactories.factory('ProjectList', ['ProjectResource', function(ProjectResource) {
    return {
        selectedId: null,
        items: {},
        getSelectedId: function () {
            return this.selectedId;
        },
        setSelectedId: function (id) {
            this.selectedId = id;
        },
        getSelectedItem: function () {
            return this.items[this.selectedId];
        },
        editSelectedItem: function (item) {
            this.items[this.selectedId] = item;
        },
        delSelectedItem: function () {
            delete this.items[this.selectedId];
            this.selectedId = null;
        },
        getResource: function () {
            return ProjectResource;
        },
        fetchItems: function (callback) {
            var self = this;
            ProjectResource.query(function(data) {
                for (var i=0 ; i<data.length ; i++) {
                    self.items[data[i].id] = data[i];
                }
                return callback();
            });
        }
    }
}]);