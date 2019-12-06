
/*var combobar = document.getElementById("comboBarLineChart").getContext('2d');
$(document).ready(function() {
    $.ajax({
        url: "api/data_by_year.php",
        method: "GET",
        dataType: 'JSON',
        success: function (data) {
            console.log(data);
            var academic = [];
            var male = [];
            var female = [];

            console.log()
            for (var i in data) {
                academic.push(data[i].academicYear);
                male.push(data[i].maleData);
                female.push(data[i].femaleData);
            }
            var comboBarLineChart = new Chart(combobar, {
                type: 'bar',
                data: {
                    labels: academic,
                    datasets: [{
                        type: 'bar',
                        label: 'Male',
                        backgroundColor: '#059BFF',
                        data: male,
                        borderColor: 'white',
                        borderWidth: 0
                    }, {
                        type: 'bar',
                        label: 'Female',
                        backgroundColor: '#FF6B8A',
                        data: female,
                    }],
                    borderWidth: 1
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    legend: {
                        display: true
                    },
                }
            });

        },
        error: function (data) {
            console.log(data);
        }
    });
});
*/
//For MUM
var combobar = document.getElementById("comboBarLineChart").getContext('2d');
$(document).ready(function() {
    $.ajax({
        url: "api/data_by_school.php",
        method: "GET",
        dataType: 'JSON',
        success: function (data) {
            console.log(data);
            var school = [];
            var male = [];
            var female = [];

            console.log()
            for (var i in data) {
                school.push(data[i].schoolCode);
                male.push(data[i].maleData);
                female.push(data[i].femaleData);
            }
            var comboBarLineChart = new Chart(combobar, {
                type: 'bar',
                data: {
                    labels: school,
                    datasets: [{
                        type: 'bar',
                        label: 'Male',
                        backgroundColor: '#059BFF',
                        data: male,
                        borderColor: 'white',
                        borderWidth: 0
                    }, {
                        type: 'bar',
                        label: 'Female',
                        backgroundColor: '#FF6B8A',
                        data: female,
                    }],
                    borderWidth: 1
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    legend: {
                        display: true
                    },
                }
            });

        },
        error: function (data) {
            console.log(data);
        }
    });
});


//lineChart
var ctx1 = document.getElementById("lineChart").getContext('2d');
$(document).ready(function(){
    $.ajax({
        url: "api/data_by_level.php",
        method: "GET",
        dataType: 'JSON',
        success: function(data) {
            //console.log(data);
            var district = [];
            var male = [];
            var female = [];

            console.log()
            for (var i in data) {
                district.push(data[i].plCode);
                //console.log(data[i].districtName);
                male.push(data[i].maleData);
                female.push(data[i].femaleData);
            }
            var lineChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: district,
                    datasets: [{
                        label: 'Male',
                        backgroundColor: '#00a65a',
                        data: male
                    }, {
                        label: 'Female',
                        backgroundColor: '#00c0ef',
                        data: female
                    }]

                },
                options: {
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    responsive: true,
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }
            });
        },
        error: function(data) {
            console.log(data);
        }
    });
});
//endof lineChart




var ctx2 = document.getElementById("pieChart").getContext('2d');
$(document).ready(function(){
    $.ajax({
        url: "api/data_by_lecturer.php",
        method: "GET",
        dataType: 'JSON',
        success: function(data) {
            //console.log(data);
            var levelName = [];
            var proData= [];

            console.log()
            for (var i in data) {
                levelName.push(data[i].titleName);
                proData.push(data[i].instdata);
            }
            var pieChart = new Chart(ctx2, {
                type: 'pie',
                data: {
                    datasets: [{
                        data: proData,
                        backgroundColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        label: "DataSet1"
                    }],
                    labels: levelName
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true
                    },
                    plugins:
                        {
                            labels: {
                                render:"label"
                            }
                        }
                }

            });
        },
        error: function(data) {
            console.log(data);
        }
    });
});


var ctx3 = document.getElementById("doghoutChart").getContext('2d');
$(document).ready(function(){
    $.ajax({
        url: "api/data_by_time.php",
        method: "GET",
        dataType: 'JSON',
        success: function(data) {
            //console.log(data);
            var emplType = [];
            var emplData= [];

            console.log()
            for (var i in data) {
                emplType.push(data[i].empType);
                emplData.push(data[i].empData);
            }
            var pieChart = new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: emplData,
                        backgroundColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        label: "DataSet1"
                    }],
                    labels: emplType
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true
                    },
                    plugins:
                        {
                            labels: {
                                render:"label"
                            }
                        }
                }

            });
        },
        error: function(data) {
            console.log(data);
        }
    });
});


var ctx4 = document.getElementById("secondPieChart").getContext('2d');
$(document).ready(function(){
    $.ajax({
        url: "api/data_by_sponsor.php",
        method: "GET",
        dataType: 'JSON',
        success: function(data) {
            //console.log(data);
            var sponsorName = [];
            var sponsorData= [];

            console.log()
            for (var i in data) {
                sponsorName.push(data[i].spnName);
                sponsorData.push(data[i].spnData);
            }
            var pieChart = new Chart(ctx4, {
                type: 'pie',
                data: {
                    datasets: [{
                        data: sponsorData,
                        backgroundColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        label: "DataSet1"
                    }],
                    labels: sponsorName
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true
                    }
                },
                plugins: {
                    labels: {
                        render: 'percentage',
                        fontColor: ['green', 'white', 'red'],
                        precision: 2
                    }
                }
            });
        },
        error: function(data) {
            console.log(data);
        }
    });
});