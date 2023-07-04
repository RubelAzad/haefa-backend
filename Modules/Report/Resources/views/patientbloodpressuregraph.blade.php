@extends('layouts.app')

@section('title')
{{ $page_title }}
@endsection

@push('stylesheet')
<style>
{
        {
        -- pagination style--
    }
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    list-style: none;
    padding: 0;
}

.pagination li {
    margin: 0 5px;
}

.pagination .active {
    font-weight: bold;
    color: #000;
}

.pagination a {
    color: #007bff;
    text-decoration: none;
    padding: 5px 10px;
    border: 1px solid #007bff;
    border-radius: 5px;
}

.pagination a:hover {
    background-color: #007bff;
    color: #fff;
}


    {
        {
        --pagination style ends--
    }
}

#prescription .container {
    background-color: #f2f2f2 !important;
}

.header p {
    font-size: 14px;
}

.aside {
    width: 400px;
    border-right: 1px solid #ddd;
    min-height: 600px;
    padding-bottom: 20px;
}

.signatureImage {
    display: inline-block;
    width: 100px;
    object-fit: contain;
    margin-bottom: 5px;
}

.signatureBox {
    position: absolute;
    right: 50px;
    bottom: 30px;
}

.footer {
    padding-top: 20px;
    padding-bottom: 20px;
    border-top: 1px solid #ddd;
}

.footer p {
    font-size: 14px;
}

.apiLogo {
    max-width: 40px;
    transform: translateY(-4px);
    margin-left: 5px;
}

.logoText {
    font-size: 14px;
}

.nextinfo {
    margin-top: 150px;
}

.userImg {
    margin-top: 20px;
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 20px;
    border: 10px solid rgba(122, 122, 122, .15);
}

.dataItem p {
    font-weight: 400;
    font-size: 15px;
}

.dataItem span {
    font-weight: 600;
    font-size: 15px;
}

@media (max-width: 767px) {

    #prescription,
    .logoText,
    address p,
    .header p {
        font-size: 12px !important;
    }

    .header h4 {
        font-size: 18px !important;
    }

    .patientageLeftSide {
        width: 100% !important;
        min-height: auto !important;
        border: 0 !important;
    }

    .itemMerge {
        flex-direction: column;
    }

    .patientageLeftSide h5 {
        font-size: 18px !important;
    }

    .userImg {
        width: 140px !important;
        height: 140px !important;
        border-width: 5px;
    }

    .patientageRightSide .dataItem p,
    .patientageRightSide .dataItem span,
    .patientageLeftSide p {
        margin-bottom: 0;
        font-size: 14px;
    }

    .patientageRightSide .dataItem h5 {
        font-size: 16px !important;
        margin-bottom: 5px !important;
    }

    .patientageRightSide {
        padding: 10px 10px !important;
    }

    .patientageRightSide .dataItem {
        margin-top: 15px !important;
    }

}
</style>
@endpush

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

