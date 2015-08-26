var logicorpo = angular.module('logicorpo', ['ngCookies']);

logicorpo.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

/**
 * Main Controller
 */
logicorpo.controller('MainCtrl',function($scope) {
	$scope.flash=true;
});

/**
 * Menu Controller
 */
logicorpo.controller('MenuCtrl',function($scope, $http, $cookieStore) {



/*
	console.log("1 : "+$cookies.menu);
	console.log("1 : "+$scope.menu);

	if($cookies.menu === "true") {
		$cookies.menu = true;
		$scope.menu = true;
	} else if($cookies.menu === "false") {
		$cookies.menu = false;
		$scope.menu = false;
	} else {
		$scope.menu = true; $cookies.menu=true; console.log("last")
	}

	console.log("2 : "+$cookies.menu);
	console.log("2 : "+$scope.menu);
	*/
/*
*/
	if($cookieStore.get('menu') === undefined) {$scope.menu = true; $cookieStore.put('menu',true); console.log("oui")}
	else {$scope.menu = $cookieStore.get('menu'); console.log("none")}
	
	$scope.$watch(function() { return $scope.menu; }, function(newValue, oldValue) {
		$cookieStore.put('menu', newValue);
	})

});