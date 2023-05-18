/*
----------------------------------------------
    : Custom - Dashboard CRM js :
----------------------------------------------
*/
"use strict";
$(document).ready(function() {
    /* -----  Apex Line Chart ----- */

    /* -----  Apex Bar Chart ----- */

    /* Apex Pie Chart */

    /* Apex Radial Chart */
    var options = {
        chart: {
            height: 250,
            type: 'radialBar',
        },
        plotOptions: {
            radialBar: {
                dataLabels: {
                    name: {
                        fontSize: '18px',
                        fontFamily: 'Muli',
                    },
                    value: {
                        fontSize: '16px',
                        fontFamily: 'Muli',
                    },
                    total: {
                        show: true,
                        label: 'Total',
                        formatter: function(w) {
                            return 249
                        }
                    }
                }
            }
        },
        colors: ['#0080ff', '#18d26b', '#ffa800', '#d4d8de'],
        series: [44, 55, 67, 83],
        labels: ['News', 'Media', 'Ads', 'Others'],
    }
    var chart = new ApexCharts(
        document.querySelector("#apex-radial-chart"),
        options
    );
    chart.render();




    var options = {
        series: [44, 55, 41, 17, 15],
        chart: {
            type: 'donut',
            height: 350,
        },
        labels: ['Diesel', 'Petrol', 'CNG', 'LPG', 'EV'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'top'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#chart1"), options);
    chart.render();


    /* -- User Slider -- */
    $('.user-slider').slick({
        arrows: true,
        dots: false,
        infinite: true,
        adaptiveHeight: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: '<i class="feather icon-chevron-left"></i>',
        nextArrow: '<i class="feather icon-chevron-right"></i>'
    });
});