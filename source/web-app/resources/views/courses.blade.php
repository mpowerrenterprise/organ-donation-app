@extends('layouts.dashboard_master_layout')

@section('HeaderAssets')

  <title>IOV - Customers</title>

  <!-- DataTables -->
  <link href="{{asset('assets')}}/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
  <link href="{{asset('assets')}}/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
  <link href="{{asset('assets')}}/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

  <link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="{{asset('assets')}}/css/metismenu.min.css" rel="stylesheet" type="text/css">
  <link href="{{asset('assets')}}/css/icons.css" rel="stylesheet" type="text/css">
  <link href="{{asset('assets')}}/css/style.css" rel="stylesheet" type="text/css">

  <style>
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

    <style>

        #datatable tbody tr:hover{
            background-color: #3f4f69; /* Change the background color on hover */
            cursor: pointer; /* Change the cursor to pointer to indicate the row is clickable */
        }

    </style>

@endsection

@section('PageContent')

<div class="container-fluid">
    <div class="page-title-box">

        <div class="row align-items-center ">
          <div class="col-md-8">
            <div class="page-title-box">
                <h4 class="page-title">Courses</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a>IOV</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">Courses</a>
                    </li>
                </ol>
            </div>

        </div>
    </div>
    <!-- end page-title -->

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

            <div class="card">
                <div class="card-body">
                    <form id="customerForm" method="POST" action="{{ route('add.course') }}" enctype="multipart/form-data">
                        @csrf

                        <div style="margin-top: 20px; padding-bottom:50px;">
                            <!-- Course Name -->
                            <div class="form-group row justify-content-center">
                                <label for="course_name" class="col-sm-2 col-form-label">Course Name:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" placeholder="Enter course name" id="course_name" name="course_name" required>
                                </div>
                            </div>
                        
                            <!-- Course Description -->
                            <div class="form-group row justify-content-center">
                                <label for="course_description" class="col-sm-2 col-form-label">Course Description:</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" placeholder="Enter course description" id="course_description" name="course_description" rows="3"></textarea>
                                </div>
                            </div>
                        
                            <!-- Duration -->
                            <div class="form-group row justify-content-center">
                                <label for="duration" class="col-sm-2 col-form-label">Duration:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" placeholder="Enter duration (e.g. 6 months)" id="duration" name="duration" required>
                                </div>
                            </div>
                        
                            <!-- Course Fee and Button in one row -->
                            <div class="form-group row justify-content-center">
                                <label for="fee" class="col-sm-2 col-form-label">Fee:</label>
                                <div class="col-sm-6">
                                    <input class="form-control" type="text" placeholder="Enter fee (e.g. 1500.00)" id="fee" name="fee" required>
                                </div>
                                <div class="col-sm-2 d-flex justify-content-end">
                                    <input type="submit" value="Add Course" class="btn btn-success">
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <table id="datatable" class="table table-bordered display nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Action</th> <!-- For edit/delete buttons or other actions -->
                                <th>Course ID</th> <!-- Unique Course Identifier -->
                                <th>Course Name</th> <!-- Name of the Course -->
                                <th>Course Description</th> <!-- Description of the Course -->
                                <th>Duration</th> <!-- Duration of the Course -->
                                <th>Fee</th> <!-- Course Fee -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- You can dynamically generate rows using a backend framework (e.g., Blade in Laravel, or JS for frontend rendering) -->
                        </tbody>
                    </table>
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
 
     <!-- Required datatable js -->
     <script src="{{asset('assets')}}/plugins/datatables/jquery.dataTables.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/dataTables.bootstrap4.min.js"></script>
     <!-- Buttons examples -->
     <script src="{{asset('assets')}}/plugins/datatables/dataTables.buttons.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/buttons.bootstrap4.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/jszip.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/pdfmake.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/vfs_fonts.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/buttons.html5.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/buttons.print.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/buttons.colVis.min.js"></script>
     <!-- Responsive examples -->
     <script src="{{asset('assets')}}/plugins/datatables/dataTables.responsive.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/responsive.bootstrap4.min.js"></script>

    <!-- App js -->
    <script src="{{asset('assets')}}/js/app.js"></script>
    

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 6000); // 6 seconds
        });
    </script>
 
    <script>
        function displaySelectedImage(event) {
            var image = document.getElementById('student_image');
            image.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>

    <script>
        
        let videoStream;

        function openCameraPopup() {
            const cameraPopup = document.getElementById('camera_popup');
            cameraPopup.style.display = 'block';

            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    videoStream = stream;
                    document.getElementById('video').srcObject = stream;
                })
                .catch(error => {
                    console.error('Error accessing webcam:', error);
                });
        }

        function closeCameraPopup() {
            const cameraPopup = document.getElementById('camera_popup');
            cameraPopup.style.display = 'none';
            stopVideoStream();
        }

        function captureImage() {
            const video = document.getElementById('video');
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageDataURL = canvas.toDataURL('image/jpg');

            // Convert data URL to file object
            const file = dataURLToFile(imageDataURL, 'captured_image.jpg');

            // Create a DataTransfer object to hold the file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);

            // Assign the file to the input field
            const input = document.getElementById('image_input');
            input.files = dataTransfer.files;

            document.getElementById('student_image').src = imageDataURL;
            closeCameraPopup();
        }

        // Function to convert data URL to file object
        function dataURLToFile(dataurl, filename) {
            var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], filename, { type: mime });
        }

        function stopVideoStream() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
        }
    </script>


<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('process.courses.ajax') }}",
                "type": "POST",
                "data": function (d) {
                    d._token = "{{ csrf_token() }}"; // Include CSRF token
                }
            },
            "scrollX": true,
            "columns": [
                {
                    "data": null,
                    "render": function (data, type, row) {
                        var viewUrl = '{{ route("view.course", ":id") }}';
                        viewUrl = viewUrl.replace(':id', row.id);
                        return '<div class="text-center"><a href="' + viewUrl + '" class="btn btn-sm btn-info">View</a></div>';
                    }
                },
                { "data": "course_id", "name": "course_id" },
                { "data": "course_name", "name": "course_name" },
                { "data": "course_description", "name": "course_description" },
                { "data": "duration", "name": "duration" },
                { "data": "fee", "name": "fee" }
            ]
        });
    });
</script>







@endsection
