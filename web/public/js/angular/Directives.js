logicorpo.directive('flash', function() {
	return {
		restrict:'E',
		scope: {type: '@'},
		transclude:true,
		replace:true,
		template: '<div ng-show="flash" ng-init="flash=true" class="flash [[type]]">'+
				  '<div ng-transclude></div>'+
				  '<button ng-click="flash=false" class="flash-close"></button>'+
				  '</div>'
	};
});

logicorpo.directive('solde', function() {
	return {
		restrict:'C',
		scope: {solde: '@'},
		template:'<span class="user-solde" ng-show="solde!=0" ng-class="{negative:solde<0}">[[solde | currency ]]</span>'
	};
});

logicorpo.directive('popup', function() {
	return {
		restrict:'E'
	};
});

logicorpo.filter('capitalize', function() {
    return function(input, all) {
      var reg = (all) ? /([^\W_]+[^\s-]*) */g : /([^\W_]+[^\s-]*)/;
      return (!!input) ? input.replace(reg, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
    }
});

logicorpo.filter('price',
	[ '$filter', function(filter) {
		var currencyFilter = filter('currency');
		return function(amount, currencySymbol) {
			if(amount == 0) return "Gratuit";
			else return currencyFilter(amount, currencySymbol);
		}
	}]
);