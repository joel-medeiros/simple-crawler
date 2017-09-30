angular.module('mainCtrl', [])

// inject the Email service into our controller
    .controller('mainController', function($scope, $http, Email) {

      // loading variable to show the spinning loading icon
      $scope.loading = true;

      // get all the emails first and bind it to the $scope.emails object
      // use the function we created in our service
      // GET ALL EMAILS ==============
      Email.get()
          .success(function(data) {
            $scope.emails = data;
            $scope.loading = false;
          });

    });