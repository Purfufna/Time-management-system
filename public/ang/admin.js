tm.controller('admin', function ($scope, $http, $localStorage, $routeParams, $filter, $location) {


    $scope.allUsers = {};
    $scope.getSingleUser = {};
    $scope.editUserData = {};
    $scope.addNewUser = {};
    $scope.userWorkList = {};
    $scope.currentUserId = {};
    $scope.addUserWork = {};

    $scope.getIndividualWork = {};
    $scope.editIndividualWork = {};

    $scope.adminUser = function () {
        if ($localStorage.role == 1) {
            return true;
        }
        else {
            return false;
        }
    }


    $scope.getAllUsers = function () {

        if ($localStorage.role != 3) {

            $http({
                method: 'GET',
                url: '/user'
            }).then(function successCallback(response) {
                $scope.allUsers = response.data.users;
            }, function errorCallback(response) {
                console.log(response);
            });
        }
        else {
            $location.path('/permission');
        }
    }

    $scope.deleteUser = function (idx, recordId) {

        $scope.allUsers.splice(idx, 1);
        $http({
            method: 'DELETE',
            url: '/user/' + recordId
        }).then(function successCallback(response) {
            if (response.data.success == 'ok') {
                $scope.getAllUsers();
            }
        }, function errorCallback(response) {
            console.log(response);
        });
    }


    $scope.getEditUser = function () {
        if ($localStorage.role != 3) {
            $http({
                method: 'GET',
                url: '/user/' + $routeParams.userid
            }).then(function successCallback(response) {

                $scope.getSingleUser = response.data.user;

                $scope.editUserData.username = $scope.getSingleUser.username;
                $scope.editUserData.preffered_working_hours_per_day = $scope.getSingleUser.preffered_working_hours_per_day;
                $scope.editUserData.role = $scope.getSingleUser.role_id;

            }, function errorCallback(response) {
                console.log(response);
            });
        }
        else {
            $location.path('/permission');
        }
    }

    $scope.editUser = function () {
        $http({
            method: 'PUT',
            url: '/user/' + $routeParams.userid,
            data: $scope.editUserData
        }).then(function successCallback(response) {

            if (response.data.success == 'ok') {
                window.location.href = 'http://dev.timemanagement.com/#/users';
            }
            if (response.data.success == 'false') {
                $scope.message = response.data.message;
            }

        }, function errorCallback(response) {
            console.log(response);
        });
    }

    $scope.addUser = function () {
        $http({
            method: 'POST',
            url: '/user',
            data: $scope.addNewUser
        }).then(function successCallback(response) {

            if (response.data.success == 'ok') {
                window.location.href = 'http://dev.timemanagement.com/#/users';
            }
            if(response.data.success == 'false') {
                $scope.message = response.data.message;
            }
        }, function errorCallback(response) {
            console.log(response);
        });
    }

    $scope.getUserWorkList = function () {
        if ($localStorage.role == 1) {
            $http({
                method: 'GET',
                url: '/work?uid=' + $routeParams.uid
            }).then(function successCallback(response) {
                $scope.userWorkList = response.data.workList;
                $scope.currentUserId = $routeParams.uid;

            }, function errorCallback(response) {
                console.log(response);
            });
        }
        else {
            $location.path('/permission');
        }
    }

    $scope.adminAddUserWork = {};
    $scope.addUserWork = function () {
        if ($localStorage.role == 1) {
            $http({
                method: 'POST',
                url: '/work',
                data: {data: $scope.adminAddUserWork, user: $routeParams.uid}
            }).then(function successCallback(response) {
                if (response.data.success == 'ok') {
                    $location.path('/edit-user-work/' + $routeParams.uid);
                }
                else {
                    $location.path('/permission');
                }
            }, function errorCallback(response) {
                console.log(response);
            });
        }
    }

    $scope.adminDeleteUserWork = function (idx, recordId) {
        if ($localStorage.role == 1) {
            $scope.userWorkList.splice(idx, 1);
            $http({
                method: 'DELETE',
                url: '/work/' + recordId
            }).then(function successCallback(response) {
                if (response.data.success == 'ok') {
                    $scope.getUserWorkList();
                }
                else {
                    $location.path('/permission');
                }

            }, function errorCallback(response) {
                console.log(response);
            });
        }
    }

    $scope.getAdminEditUserIndividualWork = function () {
        if ($localStorage.role == 1) {
            $http({
                method: 'GET',
                url: '/work/' + $routeParams.wid
            }).then(function successCallback(response) {
                $scope.getIndividualWork = response.data.work;

                $scope.editIndividualWork.work_description = $scope.getIndividualWork.work_description;
                $scope.editIndividualWork.hours = $scope.getIndividualWork.hours;
                $scope.editIndividualWork.date_time = $scope.getIndividualWork.date_time;

            }, function errorCallback(response) {
                console.log(response);
            });
        }
    }


    $scope.setAdminEditUserIndividualWork = function () {
        if ($localStorage.role == 1) {
            $http({
                method: 'PUT',
                url: '/work/' + $routeParams.wid,
                data: $scope.editIndividualWork
            }).then(function successCallback(response) {
                if (response.data.success == 'ok') {
                    window.location.href = 'http://dev.timemanagement.com/#/users';
                }
                if(response.data.success == 'failed') {
                    $scope.message = response.data.message
                }
                else {
                    $location.path('/permission');
                }

            }, function errorCallback(response) {
                console.log(response);
            });
        }
    }

});
