<template>
<section>
 <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">Dashboard</h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                 <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
     <div class="contentbar">
                <!-- Start row -->
                <div class="row">
                    <!-- Start col -->
                    <div class="col-lg-12 col-xl-6">
                        <!-- Start row -->
                        <div class="row">
                            <!-- Start col -->
                            <div class="col-lg-12">
                                <div class="card m-b-30">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                            <div class="col-9">
                                                <h5 class="card-title mb-0">Sales Analysis</h5>
                                                <span>Total Amount: <span id="amount" style="font-weight:bolder;">0</span></span> &nbsp;<span>Total Product: <span id="product" style="font-weight:bolder;">0</span></span>
                                            </div>
                                            <div class="col-3">
                                                 <select class="form-control" style="padding: 0;" name="salesanalysis" id="salesanalysis" ref="salesanalysis" v-on:change="salesanalysis($event.target.value)">
                                                     <option value="today">Today</option>
                                                     <option value="month">This Month</option>
                                                     <option value="year">This Year</option>
                                                 </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <div class="row align-items-center">
                                            <div class="col-lg-12">
                                                <div id="apex-line-chartspinner">
                                                    <i class="fa-spinners loader-spin-3 fa-3x fa-spin"></i>
                                                </div>
                                                <div id="apex-line-chart"></div>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End row -->
                    </div>
                    <!-- End col -->
                    <!-- Start col -->
                    <div class="col-lg-12 col-xl-6">
                        <div class="card m-b-30 dash-widget">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-9">
                                        <h5 class="card-title mb-0">Stock</h5>
                                        <span>Total : <span id="totalc" style="font-weight:bolder;">0</span></span> <span> Open : <span id="open" style="font-weight:bolder;">0</span></span> <span> Close : <span id="close" style="font-weight:bolder;">0</span></span>
                                    </div>
                                   <div class="col-3">
                                               <select class="form-control" style="padding: 0;" name="complaintanalysis" id="complaintanalysis" ref="complaintanalysis" v-on:change="complaintanalysis($event.target.value)">
                                                     <option value="today">Today</option>
                                                     <option value="month">This Month</option>
                                                     <option value="year">This Year</option>
                                                 </select>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-0 pl-0 pr-2">
                                <div id="apex-bar-chart"></div>
                            </div>
                        </div>
                    </div>
                    <!-- End col -->
                </div>
                <!-- End row -->
                <!-- Start row -->
                <div class="row">
                    <!-- Start col -->
                    <div class="col-lg-12 col-xl-12">
                        <!-- Start row -->
                        <div class="row">
                            <!-- Start col -->
                            <div class="col-lg-6 col-xl-6">
                                <div class="card m-b-30">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                    <div class="col-9">
                                        <h5 class="card-title mb-0">Payment Analysis</h5>
                                        <span>Total : <span id="totalReg" style="font-weight:bolder;">0</span></span>
                                    </div>
                                   <div class="col-3">
                                               <select class="form-control" style="padding: 0;" name="registrationtanalysis" id="registrationtanalysis" ref="registrationtanalysis" v-on:change="registrationtanalysis($event.target.value)">
                                                     <option value="today">Today</option>
                                                     <option value="month">This Month</option>
                                                     <option value="year">This Year</option>
                                                 </select>
                                       
                                    </div>
                                </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="apex-pie-chart"></div>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-6">
                                <div class="card m-b-30">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                    <div class="col-9">
                                        <h5 class="card-title mb-0">Raw Material</h5>
                                        <span>Total : <span id="totalflash" style="font-weight:bolder;">0</span></span>
                                    </div>
                                   <div class="col-3">
                                               <select class="form-control" style="padding: 0;" name="flashanalysis" id="flashanalysis" ref="flashanalysis" v-on:change="flashanalysis($event.target.value)">
                                                     <option value="today">Today</option>
                                                     <option value="month">This Month</option>
                                                     <option value="year">This Year</option>
                                                 </select>
                                       
                                    </div>
                                </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="chart"></div>
                                        
                                    </div>
                                </div>
                            </div>

                             <div class="col-lg-6 col-xl-6">
                                 <h5>Recent Activities</h5>
                                <div class="card m-b-30">
                                    
                                    <div class="card-body">
                                        <div class="media" style="align-items:center;">
                                            <span class="align-self-center mr-3 action-icon badge badge-secondary-inverse"><i class="fa fa-android"></i></span>
                                            <div class="media-body">
                                                <p class="mb-0" style="    color: #464646;" id="diagnosticDashboard"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                             <div class="col-lg-6 col-xl-6" style="    margin-top: 30px;">
                                <div class="card m-b-30">
                                    
                                    <div class="card-body">
                                        <div class="media" style="align-items:center;">
                                            <span class="align-self-center mr-3 action-icon badge badge-secondary-inverse"><i class="fa fa-file-text-o"></i></span>
                                            <div class="media-body">
                                                <p class="mb-0" style="    color: #464646;" id="dashboardHex"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="col-lg-6 col-xl-6">
                                <div class="card m-b-30">
                                    
                                    <div class="card-body">
                                        <div class="media" style="align-items:center;">
                                            <span class="align-self-center mr-3 action-icon badge badge-secondary-inverse"><i class="fa fa-bell-o"></i></span>
                                            <div class="media-body">
                                                <p class="mb-0" style="    color: #464646;">TVS Auto will be launch soon.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- End col -->
                            <!-- Start col -->
                            
                        
                        
                            
                        </div>
                        <!-- End row -->
                    </div>
                    <!-- End col -->
                    <!-- Start col -->
                
                    <!-- End col -->
                </div>
                <!-- End row -->
            </div>
