@extends('layouts.dashboard_master_layout')

@section('HeaderAssets')

    <title>IOV - Customer View</title>

    <link href="{{asset('assets')}}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">

    <link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('assets')}}/css/metismenu.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('assets')}}/css/icons.css" rel="stylesheet" type="text/css">
    <link href="{{asset('assets')}}/css/style.css" rel="stylesheet" type="text/css">
    
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

@endsection

@section('PageContent')

    <div class="container-fluid">
        <div class="page-title-box">

            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">Course View</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">IOV</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('show.courses') }}">Course</a>
                            </li>
                            <li class="breadcrumb-item active" style="text-transform: capitalize;">{{$course->course_name}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page-title -->

        <div class="row">
            
            <div class="col-12" style="padding-left:25px; padding-right:25px; padding-top:25px;">
                
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

                <div class="card">

                    <div class="card-body">

                        <form id="editCourseForm" method="POST" action="{{ route('edit.course', ['data' => urlencode(json_encode(['id' => $course->id]))]) }}" enctype="multipart/form-data" style="margin-top: 25px;">
                            @csrf
                            @method('PUT')
                        
                            <!-- ID (Read Only) -->
                            <div class="form-group row">
                                <label for="id" class="col-sm-2 col-form-label">Course ID:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" value="{{ $course->id }}" id="id" readonly required name="id">
                                </div>
                            </div>
                        
                            <!-- Course Name -->
                            <div class="form-group row">
                                <label for="course_name" class="col-sm-2 col-form-label">Course Name:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" value="{{ $course->course_name }}" id="course_name" required name="course_name">
                                </div>
                            </div>
                            
                            <!-- Course Description -->
                            <div class="form-group row">
                                <label for="course_description" class="col-sm-2 col-form-label">Course Description:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="course_description" name="course_description" rows="3">{{ $course->course_description }}</textarea>
                                </div>
                            </div>
                            
                            <!-- Duration -->
                            <div class="form-group row">
                                <label for="duration" class="col-sm-2 col-form-label">Duration:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" value="{{ $course->duration }}" id="duration" name="duration" required>
                                </div>
                            </div>
                            
                            <!-- Fee -->
                            <div class="form-group row">
                                <label for="fee" class="col-sm-2 col-form-label">Fee:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" value="{{ $course->fee }}" id="fee" name="fee" required>
                                </div>
                            </div>
                        
                            <!-- Submit and Delete Buttons -->
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2 d-flex justify-content-end">
                                    <input type="submit" value="Save Changes" class="btn btn-info" style="margin-right: 10px;">
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
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


    <script>
       // Function to show the SweetAlert confirmation dialog
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
                    // Get the course ID to delete
                    const courseId = '{{ $course->id }}'; // Assuming $course is available in your blade template

                    // Update the form's action URL to use the correct route with the ID
                    document.getElementById('editCourseForm').setAttribute('action', '{{ url("/courses/delete") }}/' + courseId);
                
                    // Update the existing _method input field to DELETE if it's not already there
                    var methodInput = document.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        // Create the input if it doesn't exist
                        methodInput = document.createElement('input');
                        methodInput.setAttribute('type', 'hidden');
                        methodInput.setAttribute('name', '_method');
                        methodInput.setAttribute('value', 'DELETE');
                        document.getElementById('editCourseForm').appendChild(methodInput);
                    } else {
                        methodInput.value = 'DELETE'; // Update to DELETE method if it's already present
                    }

                    // Submit the form to delete the course
                    document.getElementById('editCourseForm').submit();
                }
            });
        }

    </script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 6000); // 6 seconds
        });
    </script>

@endsection
