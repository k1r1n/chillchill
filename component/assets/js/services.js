angular.module('app.services', ['ngFacebook'])
.config( function( $facebookProvider ) {
  $facebookProvider.setAppId('1779493598931689')
  $facebookProvider.setVersion("v2.8")
})
.run( function( $rootScope ) {
  // Load the facebook SDK asynchronously
  (function(){
     // If we've already installed the SDK, we're done
     if (document.getElementById('facebook-jssdk')) {return;}

     // Get the first script element, which we'll use to find the parent node
     var firstScriptElement = document.getElementsByTagName('script')[0];

     // Create a new script element and set its id
     var facebookJS = document.createElement('script');
     facebookJS.id = 'facebook-jssdk';

     // Set the new script's source to the source of the Facebook JS SDK
     facebookJS.src = '//connect.facebook.net/en_US/all.js';

     // Insert the Facebook JS SDK into the DOM
     firstScriptElement.parentNode.insertBefore(facebookJS, firstScriptElement);
   }());
})
.factory('getIP', function($http) {
  var m = {
    async: function() {
      var promise = $http.get('http://ipv4.myexternalip.com/json')
			.then(function (response) {
        return response.data.ip
      })
      return promise
    }
  }
  return m;
})
.factory('auth', function($http) {
  var m = {
    async: function() {
      var promise = $http.get('./controller/login.php')
      .then(function (response) {
        return response.data
      })
      return promise
    }
  }
  return m;
})
