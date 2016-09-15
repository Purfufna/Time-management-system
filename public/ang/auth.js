tm.controller('auth', function ($scope, $http, $localStorage, $routeParams, $filter, $location) {

    $scope.registerData = {};
    $scope.loginData = {};
    $scope.userName = function () {
        if ($localStorage && $localStorage.username)
            return $localStorage.username;
    };

    $scope.register = function () {
        $http({
            method: 'POST',
            url: '/auth/register',
            data: $scope.registerData

        }).then(function successCallback(response) {

            if (response.data.success == 'ok') {
                $scope.message = response.data.message;

                document.getElementById("username").value = '';
                document.getElementById("password").value = '';
                document.getElementById("prefferedhours").value = '';
            }
            else if (response.data.success == 'false') {
                $scope.message = response.data.message;
            }
            else if (response.data.success == 'failed') {
                $scope.message = response.data.message;
            }
            else {
                $scope.message = 'Some error occured';
            }


        }, function errorCallback(response) {
            //console.log(response);
        });
    };

    $scope.login = function () {
        $http({
            method: 'POST',
            url: '/auth/login',
            data: $scope.loginData

        }).then(function successCallback(response) {

            if (response.data.success == 'ok') {
                $localStorage.user_token = response.data['user_token'];
                $localStorage.role = response.data['role'];
                $localStorage.username = response.data['username'];

                $location.path('/home');
            }
            else
                $scope.message = 'Wrong username or password :(';

        }, function errorCallback(response) {
            //console.log(response);
        });
    }

    $scope.loginInit = function () {
        if ($localStorage.user_token) {
            $location.path('/home');
        }
    }

    $scope.logout = function () {
        $localStorage.$reset();
    }

    $scope.loggedIn = function () {
        if ($localStorage.user_token) {
            return true;
        }
        else {
            return false;
        }
    }

    $scope.loggedOut = function () {
        if (!$localStorage.user_token) {
            return true;
        }
        else {
            return false;
        }
    }

    $scope.manager = function () {
        if ($localStorage.user_token && $localStorage.role != 3) {
            return true;
        }
        else {
            return false;
        }
    }
});