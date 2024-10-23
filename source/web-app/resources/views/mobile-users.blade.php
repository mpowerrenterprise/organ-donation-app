@extends('layouts.dashboard_master_layout')

@section('HeaderAssets')

  <title>Mobile Users</title>

  <!-- DataTables -->
  <link href="{{asset('assets')}}/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
  <link href="{{asset('assets')}}/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
  <link href="{{asset('assets')}}/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

  <link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="{{asset('assets')}}/css/metismenu.min.css" rel="stylesheet" type="text/css">
  <link href="{{asset('assets')}}/css/icons.css" rel="stylesheet" type="text/css">
  <link href="{{asset('assets')}}/css/style.css" rel="stylesheet" type="text/css">

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
                <h4 class="page-title">Mobile Users</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a>Mobile App</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">Users</a>
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
                    <table id="datatable" class="table table-bordered display nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Blood Type</th>
                                <th>Organ</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
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

     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
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
        $(document).ready(function() {
            $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('process.mobile.users.ajax') }}", // Update route for fetching mobile users
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
                            if (row.status === 'pending') {
                                // Show Approve and Delete buttons for pending users
                                return '<div class="text-center">' +
                                    '<button type="button" class="btn btn-info btn-approve" onclick="confirmApprove(' + row.id + ')">Approve</button>' +
                                    ' <button type="button" class="btn btn-danger btn-delete" onclick="confirmDelete(' + row.id + ')">Delete</button>' +
                                    '</div>';
                            } else {
                                // Show only Delete button for approved users
                                return '<div class="text-center">' +
                                    '<button type="button" class="btn btn-danger btn-delete" onclick="confirmDelete(' + row.id + ')">Delete</button>' +
                                    '</div>';
                            }
                        }
                    },

                    { "data": "full_name", "name": "full_name" },
                    { "data": "email", "name": "email" },
                    { "data": "phone_number", "name": "phone_number" },
                    { "data": "blood_type", "name": "blood_type" },
                    { "data": "organ", "name": "organ" },
                    {
                        "data": "status",
                        "name": "status",
                        "render": function(data, type, row) {
                            return data.charAt(0).toUpperCase() + data.slice(1); // Capitalize the first letter of the status
                        }
                    }

                ]
            });
        });

        // Confirm approve function using SweetAlert
        function confirmApprove(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will approve the user!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    approveUser(id);
                }
            })
        }

        // Approve function
        function approveUser(id) {
            $.ajax({
                url: '{{ route("approve.user", ":id") }}'.replace(':id', id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Approved!',
                            'The user has been approved.',
                            'success'
                        );
                        $('#datatable').DataTable().ajax.reload(); // Reload DataTable
                    } else {
                        Swal.fire(
                            'Error!',
                            'Failed to approve the user.',
                            'error'
                        );
                    }
                }
            });
        }


        // Confirm delete function using SweetAlert
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the user!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteUser(id);
                }
            })
        }

        // Delete function
        function deleteUser(id) {
            $.ajax({
                url: '{{ route("delete.user", ":id") }}'.replace(':id', id),
                type: 'DELETE', // DELETE method
                data: {
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            'The user has been deleted.',
                            'success'
                        );
                        $('#datatable').DataTable().ajax.reload(); // Reload DataTable
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong.',
                        'error'
                    );
                    console.log(xhr.responseText); // Log any errors for debugging
                }
            });

        }


    </script>






@endsection
