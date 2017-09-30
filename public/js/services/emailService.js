angular.module('emailService', [])

    .factory('Email', function($http) {

      return {
        // get all the emails
        get : function() {
          return $http.get('/api/emails');
        }
      }

    });