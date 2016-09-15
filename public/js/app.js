var tm = angular.module('tm', ['ngRoute', 'ngStorage', 'datePicker']);

tm.config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {


    $routeProvider

        .when('/', {
            templateUrl:'views/main.html',
            controller: 'auth'
        })
        .when('/register', {
            templateUrl: '/views/register.html',
            controller: 'auth'
        })

        .when('/login', {
            templateUrl: 'views/login.html',
            controller: 'auth'
        })

        .when('/home', {
            templateUrl: 'views/home.html',
            controller: 'main'
        })

        .when('/add-work', {
            templateUrl: 'views/addWork.html',
            controller: 'main'
        })

        .when('/edit-work/:workid', {
            templateUrl: 'views/editWork.html',
            controller: 'main'
        })

        .when('/settings', {
            templateUrl: 'views/editSettings.html',
            controller: 'main'
        })

        .when('/permission', {
            templateUrl: 'views/permission.html',
            controller: 'main'
        })

        .when('/users', {
            templateUrl: 'views/users.html',
            controller: 'admin'
        })

        .when('/edit-user/:userid', {
            templateUrl: 'views/editUser.html',
            controller: 'admin'
        })

        .when('/add-user', {
            templateUrl: 'views/addUser.html',
            controller: 'admin'
        })

        .when('/edit-user-work/:uid', {
            templateUrl: 'views/editUserWork.html',
            controller: 'admin'
        })

        .when('/add-user-work/:uid', {
            templateUrl: 'views/addUserWork.html',
            controller: 'admin'
        })

        .when('/edit-user-individual-work/:wid', {
            templateUrl: 'views/editUserIndividualWork.html',
            controller: 'admin'
        })

        .when('/exported-filter', {
            templateUrl: 'views/exportedFilter.html',
            controller: 'main'
        })

    $httpProvider.interceptors.push('requestInterceptor');

}]);

tm.factory('requestInterceptor', function ($q, $localStorage, $location) {

    return {
        'request': function (config) {
            if ($localStorage && $localStorage.user_token)
                config.headers['UT'] = $localStorage.user_token;
            return config;
        },
        'responseError': function (rejection) {

            if (rejection.status === 403) {
                $localStorage.$reset();
                $location.path("/");
            }

            return $q.reject(rejection);
        }
    };
});


tm.filter('DisplayTime', function () {
    return function (input) {
        if (!input)
            return '';

        var date = new Date(input);
        if (isNaN(date))
            date = new Date(input.replace(/-/g, "/"));
//console.log(date);
        var d = date,
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return month+ '/' + day  + '/' + year;
    }
});
