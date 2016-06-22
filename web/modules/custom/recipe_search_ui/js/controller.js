angular.module('App', ['ngMaterial', 'ngResource'])
  .config(function($mdThemingProvider) {
    $mdThemingProvider.theme('default')
      .primaryPalette('green')
      .accentPalette('orange');
  })
  .controller('AppCtrl', function($resource, $http, $scope, $mdSidenav) {
    var vm = this;

    vm.toggleSidebar = function() {
      $mdSidenav('left').toggle();
    }

    vm.searchResult = {
      hits: []
    };

    vm.searchParams = {
      input: '',
      sources: {},
      ingredients: {},
    };

    $scope.$watch('app.searchParams', function(newParams, oldParams) {


      SearchEndpoint.query({
        input: newParams.input,
        'sources[]': Object.keys(newParams.sources).filter(function(key) { return newParams.sources[key]; }),
        'ingredients[]': Object.keys(newParams.ingredients).filter(function(key) { return newParams.ingredients[key]; }),
      }).$promise.then(function(response) {
        vm.searchResult.hits = response.hits;
        vm.searchResult.total = response.total;
        vm.sourceFacet = response.sourceFacet;
        vm.ingredientsFacet = response.ingredientsFacet;
      });
    }, true);

    var SearchEndpoint = $resource('/api/search', {}, {
      query: {method:'GET', params: {input: '', from: 0}}
    });


    vm.getSearchSuggestions = function(input) {
      return $http.get('/api/suggest', {
        params: {input: input}
      }).then(function successCallback(response) {
        return response.data;
      })
    }

    vm.updateSearchResults = function() {
      vm.searchResult.hits = [];
      vm.searchResult.total = 0;
      vm.loadingSearchResults = true;

      // Keep track of the input, so we have it even if the field value changes.
      vm.searchParams.input = vm.searchInput;
      vm.searchParams.ingredients = {};
      vm.searchParams.sources = {};
    }

    // In this example, we set up our model using a plain object.
    // Using a class works too. All that matters is that we implement
    // getItemAtIndex and getLength.
    this.infiniteItems = {
      toLoad_: 0,
      // Required.
      getItemAtIndex: function(index) {
        if (index > vm.searchResult.hits.length) {
          this.fetchMoreItems_(index);
          return null;
        }
        return vm.searchResult.hits[index];
      },
      // Required.
      // For infinite scroll behavior, we always return a slightly higher
      // number than the previously loaded items.
      getLength: function() {
        return vm.searchResult.total;
      },
      fetchMoreItems_: function(index) {
        if (this.toLoad_ < index) {
          this.toLoad_ += 10;

          SearchEndpoint.query({input: vm.searchParams.input, from: vm.searchResult.hits.length}, function(response) {
            response.hits.forEach(function(item) {
              vm.searchResult.hits.push(item);
            })

          });
        }
      }
    };

  });