@section('content')
<div class="dt-content">

    <!-- Grid -->
    <div class="row">
        <div class="col-xl-12 pb-3">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="active breadcrumb-item">{{ $sub_title }}</li>
            </ol>
        </div>
        <!-- Grid Item -->
        <div class="col-xl-12">

            <!-- Entry Header -->
            <div class="dt-entry__header">

                <!-- Entry Heading -->
                <div class="dt-entry__heading">
                    <h2 class="dt-page__title mb-0 text-primary"><i class="{{ $page_icon }}"></i> {{ $sub_title }}</h2>
                </div>
                <!-- /entry heading -->
                @if (permission('patientage-add'))
                <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New patientage','Save')">
                    <i class="fas fa-plus-square"></i> Add New
                </button>
                @endif


            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter" method="GET" action="{{url('patient-blood-pressure-graph')}}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="name">Date From</label>
                                <input type="date" class="form-control" value="<?php echo $_GET['starting_date']??'' ?>" name="starting_date" id="starting_date"
                                    placeholder="Date From">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="name">Date To </label>
                                <input type="date" class="form-control" value="<?php echo $_GET['ending_date']??'' ?>" name="ending_date" id="ending_date"
                                    placeholder="Date To">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="name">RegistrationId</label>
                                <input type="text" class="form-control" value="<?php echo $_GET['registration_id']??'' ?>" name="registration_id" id="registration_id"
                                    placeholder="RegistrationId">
                            </div>

                            <div class="form-group col-md-2 pt-24">

                                <button type="submit" class="btn btn-primary btn-sm float-right mr-2">
                                    <i class="fas fa-search"></i>
                                </button>
                                
                                <a href="{{url('patient-blood-pressure-graph')}}" class="btn btn-primary btn-sm float-right mr-2 refresh">
                                <i class="fas fa-sync-alt"></i></a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <figure class="highcharts-figure">
                                    <div id="container"></div>
                                </figure>
                            </div>
                        </div>
                    </form>



                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->

        </div>
        <!-- /grid item -->

    </div>
    <!-- /grid -->

</div>
@endsection

@push('script')
<script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
<script>
$(document).ready(function() {});
var BPSystolic1Numeric = {!! json_encode($BPSystolic1Numeric) !!};

var BPDiastolic1Numeric = {!! json_encode($BPDiastolic1Numeric) !!};

var BPSystolic2Numeric = {!! json_encode($BPSystolic2Numeric) !!};

var BPDiastolic2Numeric = {!! json_encode($BPDiastolic2Numeric) !!};

var BPSystolic1 = {!! json_encode($BPSystolic1) !!};

var BPDiastolic1 = {!! json_encode($BPDiastolic1) !!};

var BPSystolic2 = {!! json_encode($BPSystolic2) !!};

var BPDiastolic2 = {!! json_encode($BPDiastolic2) !!};


Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Patient Blood Pressure'
    },
    xAxis: {

        categories: {!! json_encode($DistinctDate) !!},

        // categories: ['Jan 2003', 'Feb 2003', 'Mar', 'Apr', 'May', 'Jun',
        //     'Jul'
        // ],

        accessibility: {
            description: 'Months of the year'
        },
        labels: {
            style: {
                fontSize: '12px'
            }
        }
    },
    yAxis: {
        title: {
            text: 'Blood ressure '
        },
        labels: {
            style: {
                fontSize: '12px'
            }
        }
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },

    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            },
            dataLabels: {
                enabled: true,
                formatter: function() {
                    var index = this.point.index;
                    var bpsData1 = BPSystolic1[index];
                    var bpdData1 = BPDiastolic1[index];
                    var bpsData2 = BPSystolic2[index];
                    var bpdData2 = BPDiastolic2[index];

                    var label1 = '(' + bpsData1 + ', ' + bpdData1 + ')';
                    var label2 = '(' + bpsData2 + ', ' + bpdData2 + ')';

                    if (this.series.index === 0 || this.series.index === 1) {
                        return label1;
                    } else if (this.series.index === 2 || this.series.index === 3) {
                        return label2;
                    }
                },
                style: {
                    fontSize: '12px'
                }
            }
        }
    },
    series: [{
            name: 'BPSystolic1',
            marker: {
                symbol: 'square'
            },
            // data: [5.22, 5.7, 8.7, 13.9, 18.2, 21.4, 1.0]
            data: <?php echo $BPSystolic1Numeric; ?>
        },
        {
            name: 'BPDiastolic1',
            marker: {
                symbol: 'square'
            },
            data: <?php echo $BPDiastolic1Numeric; ?>
        },
        {
            name: 'BPSystolic2',
            marker: {
                symbol: 'square'
            },
             data: <?php echo $BPSystolic2Numeric; ?>
        },
        {
            name: 'BPDiastolic2',
            marker: {
                symbol: 'square'
            },
             data: <?php echo $BPDiastolic2Numeric; ?>
        }
    ]

});
</script>
@endpush