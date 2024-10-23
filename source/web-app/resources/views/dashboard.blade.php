@extends('layouts.dashboard_master_layout')

@section('HeaderAssets')
  <title>Dashboard</title>

  <!-- DataTables -->
  <link href="{{asset('assets')}}/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
  <link href="{{asset('assets')}}/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
  <link href="{{asset('assets')}}/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

  <link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="{{asset('assets')}}/css/metismenu.min.css" rel="stylesheet" type="text/css">
  <link href="{{asset('assets')}}/css/icons.css" rel="stylesheet" type="text/css">
  <link href="{{asset('assets')}}/css/style.css" rel="stylesheet" type="text/css">

@endsection

@section('PageContent')

<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
          <div class="col-md-8">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard</h4>
            </div>
          </div>
        </div>
    </div>

    <div class="row">
        <!-- Total Mobile Users -->
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center p-1">
                        <div class="col-lg-12">
                            <h5 class="font-16">Total Mobile Users</h5>
                            <h4 class="text-info pt-1 mb-0">{{ $totalMobileUsers }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Requested Organs -->
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center p-1">
                        <div class="col-lg-12">
                            <h5 class="font-16">Total Organs</h5>
                            <h4 class="text-info pt-1 mb-0">{{ $totalOrgans }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

          <!-- Total Requested Organs -->
          <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center p-1">
                        <div class="col-lg-12">
                            <h5 class="font-16">Total Requested Organs</h5>
                            <h4 class="text-info pt-1 mb-0">{{ $totalRequestedOrgans }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Donated Organs -->
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center p-1">
                        <div class="col-lg-12">
                            <h5 class="font-16">Total Donated Organs</h5>
                            <h4 class="text-info pt-1 mb-0">{{ $totalDonatedOrgans }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <hr style="margin-top:150px;">

    
        <!-- Total Organs -->
        @foreach($organEmojis as $organ => $emoji)
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center p-1">
                        <div class="col-lg-12">
                            <h5 class="font-16">{{ $emoji }} {{ $organ }}</h5>
                            <h4 class="text-warning pt-1 mb-0">{{ \App\Models\Organ::where('organ_name', $organ)->count() }}</h4> <!-- Show organ count -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <!-- Recent Organ Requests -->
    <div class="row mt-4">
        <div class="col-12">
            <h4 class="page-title">Recent Organ Requests</h4>
            <div class="card">
                <div class="card-body">
                    <table id="datatable" class="table table-bordered display nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Organ Name</th>
                                <th>Requested By</th>
                                <th>Phone Number</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRequests as $request)
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-info btn-approve" onclick="confirmAccept({{ $request->id }})">Accept</button>
                                        <button type="button" class="btn btn-danger btn-reject" onclick="confirmReject({{ $request->id }})">Reject</button>
                                    </td>
                                    <td>{{ $request->organ->organ_name }}</td>
                                    <td>{{ $request->user->full_name }}</td>
                                    <td>{{ $request->user->phone_number }}</td>
                                    <td>{{ ucfirst($request->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('FooterAssets')

     <!-- Required datatable js -->
     <script src="{{asset('assets')}}/plugins/datatables/jquery.dataTables.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/dataTables.bootstrap4.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/dataTables.responsive.min.js"></script>
     <script src="{{asset('assets')}}/plugins/datatables/responsive.bootstrap4.min.js"></script>

    <!-- App js -->
    <script src="{{asset('assets')}}/js/app.js"></script>

@endsection