</section>
</template>
<style>


</style>
<script>
import moment from 'moment';
    export default {
         data () {
            return {
                editmode: false,
                dashboard : {},
                // salesanalysis : '',
            }
        },
         methods: {

           salesanalysis(names){
                    var datas = {
                        duration : names
                    }
                axios.post('/api/salesAnalysis', datas)
                    .then(function (response) {
                        // $('#apex-line-chartspinner').
                        $('#amount').html(response.data.data.totalamount.toFixed(2));
                        $('#product').html(response.data.data.totalvci);
                        var options = {
                                    chart: {
                                        height: 362,
                                        type: 'area',
                                        toolbar: {
                                            show: false
                                        },
                                        zoom: {
                                            type: 'x',
                                            enabled: false,
                                            autoScaleYaxis: true
                                        },
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    stroke: {
                                        curve: 'smooth',
                                    },
                                    colors: ['#0080ff', '#d4d8de'],
                                    series: [{
                                            name: 'Amount',
                                            data: response.data.data.amount
                                        },
                                        {
                                            name: 'Product',
                                            data: response.data.data.count
                                        }
                                    ],
                                    legend: {
                                        show: false,
                                    },
                                    xaxis: {
                                        categories: response.data.data.time,
                                        axisBorder: {
                                            show: true,
                                            color: 'rgba(0,0,0,0.05)'
                                        },
                                        axisTicks: {
                                            show: true,
                                            color: 'rgba(0,0,0,0.05)'
                                        }
                                    },
                                    grid: {
                                        row: {
                                            colors: ['transparent', 'transparent'],
                                            opacity: .2
                                        },
                                        borderColor: 'rgba(0,0,0,0.05)'
                                    },
                                    tooltip: {
                                        x: {
                                            format: 'dd/MM/yy HH:mm'
                                        },
                                    },
                                    noData: {
                                        text: 'Loading...'
                                    }
                                }
                                var chart = new ApexCharts(
                                    document.querySelector("#apex-line-chart"),
                                    options
                                );
                               chart.render();
                               chart.updateSeries(
                                  [{
                                            name: 'Amount',
                                            data: response.data.data.amount
                                        },
                                        {
                                            name: 'Product',
                                            data: response.data.data.count
                                        }
                                    ],
                               );                               
                       
                    })
                    .catch(function (err) {
                         Toast.fire({
                            icon: 'error',
                            title: 'Some error occured! Please try again'
                        });
                    });
                
            },
            complaintanalysis(names){
                    var datas = {
                        duration : names
                    }
                axios.post('/api/complaintAnalysis', datas)
                    .then(function (response) {
                        // $('#apex-line-chartspinner').
                        $('#totalc').html(response.data.data.tot);
                        $('#open').html(response.data.data.openTot);
                         $('#close').html(response.data.data.closeTot);
                         var options = {
                                chart: {
                                    height: 362,
                                    type: 'bar',
                                    toolbar: {
                                        show: false
                                    },
                                },
                                plotOptions: {
                                    bar: {
                                        horizontal: false,
                                        columnWidth: '25%',
                                        endingShape: 'rounded'
                                    },
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                    show: true,
                                    width: 2,
                                    colors: ['transparent']
                                },
                                colors: ['#de3f2c', '#157d02'],
                                series: [ {
                                    name: 'Open',
                                    data: response.data.data.open
                                }, {
                                    name: 'Close',
                                    data: response.data.data.close
                                }],
                                legend: {
                                    show: true,
                                },

                                xaxis: {
                                    categories: response.data.data.time,
                                    axisBorder: {
                                        show: true,
                                        color: 'rgba(0,0,0,0.05)'
                                    },
                                    axisTicks: {
                                        show: true,
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                grid: {
                                    row: {
                                        colors: ['transparent', 'transparent'],
                                        opacity: .2
                                    },
                                    borderColor: 'rgba(0,0,0,0.05)'
                                },
                                fill: {
                                    opacity: 1,
                                },
                                tooltip: {
                                    y: {
                                        formatter: function(val) {
                                            return  val + " Complaints"
                                        }
                                    }
                                }
                            }
                            var chart = new ApexCharts(
                                document.querySelector("#apex-bar-chart"),
                                options
                            );
                            chart.render();
                                chart.updateSeries(
                                  [ {
                                    name: 'Open',
                                    data: response.data.data.open
                                }, {
                                    name: 'Close',
                                    data: response.data.data.close
                                }]
                               );  
                       
                    })
                    .catch(function (err) {
                         Toast.fire({
                            icon: 'error',
                            title: 'Some error occured! Please try again'
                        });
                    });
                
            },
             registrationtanalysis(names){
                    var datas = {
                        duration : names
                    }
                axios.post('/api/registrationanalysis', datas)
                    .then(function (response) {
                        // $('#apex-line-chartspinner').
                        $('#totalReg').html(response.data.data.tot);
                        var options = {
                            series: [{
                                name: 'Registration',
                                data: response.data.data.customer
                            }],
                            chart: {
                                type: 'bar',
                                height: 330
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 4,
                                    horizontal: true,
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            xaxis: {
                                categories: response.data.data.time,
                            }
                        };

                        var chart = new ApexCharts(document.querySelector("#apex-pie-chart"), options);
                        chart.render();
                         chart.updateSeries(
                                  [{
                                name: 'Registration',
                                data: response.data.data.customer
                            }]
                               );
                       
                    })
                    .catch(function (err) {
                         Toast.fire({
                            icon: 'error',
                            title: 'Some error occured! Please try again'
                        });
                    });
                
            },
             flashanalysis(names){
                    var datas = {
                        duration : names
                    }
                axios.post('/api/flashanalysis', datas)
                    .then(function (response) {
                        // $('#apex-line-chartspinner').
                        $('#totalflash').html(response.data.data.tot);
                       var options = {
                            colors: ['#02b9a0'],
                            series: [{
                                data: response.data.data.flash
                            }],
                            chart: {
                                type: 'bar',
                                height: 330,
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    dataLabels: {
                                        position: 'top',
                                    },
                                }
                            },
                            dataLabels: {
                                enabled: true,
                                offsetX: -6,
                                style: {
                                    fontSize: '12px',
                                    colors: ['#fff']
                                }
                            },
                            stroke: {
                                show: true,
                                width: 1,
                                colors: ['#fff']
                            },
                            tooltip: {
                                shared: true,
                                intersect: false
                            },
                            xaxis: {
                                categories: response.data.data.time,
                            },
                        };

                        var chart = new ApexCharts(document.querySelector("#chart"), options);
                        chart.render();
                       chart.updateSeries(
                                   [{
                                data: response.data.data.flash
                            }]
                               );
                    })
                    .catch(function (err) {
                         Toast.fire({
                            icon: 'error',
                            title: 'Some error occured! Please try again'
                        });
                    });
                
            },
             dashboardData(){
                axios.get('/api/dashboardData')
                    .then(function (response) {
                        if(response.data.data.app){
                        var appDate = moment(response.data.data.app.approved_date).format("DD-MM-YYYY");
                        $('#diagnosticDashboard').html('Diagnostic application '+response.data.data.app.version+' received '+appDate);
                        }else{
                        $('#diagnosticDashboard').html('No Data Found');
                        }
                       
                        if(response.data.data.hex){
                        var hexDate = moment(response.data.data.hex.approved_date).format("DD-MM-YYYY");
                        $('#dashboardHex').html(response.data.data.hex.name.replace('_',' ')+' bike HEX file '+response.data.data.hex.hex_file+' approved '+hexDate);
                        }else{
                            $('#dashboardHex').html('No Data Found');
                        }
                       
                    })
                    .catch(function (err) {
                         Toast.fire({
                            icon: 'error',
                            title: 'Some error occured! Please try again'
                        });
                    });
                
            },
         },
        mounted() {
            console.log('Component mounted.')
        },
        created() {

             
            this.salesanalysis('today'); 
            this.complaintanalysis('today');
            this.registrationtanalysis('today');
            this.flashanalysis('today');
            this.dashboardData();
             
        }
    }
</script>
