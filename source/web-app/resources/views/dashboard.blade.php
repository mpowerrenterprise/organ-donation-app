@extends('layouts.dashboard_master_layout')

@section('HeaderAssets')

    <title>IOV - Dashboard</title>

    <link href="{{asset('assets')}}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">

    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/morris/morris.css">

    <link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('assets')}}/css/metismenu.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('assets')}}/css/icons.css" rel="stylesheet" type="text/css">
    <link href="{{asset('assets')}}/css/style.css" rel="stylesheet" type="text/css">

@endsection

@section('PageContent')

    <div class="container-fluid">
        <div class="page-title-box">

            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">Dashboard</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Welcome to IOV Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page-title -->

        <!-- start top-Contant -->
        <div class="row">

            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center p-1">
                            <div class="col-lg-12">
                                <h5 class="font-16">Total Students</h5>
                                <h4 class="text-info pt-1 mb-0">{{ $totalStudents }}</h4> <!-- Display total students -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center p-1">
                            <div class="col-lg-12">
                                <h5 class="font-16">Total Courses</h5>
                                <h4 class="text-warning pt-1 mb-0">{{ $totalCourses }}</h4> <!-- Display total courses -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center p-1">
                            <div class="col-lg-12">
                                <h5 class="font-16">Total Batches</h5>
                                <h4 class="text-primary pt-1 mb-0">{{ $totalBatches }}</h4> <!-- Display total batches -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center p-1">
                            <div class="col-lg-12">
                                <h5 class="font-16">Current Active Batches</h5>
                                <h4 class="text-primary pt-1 mb-0">{{ $activeBatches }}</h4> <!-- Display active batches -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>

        <div class="card">
            <div class="card-body">
                <h4 style="text-align: left; margin-top:15px; margin-bottom:30px;">Latest Registered Students</h4> <!-- Updated title -->
                <table id="datatable" class="table table-bordered display nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Profile</th>
                            <th>Address</th>
                            <th>Phone Number</th>
                            <th>DOB</th> <!-- Date of Birth Column -->
                            <th>Gender</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentStudents as $student)
                            <tr>
                                <td>
                                    <a href="{{ route('view.student', ['id' => $student->id]) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td>
                                    <img src="{{ route('show.student.image', ['filename' => $student->profile_picture ? $student->profile_picture : 'default.jpg']) }}" 
                                         style="width: 50px; height: 50px; border-radius: 50%;" alt="Profile Picture">
                                </td>
                                <td>{{ $student->address }}</td>
                                <td>{{ $student->phone_number }}</td>
                                <td>{{ $student->date_of_birth }}</td>
                                <td>{{ ucfirst($student->gender) }}</td> <!-- Capitalize the gender -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        

@endsection


@section('FooterAssets')

 <!-- jQuery  -->
 <script src="{{asset('assets')}}/js/jquery.min.js"></script>
 <script src="{{asset('assets')}}/js/bootstrap.bundle.min.js"></script>
 <script src="{{asset('assets')}}/js/metismenu.min.js"></script>
 <script src="{{asset('assets')}}/js/jquery.slimscroll.js"></script>
 <script src="{{asset('assets')}}/js/waves.min.js"></script>

 <script src="{{asset('assets')}}/plugins/apexchart/apexcharts.min.js"></script>
 <script src="{{asset('assets')}}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

 <!--Morris Chart-->
 <script src="{{asset('assets')}}/plugins/morris/morris.min.js"></script>
 <script src="{{asset('assets')}}/plugins/raphael/raphael.min.js"></script>

 <script src="{{asset('assets')}}/plugins/chartjs/chart.min.js"></script>


 <script src="{{asset('assets')}}/pages/chartjs.init.js"></script>
 <script src="{{asset('assets')}}/pages/dashboard.init.js"></script>

 <!-- App js -->
 <script src="{{asset('assets')}}/js/app.js"></script>

@endsection