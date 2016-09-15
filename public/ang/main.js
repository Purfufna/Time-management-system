tm.controller('main', function ($scope, $http, $localStorage, $routeParams, $filter, $location) {

    $scope.work = {};
    $scope.listOfWork = {};
    $scope.getIndividualWork = {};
    $scope.editIndividualWork = {};
    $scope.getUserSettings = {};
    $scope.editedSettings = {};
    $scope.filter = {};
    $scope.filterData = {};

    $scope.workList = function () {

        var fromDate = $filter('date')($scope.filter.from, 'yyyy-MM-dd');
        var toDate = $filter('date')($scope.filter.to, 'yyyy-MM-dd');
        var url = '/work';

        if (fromDate !== undefined && toDate !== undefined) {
            url = '/work?fromDate=' + fromDate + '&toDate=' + toDate;
        }
            $http({
                method: 'GET',
                url: url
            }).then(function successCallback(response) {
                $scope.listOfWork = response.data.workList;
                $scope.filterData = response.data.exported;
                console.log($scope.filterData);
            }, function errorCallback(response) {
                console.log(response);
            });
        }



    $scope.addWork = function () {

        $http({
            method: 'POST',
            url: '/work',
            data: $scope.work
        }).then(function successCallback(response) {
            if (response.data.success == 'ok') {
                window.location.href = 'http://dev.timemanagement.com/#/home';
            }
            else if (response.data.success == 'false') {
                $scope.message = response.data.message;
            }
        }, function errorCallback(response) {
            console.log(response);
        });
    }

    $scope.getEditWork = function () {
        $http({
            method: 'GET',
            url: '/work/' + $routeParams.workid
        }).then(function successCallback(response) {
            console.log(response.data);
            $scope.getIndividualWork = response.data.work;

            $scope.editIndividualWork.work_description = $scope.getIndividualWork.work_description;
            $scope.editIndividualWork.hours = $scope.getIndividualWork.hours;
            $scope.editIndividualWork.date_time = $scope.getIndividualWork.date_time;

        }, function errorCallback(response) {
            console.log(response);
        });
    }

    $scope.editWork = function () {
        $http({
            method: 'PUT',
            url: '/work/' + $routeParams.workid,
            data: $scope.editIndividualWork
        }).then(function successCallback(response) {
            if (response.data.success == 'ok') {
                window.location.href = 'http://dev.timemanagement.com/#/home';
            }
            if (response.data.success == 'failed') {
                $scope.message = response.data.message;
            }
            else {
                console.log(response.data.error);
            }

        }, function errorCallback(response) {
            console.log(response);
        });
    }


    $scope.deleteWork = function (idx, recordId) {

        $scope.listOfWork.splice(idx, 1);

        $http({
            method: 'DELETE',
            url: '/work/' + recordId
        }).then(function successCallback(response) {
            if (response.data.success == 'ok') {
                $scope.workList();
            }

        }, function errorCallback(response) {
            console.log(response);
        });
    }

    $scope.getSettings = function () {
        $http({
            method: 'GET',
            url: '/settings'
        }).then(function successCallback(response) {
            if (response.data.success == 'ok') {
                $scope.getUserSettings = response.data.preffered_hours;
                $scope.editedSettings.perday = $scope.getUserSettings;
            }
        }, function errorCallback(response) {
            console.log(response);
        });
    }

    $scope.setSettings = function () {
        $http({
            method: 'POST',
            url: '/settings',
            data: $scope.editedSettings
        }).then(function successCallback(response) {
            if (response.data.success == 'ok') {
                $scope.message = response.data.message;
            }
            if (response.data.success == 'false') {
                $scope.message = response.data.message;
            }
        }, function errorCallback(response) {
            console.log(response);
        });
    }

    $scope.resetFilter = function () {
        $scope.filter = "";
        $scope.workList();
    }

});