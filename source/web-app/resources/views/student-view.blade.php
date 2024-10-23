@extends('layouts.dashboard_master_layout')

@section('HeaderAssets')

    <title>IOV - Customer View</title>

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

@endsection

@section('PageContent')

    <div class="container-fluid">
        <div class="page-title-box">

            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">Customer View</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">IOV</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('show.students') }}">Student</a>
                            </li>
                            <li class="breadcrumb-item active" style="text-transform: capitalize;">{{$student->first_name}}</li>
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

                        <form id="editStudentForm" method="POST" action="{{ route('edit.student', ['data' => urlencode(json_encode(['id' => $student->id, 'image' => $student->profile_picture]))]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                                <div class="d-flex flex-column align-items-center mb-3" style="padding-top: 25px; padding-bottom: 25px;">
                                    <a>
                                        <img id="student_image" src="{{ route('show.student.image', ['filename' =>  $student->profile_picture]) }}" style="width: 450px; height: 450px; border-radius: 10px;" alt="Customer Image">
                                    </a>
                                    <h4 class="mt-3" style="text-transform: capitalize;">{{ $student->first_name }}</h4>
                            
                                    <div class="mt-3">
                                        <!-- Browse Button -->
                                        <input type="file" id="image_input" style="display:none;" name="profile_picture" accept=".jpeg,.png,.jpg,.gif,.svg,.webp" onchange="displaySelectedImage(event)">
                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('image_input').click()">
                                            <i class="fas fa-folder-open"></i> Browse
                                        </button>
                                        <!-- Camera Button -->
                                        <button type="button" class="btn btn-primary" onclick="openCameraPopup()">
                                            <i class="fas fa-camera"></i> Camera
                                        </button>
                                    </div>
                                </div>
                        

                                <!-- Animated Webcam Popup -->
                                <div class="popup animate__animated animate__bounceIn" id="camera_popup">
                                    <video id="video" width="500" height="500" class="rounded" autoplay></video>
                                    <div class="mt-3 d-flex justify-content-between">
                                        <button type="button" class="btn btn-success me-2" onclick="captureImage()">Capture</button>
                                        <button type="button" class="btn btn-danger" onclick="closeCameraPopup()">Cancel</button>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="id" class="col-sm-2 col-form-label">ID:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="{{ $student->id }}" id="id" readonly required name="id">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="student_id" class="col-sm-2 col-form-label">Student ID (NIC):</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="{{ $student->student_id }}" id="student_id" required name="student_id">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="student_fname" class="col-sm-2 col-form-label">First Name:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="{{ $student->first_name }}" id="student_fname" name="student_fname" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="student_lname" class="col-sm-2 col-form-label">Last Name:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="{{ $student->last_name }}" id="student_lname" name="student_lname" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="student_address" class="col-sm-2 col-form-label">Address:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="{{ $student->address }}" id="student_address" name="student_address" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="student_phone_number" class="col-sm-2 col-form-label">Phone Number:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="{{ $student->phone_number }}" id="student_phone_number" name="student_phone_number" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="student_email" class="col-sm-2 col-form-label">Email:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="email" value="{{ $student->email }}" id="student_email" name="student_email" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="date_of_birth" class="col-sm-2 col-form-label">Date of Birth:</label>
                                    <div class="col-sm-10">
                                        @php
                                            // Convert the date format from mm/dd/yyyy to yyyy-mm-dd
                                            $dob = \Carbon\Carbon::parse($student->date_of_birth)->format('Y-m-d');
                                        @endphp
                                        <input class="form-control" type="date" value="{{ $dob }}" id="date_of_birth" name="date_of_birth" onclick="this.showPicker()">
                                    </div>
                                </div>
                                
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Gender</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="student_gender" name="student_gender" required>
                                            <option disabled>Select Gender</option>
                                            <option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-sm-2 d-flex justify-content-end">
                                        <input type="submit" value="Save Changes" class="btn btn-info" style="margin-right: 10px;">
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
                                    </div>
                                </div>                      
                        </form>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body">
                        <h4 style="text-align: center; margin-bottom:30px;">Enrolled Courses</h4>
                        
                        <table id="datatable" class="table table-bordered display nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Course Name</th>
                                    <th>Batch No</th>
                                    <th>Batch Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($enrolledCourses as $course)
                                    <tr>
                                        <td>{{ $course['course_name'] }}</td>
                                        <td>{{ $course['batch_no'] }}</td>
                                        <td>{{ $course['batch_name'] }}</td>
                                        <td>{{ $course['start_date'] }}</td>
                                        <td>{{ $course['end_date'] }}</td>
                                        <td>
                                            @if ($course['status'] == 'Incomplete')
                                                <span class="badge badge-warning">{{ $course['status'] }}</span>
                                            @elseif ($course['status'] == 'Completed')
                                                <span class="badge badge-success">{{ $course['status'] }}</span>
                                            @endif
                                        </td>                                        
                                    </tr>
                                @endforeach
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
                // If confirmed, submit the form for deletion
                document.getElementById('editStudentForm').setAttribute('action', '{{ route('delete.student', ['data' => urlencode(json_encode(['id' => $student->id, 'image' => $student->profile_picture]))]) }}');
        
                // Update the existing _method input field to DELETE
                var methodInput = document.querySelector('input[name="_method"]');
                if (methodInput) {
                    methodInput.value = 'DELETE';
                }

                document.getElementById('editStudentForm').submit();
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

@endsection
