@extends('layouts.dashboard_master_layout')

@section('HeaderAssets')

    <title>IOV - Batch View</title>

    <link href="{{asset('assets')}}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('assets')}}/css/metismenu.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('assets')}}/css/icons.css" rel="stylesheet" type="text/css">
    <link href="{{asset('assets')}}/css/style.css" rel="stylesheet" type="text/css">

    <style>
        .badge {
            display: inline-block;
            padding: 0.5em;
            font-size: 90%;
            font-weight: 700;
            color: #fff;
            border-radius: 0.25rem;
        }
        .badge-warning {
            background-color: #cf8414; /* Orange for Incomplete */
        }
        .badge-success {
            background-color: #388e3c; /* Green for Completed */
        }
    </style>
    
    <style>
        .custom-swal-popup {
            box-shadow: 0 5px 15px rgba(21, 33, 54, 0.7); 
        }
        .popup {
            display: none;
            position: fixed;
            z-index: 999;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: #12192b; /* A darker shade that complements #293446 */
            box-shadow: 0 5px 15px rgba(21, 33, 54, 0.7); /* Darker shadow to match the background color */
            padding: 20px;
            border-radius: 10px;
            animation: bounceIn 0.5s both ease-out; /* Bounce in animation with ease-out timing function */
        }
        @keyframes bounceIn {
            0% {
                transform: translate(-50%, -50%) scale(0.1);
                opacity: 0;
            }
            60% {
                transform: translate(-50%, -50%) scale(1.2);
            }
            100% {
                transform: translate(-50%, -50%) scale(1);
            }
        }
    </style>


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
                    <div class="page-title-box">
                        <h4 class="page-title">Batch View</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">IOV</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('show.batches') }}">Batches</a>
                            </li>
                            <li class="breadcrumb-item active" style="text-transform: capitalize;">{{ $batch->batch_name }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- end page-title -->
        <div class="row">
            <div class="col-12" style="padding-left:25px; padding-right:25px; padding-top:25px;">

                <!-- Alerts for success or error -->
                @if (session('success'))
                    <div class="alert alert-success" style="background-color: green; color: white; font-size: 18px; text-align: center;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('success') }}
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

                <!-- Batch Details -->
                <div class="card">
                    <div class="card-body">
                        <form id="editBatchForm" method="POST" action="{{ route('edit.batch', ['data' => $batch->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="batch_no" class="col-sm-2 col-form-label">Batch No:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" value="{{ $batch->batch_no }}" id="batch_no" readonly name="batch_no">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="batch_name" class="col-sm-2 col-form-label">Batch Name:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" value="{{ $batch->batch_name }}" id="batch_name" name="batch_name" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="course_name" class="col-sm-2 col-form-label">Course Name:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" value="{{ $batch->course->course_name }}" id="course_name" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="start_date" class="col-sm-2 col-form-label">Start Date:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="date" value="{{ $batch->start_date }}" id="start_date" name="start_date" required onclick="this.showPicker()">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="end_date" class="col-sm-2 col-form-label">End Date:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="date" value="{{ $batch->end_date }}" id="end_date" name="end_date" required onclick="this.showPicker()">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status" class="col-sm-2 col-form-label">Status (To Complete):</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="incomplete" {{ $batch->status == 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                                        <option value="completed" {{ $batch->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2 d-flex justify-content-end">
                                    <input type="submit" value="Save Changes" class="btn btn-info" style="margin-right: 10px;">
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Batch</button>
                                </div>
                            </div>                      
                        </form>
                    </div>
                </div>

                <!-- Enrolled Students Table -->
                <div class="card">
                    <div class="card-body">
                        <h4 style="text-align: center; margin-bottom:30px;">Enrolled Students</h4>

                        <table id="datatable" class="table table-bordered display nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Profile Picture</th> <!-- Profile Picture Column -->
                                    <th>Student Name</th>
                                    <th>Phone Number</th>
                                    <!-- Conditionally render the "Actions" header if the batch is incomplete -->
                                    @if ($batch->status == 'incomplete')
                                        <th>Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($enrolledStudents as $student)
                                    <tr>
                                        <td>{{ $student->student_id }}</td>
                                        
                                        <!-- Profile Picture Column -->
                                        <td>
                                            <div style="text-align: center;">
                                                <img src="{{ route('show.student.image', ['filename' => $student->profile_picture ? $student->profile_picture : 'default.jpg']) }}" 
                                                    style="width: 50px; height: 50px; border-radius: 50%;" alt="Profile Picture">
                                            </div>
                                        </td>
                                        
                                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                        <td>{{ $student->phone_number }}</td>

                                        <!-- Conditionally render the "Remove" button if the batch is incomplete -->
                                        @if ($batch->status == 'incomplete')
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="confirmRemove('{{ route('remove.student.from.batch', ['batch_id' => $batch->id, 'student_id' => $student->id]) }}')">
                                                    Remove
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body">

                        <form id="batchForm" method="POST" action="{{ route('add.students.to.batch', ['batch_id' => $batch->id]) }}" enctype="multipart/form-data">
                            @csrf
                        
                            <h4 style="text-align: center; margin-bottom:30px;">Add Students</h4>
                        
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <select class="form-control" id="students" name="students[]" multiple required>
                                        <!-- Prepopulate with existing students (optional) -->
                                        @foreach($nonEnrolledStudents as $student)
                                            <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2 d-flex justify-content-end">
                                    <input type="submit" value="Add Students" class="btn btn-success">
                                </div>
                            </div>   
                        </form>                        
                        
                    </div>
                </div>

            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>

@endsection

@section('FooterAssets')

    <!-- jQuery  -->
    <script src="{{asset('assets')}}/js/jquery.min.js"></script>
    <script src="{{asset('assets')}}/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('assets')}}/js/metismenu.min.js"></script>
    <script src="{{asset('assets')}}/js/jquery.slimscroll.js"></script>
    <script src="{{asset('assets')}}/js/waves.min.js"></script>

    <script src="{{asset('assets')}}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <!-- App js -->
    <script src="{{asset('assets')}}/js/app.js"></script>

    <script src="{{asset('assets')}}/plugins/sweet-alert2/sweetalert2.all.min.js"></script>

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

    <!-- Confirmation Dialog -->
    <script>
        function confirmDelete() {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                background: "#12192b",
                color: "#fff",
                confirmButtonColor: "#fb4365",
                cancelButtonColor: "#20d4b6",
                confirmButtonText: "Yes, delete it!",
                customClass: {
                    popup: 'custom-swal-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('editBatchForm').setAttribute('action', '{{ route('delete.batch', ['data' => $batch->id]) }}');
                    var methodInput = document.querySelector('input[name="_method"]');
                    if (methodInput) {
                        methodInput.value = 'DELETE';
                    }
                    document.getElementById('editBatchForm').submit();
                }
            });
        }
    </script>

    <script>
        function confirmRemove(url) {
            Swal.fire({
                title: "Are you sure?",
                text: "This student will be removed from the batch.",
                icon: "warning",
                showCancelButton: true,
                background: "#12192b",
                color: "#fff",
                confirmButtonColor: "#fb4365",
                cancelButtonColor: "#20d4b6",
                confirmButtonText: "Yes, remove them!",
                customClass: {
                    popup: 'custom-swal-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the remove student route
                    window.location.href = url;
                }
            });
        }
    </script>

@endsection
