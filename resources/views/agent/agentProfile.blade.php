<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body>
    @extends('layouts.adminApp')
    <style>

    #upload-img {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;

    }

    .rounded-circle-container {
        position: relative;

    }

    .rounded-circle {
        max-width: 150px;
        max-height: 150px;
        width: 100%;
        height: 100%;
        border: solid 1px black;
    }

    .upload-icon img {
        height: 80px;
        width: 80px;
    }

    .upload-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0;
        /* Initially hidden */
        transition: opacity 0.3s ease-in-out;
    }

    #upload-img:hover .upload-icon {
        opacity: 1;
        /* Show on hover */
    }

    #save-image {
        margin-top: 10px;
        visibility: hidden;
    }
    </style>
    <script>
    $(document).ready(function() {


        document.getElementById("image-upload").addEventListener("change", function() {
            var imageElement = document.getElementById("upload-img").querySelector("img");
            var selectedImage = this.files[0];

            if (selectedImage) {
                var imageUrl = URL.createObjectURL(selectedImage);
                imageElement.src = imageUrl;
            }
        });

        function triggerImageUpload() {
            document.getElementById("image-upload").click();
        }

        document.getElementById("image-upload").addEventListener("change", function() {
            document.getElementById("save-image").style.visibility = "visible";
        });

    });
    </script>

    @section('content')
    <div class="ml-5 mt-2 container">
        <h2>My Profile</h2>
        @if($user->userRole=="agent")
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @elseif(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="row">
            <div class="col-md-8" style="padding:0;">
            </div>

            <div class="col-md-4" style="padding:0;">
                <div class="form-group text-center">
                    <a href="{{ route('ChangePassword') }}" class="btn btn-primary" style="padding: 10px 20px;">Change
                        Password</a>
                </div>
            </div>
        </div>


        <div class="container mt-4 border">
            <div class="row">
                <div class="col-md-4">
                    <!-- Left Side: User Image -->
                    <form action="{{ route('UploadAgentPhoto') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div id="upload-img">
                            <div class="rounded-circle-container">
                                <div class="rounded-circle overflow-hidden text-center">
                                    @if (!empty($user->photo))
                                    <img src="{{ asset('storage/'. $user->photo) }}" alt="Agent Photo"
                                        class="mx-auto d-block rounded-circle" style="width: 150px; height: 150px;">
                                    @else
                                    <img src="{{ asset('storage/users-avatar/agent.png') }}" alt="Default Image"
                                        class="mx-auto d-block rounded-circle" style="width: 150px; height: 150px;">
                                    @endif
                                    <div class="upload-icon">
                                        <label for="image-upload" style="cursor: pointer;">
                                            <img src="{{ asset('storage/images/up-loading.png') }}" alt="Upload Image">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <input type="file" id="image-upload" name="profile_image" style="display: none;"
                            accept="image/*">
                        <div class="form-group col-md-12 text-center">
                            <input type="hidden" name="id" value="{{$user->agentID}}">
                            <input type="hidden" name="userRole" value="{{$user->userRole}}">
                            <h4>{{$user->agentID}}</h4>
                            <button type="submit" id="save-image" class="btn btn-primary"
                                onclick="return confirm('Are you sure to upload this image for your profile?')">Save
                                Change</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-8">
                    <!-- Right Side: User Details Form -->
                    <form action="{{ route('UpdateAgentProfile') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->agentID}}">
                        <input type="hidden" name="userRole" value="{{$user->userRole}}">
                        <div class="card border-0">
                            <div class="card-body">
                                <h5 class="card-title">Profile Details</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{$user->agentName}}" required>
                                        @error('name')
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="email">Email Address:</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            value="{{$user->agentEmail}}" required readonly>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="phone">Contact Number:</label>
                                        <input type="tel" id="phone" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{$user->agentPhone}}">
                                        @error('phone')
                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="licenseNumber">License Number:</label>
                                        <input type="text" id="licenseNumber" name="licenseNum"
                                            class="form-control @error('licenseNum') is-invalid @enderror"
                                            placeholder="Optional Eg(REN/REAXXXXX)" value="{{$user->licenseNum}}">
                                        @error('licenseNum')
                                        <span class="text-danger">{{ $errors->first('licenseNum') }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Update Profile</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if(count($reviews) > 0 )
        <h2 style="margin-top:30px;">My Reviews</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Review ID</th>
                    <th>Comment</th>
                    <th>Rating</th>
                    <th>Reviewer Name</th>
                    <th>Reviewed Date</th>
                </tr>
            </thead>

            <tbody>
                @foreach($reviews as $review)
                <tr>
                    <td>{{ $review->reviewID }}</td>
                    <td>{{ $review->comment }}</td>
                    <td>{{ $review->rating }}</td>
                    <td>
                        @if ($review->agent)
                        {{ $review->agent->agentName }}
                        @elseif ($review->tenant)
                        {{ $review->tenant->tenantName }}
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($review->reviewDate)->diffForHumans() }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center result-page">
                {{ $reviews->onEachSide(1)->links() }}
            </div>
        </div>
        @endif
        @else
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @elseif(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <div class="row">
            <div class="col-md-8" style="padding:0;">
            </div>

            <div class="col-md-4" style="padding:0;">
                <div class="form-group text-center">
                    <a href="{{ route('ChangePassword') }}" class="btn btn-primary" style="padding: 10px 20px;">Change
                        Password</a>
                </div>
            </div>
        </div>

        <div class="container mt-4 border">
            <div class="row">
                <div class="col-md-4">
                    <!-- Left Side: User Image -->
                    <form action="{{ route('UploadAgentPhoto') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div id="upload-img">
                            <div class="rounded-circle-container">
                                <div class="rounded-circle overflow-hidden text-center">
                                    @if (!empty($user->photo))
                                    <img src="{{ asset('storage/'. $user->photo) }}" alt="Agent Photo"
                                        class="mx-auto d-block rounded-circle" style="width: 150px; height: 150px;">
                                    @else
                                    <img src="{{ asset('storage/users-avatar/admin.png') }}" alt="Default Image"
                                        class="mx-auto d-block rounded-circle" style="width: 150px; height: 150px;">
                                    @endif
                                    <div class="upload-icon">
                                        <label for="image-upload" style="cursor: pointer;">
                                            <img src="{{ asset('storage/images/up-loading.png') }}" alt="Upload Image">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <input type="file" id="image-upload" name="profile_image" style="display: none;"
                            accept="image/*">
                        <div class="form-group col-md-12 text-center">
                            <input type="hidden" name="id" value="{{$user->adminID}}">
                            <input type="hidden" name="userRole" value="{{$user->userRole}}">
                            <h4>{{$user->adminID}}</h4>
                            <button type="submit" id="save-image" class="btn btn-primary"
                                onclick="return confirm('Are you sure to upload this image for your profile?')">Save
                                Change</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-8">
                    <!-- Right Side: User Details Form -->
                    <form action="{{ route('UpdateAgentProfile') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->adminID}}">
                        <input type="hidden" name="userRole" value="{{$user->userRole}}">
                        <div class="card border-0">
                            <div class="card-body">
                                <h5 class="card-title">Profile Details</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{$user->adminName}}" required>
                                        @error('name')
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="email">Email Address:</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            value="{{$user->adminEmail}}" required readonly>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="phone">Contact Number:</label>
                                        <input type="tel" id="phone" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{$user->adminPhone}}">
                                        @error('phone')
                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Update Profile</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endsection
</body>

</html>