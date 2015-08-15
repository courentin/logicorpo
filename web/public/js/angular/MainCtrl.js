var logicorpo = angular.module('logicorpo', []);

logicorpo.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

logicorpo.controller('MainCtrl', function ($scope, $http) {
	$scope.flash=true;
});