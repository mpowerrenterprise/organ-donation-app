@extends('layouts.dashboard_master_layout')

@section('HeaderAssets')

  <title>IOV - Batches</title>

  <!-- DataTables -->
  <link href="{{asset('assets')}}/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
  <link href="{{asset('assets')}}/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
  <link href="{{asset('assets')}}/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

  <link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="{{asset('assets')}}/css/metismenu.min.css" rel="stylesheet" type="text/css">
  <link href="{{asset('assets')}}/css/icons.css" rel="stylesheet" type="text/css">
  <link href="{{asset('assets')}}/css/style.css" rel="stylesheet" type="text/css">

   <!-- Choices.js CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

   <style>

        /* Set background color for the input field and the multi-select field */
        .choices__inner, .choices__list--multiple .choices__item {
            background-color: #38455c; /* Dark blue-gray color for the input and field */
            border-color: #38455c; /* Border matching the background */
            color: #ffffff; /* White text for better contrast */
        }

        /* Set background color for the dropdown and the hover effect */
        .choices__list--dropdown .choices__item--selectable {
            background-color: #38455c; /* Dark blue-gray for the dropdown */
            color: #ffffff; /* White text in the dropdown */
        }

        /* Change background color of the options on hover */
        .choices__list--dropdown .choices__item--selectable:hover {
            background-color: #2c3b4f; /* Slightly darker shade for hover */
            color: #ffffff; /* White text */
        }

        /* Change the background color and border color of the selected tags */
        .choices__list--multiple .choices__item {
            background-color: #0c25b2; /* Teal green for the tags */
            border: 1px solid #0c25b2; /* Darker teal border for the tags */
            color: #ffffff; /* White text in tags */
        }

        /* Customize the remove (X) button on the tags */
        .choices__list--multiple .choices__button {
            color: #ffffff; /* White color for the remove (X) button */
            background-color: transparent; /* Transparent background for remove button */
        }

        /* Customize the text color for the placeholder */
        .choices__placeholder {
            color: #839496; /* Light grey color for placeholder text */
        }

        /* Customize the text input area inside the dropdown */
        .choices__input {
            background-color: #38455c; /* Dark blue-gray background for the input */
            color: #ffffff; /* White text in the input field */
        }


        /* Change the hover background color for the dropdown items */
        .choices__list--dropdown .choices__item--selectable:hover {
            background-color: #d3d3d3; /* Light gray hover color */
            color: #000000; /* Set a suitable text color */
        }

        /* Adjust the hover effect for selectable items */
        .choices__list--dropdown .choices__item--selectable:hover {
            background-color: #d3d3d3; /* Light gray background on hover */
            color: #38455c; /* Dark text on hover */
        }

        /* Change the background color and text color of the selected item when not hovered */
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #38455c; /* Dark background for selected item */
            color: #000000; /* White text */
        }

    </style>


@endsection

@section('PageContent')

