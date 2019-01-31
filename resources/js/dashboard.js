var path = window.location.pathname;

if (path.indexOf("/dashboard") != -1) {

    // Load the Visualization API, corechart and bar packages.
    google.charts.load('current', {'packages': ['corechart', 'bar']});

    // Load the charts dynamically
    $.get('load-dashboard', function (data) {
        $.each(data, function (chart, values) {
            var chartFunction = eval('google.charts.setOnLoadCallback(function(){' + chart + '_chart(values)})');
            chartFunction;
        });
    }, "json");



    function domestic_vs_international_chart(values) {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Traffic');
        data.addColumn('number', 'Shipments');
        data.addRows([
            ['Domestic', values.domestic],
            ['International', values.international],
        ]);

        // Set chart options
        var options = {
            height: 160,
            width: 180,
            pieHole: 0.7,
            legend: {position: 'none'},
            pieSliceText: 'none',
            chartArea: {
                width: "90%",
                height: "90%"
            },
            sliceVisibilityThreshold: 0,
            colors: ['#8EC9D7', '#EEEEEE'],
            fontSize: 14
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('domestic_vs_international_chart'));
        chart.draw(data, options);
    }

    function shipments_chart(values) {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Status');
        data.addColumn('number', 'Shipments');

        $.each(values, function (key, value) {
            data.addRow([key, value]);
        });

        // Set chart options
        var options = {
            height: 160,
            width: 180,
            pieHole: 0.7,
            legend: {position: 'none'},
            pieSliceText: 'none',
            chartArea: {
                width: "90%",
                height: "90%"
            },
            sliceVisibilityThreshold: 0,
            colors: ['#8EC9D7', '#EEEEEE'],
            fontSize: 14
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('shipments_chart'));
        chart.draw(data, options);
    }


    function carriers_chart(values) {

        var data = new google.visualization.DataTable();

        data.addColumn('string', 'Carrier');
        data.addColumn('number', 'Shipments');


        var carrierColors = {FedEx: ['#4D148C'], DHL: ['#FFCC00'], UPS: ['#351C15'], TNT: ['#FF6200'], IFS: ['#2B395B'], Other: ['#EC1F26']};
        var colors = [];

        $.each(values, function (key, value) {
            data.addRow([key, value]);
            alert(carrierColors.key[0]);
            colors.push(carrierColors.key[0]);
        });


        // Set chart options
        var options = {
            height: 160,
            width: 180,
            pieHole: 0.7,
            legend: {position: 'none'},
            pieSliceText: 'none',
            chartArea: {
                width: "90%",
                height: "90%"
            },
            sliceVisibilityThreshold: 0,
            colors: colors,
            fontSize: 14
        };


        var chart = new google.visualization.PieChart(document.getElementById('carriers_chart'));
        chart.draw(data, options);
    }


    function top_5_destinations_chart(values) {
        var data = new google.visualization.DataTable();

        data.addColumn('string', 'Destination');
        data.addColumn('number', 'Shipments');

        $.each(values, function (key, value) {
            data.addRow([key, value]);
        });

        var options = {
            legend: {position: 'none'},
            chartArea: {width: 400},
            hAxis: {minValue: 0}
        };

        var chart = new google.visualization.BarChart(document.getElementById('top_5_destinations_chart'));
        chart.draw(data, options);
    }


    function daily_stats_chart(values) {

        var data = new google.visualization.DataTable();

        data.addColumn('string', 'Date');
        data.addColumn('number', 'Shippers');
        data.addColumn('number', 'Shipments');

        $.each(values, function (key, value) {
            data.addRow([key, value.shippers, value.shipments]);
        });

        var options = {
            isStacked: true,
            chartArea: {
                width: "90%",
                height: "90%"
            },
            legend: {position: 'none'},
            colors: ['#2677B5', '#7CB57C'],
            vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('daily_stats_chart'));
        chart.draw(data, options);
    }






}