<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="page-title">Batches</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">IOV</a></li>
                    <li class="breadcrumb-item active">Batches</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            @if ($errors->any())
                <div class="alert alert-danger" style="background-color: red; color: white; font-size: 18px; text-align: center;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger" style="background-color: red; color: white; font-size: 18px; text-align: center;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session('error') }}
                </div>
            @endif
            
            @if (session('success'))
                <div class="alert alert-success" style="background-color: green; color: white; font-size: 18px; text-align: center;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Batch Form -->
            <div class="card">
                <div class="card-body">
                    <form id="batchForm" method="POST" action="{{ route('add.batch') }}" enctype="multipart/form-data">
                        @csrf
            
                        <div class="form-group row">
                            <label for="batch_name" class="col-sm-2 col-form-label">Batch Name:</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" placeholder="Enter batch name" id="batch_name" name="batch_name" required>
                            </div>
                        </div>
        
            
                        <div class="form-group row">
                            <label for="course_id" class="col-sm-2 col-form-label">Course:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="course_id" name="course_id" required>
                                    <option value="">Select Course</option>
                                    <!-- Populate this dynamically -->
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
            
                        <div class="form-group row">
                            <label for="start_date" class="col-sm-2 col-form-label">Start Date:</label>
                            <div class="col-sm-4">
                                <input class="form-control" type="date" id="start_date" name="start_date" onclick="this.showPicker()" required>
                            </div>
            
                            <label for="end_date" class="col-sm-2 col-form-label">End Date:</label>
                            <div class="col-sm-4">
                                <input class="form-control" type="date" id="end_date" name="end_date" onclick="this.showPicker()" required>
                            </div>
                        </div>
            
                        <div class="form-group row">
                            <label for="students" class="col-sm-2 col-form-label">Students:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="students" name="students[]" multiple required>
                                    <!-- Prepopulate with existing students (optional) -->
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}({{ $student->student_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
            
                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2 d-flex justify-content-end">
                                <input type="submit" value="Add Batch" class="btn btn-success">
                            </div>
                        </div>                        
            
                    </form>
                </div>
            </div>
            
            <!-- Batches DataTable -->
            <div class="card">
                <div class="card-body">
                    <!-- Filter Dropdown with label and center alignment -->
                    <div class="d-flex justify-content-end mb-3" style="margin-right: 1%;">
                        <label for="statusFilter" class="mr-2 align-self-center" style="font-size: 14px !important;">Filter by Status:</label>
                        <select id="statusFilter" class="form-control" style="width: 16%;">
                            <option value="">All</option> <!-- Default option for all statuses -->
                            <option value="incomplete">Incomplete</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    
                    <!-- DataTable -->
                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Batch No</th>
                                <th>Batch Name</th>
                                <th>Course</th>
                                <th>Student Count</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamically populated rows with batches -->
                        </tbody>
                    </table>
                </div>
            </div>
   
        </div>
    </div>
</div>

@endsection

@section('FooterAssets')

     <!-- jQuery  -->
     <script src="{{asset('assets')}}/js/jquery.min.js"></script>
     <script src="{{asset('assets')}}/js/bootstrap.bundle.min.js"></script>
     <script src="{{asset('assets')}}/js/metismenu.min.js"></script>
     <script src="{{asset('assets')}}/js/waves.min.js"></script>

     <!-- DataTables -->
     <script src="{{asset('assets')}}/plugins/datatables/jquery.dataTables.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/dataTables.bootstrap4.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/dataTables.responsive.min.js"></script>


    <!-- Choices.js JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var studentSelect = new Choices('#students', {
                removeItemButton: true,    // Adds a button to remove selected items
                placeholder: true,         // Enables placeholder support
                placeholderValue: 'Select students or add new ones', // Placeholder text
                searchResultLimit: 10,     // Limits the number of displayed results
                noResultsText: 'No results found',
                noChoicesText: 'No choices to choose from',
                addItems: true,            // Allows users to add new items
                duplicateItemsAllowed: false, // Prevent duplicate selections
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 6000); // 6 seconds
        });
    </script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('process.batches.ajax') }}",
                    "type": "POST",
                    "data": function (d) {
                        d._token = "{{ csrf_token() }}";
                        d.statusFilter = $('#statusFilter').val(); // Send the selected filter to the server
                    }
                },
                "columns": [
                    {
                        "data": null, // Action column
                        "render": function (data, type, row) {
                            var viewUrl = '{{ route("view.batch", ":batch_id") }}';
                            viewUrl = viewUrl.replace(':batch_id', row.id);
                            return '<div class="text-center"><a href="' + viewUrl + '" class="btn btn-sm btn-info">View</a></div>';
                        }
                    },
                    { "data": "batch_no" },       // Batch number column
                    { "data": "batch_name" },     // Batch name column
                    { "data": "course_name" },    // Course name column
                    { "data": "total_students" },    // Course name column
                    { "data": "start_date" },     // Start date column
                    { "data": "end_date" },       // End date column
                    { "data": "status" }          // Status column
                ]
            });
    
            // Event listener for the status filter dropdown
            $('#statusFilter').change(function() {
                table.ajax.reload(); // Reload the table data when filter changes
            });
        });
    </script>
    
    
@endsection
