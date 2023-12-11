@extends('layouts.header')
<link rel="stylesheet" href="{{ asset('/storage/css/ViewMyAccount.css') }}" media="screen">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')



<script>

$(document).ready(function() {

 $(".dynamic-content").hide();

 var sessionContent = "{{ session('dynamicContent') }}";


 if (sessionContent) {
     $("#" + sessionContent).show();
     {{ session()->forget('dynamicContent') }}

 } else {

     $("#profile").show();
 }


 $(".menu-left li").click(function() {
     
     $(".dynamic-content").hide();

    
     var contentToShow = $(this).data("content");

  
     $("#" + contentToShow).show();

     
 });


 document.getElementById("image-upload").addEventListener("change", function () {
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

document.getElementById("image-upload").addEventListener("change", function () {
 document.getElementById("save-image").style.visibility = "visible";
});

});
</script>



<div class="account-card">
    <div class="menu-left">
        @if(session('upload-error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('upload-error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @elseif(session('upload-success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('upload-success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="image-container">
            <form action="{{ route('UploadPhoto') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="upload-img" style="text-align: center;">
                    @if (!empty(session('tenant')->photo))
                    <img src="{{ asset('storage/'. session('tenant')->photo) }}" alt="Tenant Photo">
                    @else
                    <img src="{{ asset('storage/users-avatar/landlord.png') }}" alt="Default Image">
                    @endif

                    <div class="upload-icon">
                        <label for="image-upload" style="cursor: pointer;">
                            <img src="{{ asset('storage/images/up-loading.png') }}" alt="Upload Image">
                        </label>
                    </div>
                </div>
                <input type="file" id="image-upload" name="profile_image" style="display: none;" accept="image/*">
                <input type="hidden" name="tenantID" value="{{session('tenant')->tenantID }}">
                <button type="submit" id="save-image" style="margin-top: 10px; visibility: hidden;"
                    class="btn btn-primary"
                    onclick="return confirm('Are you sure to upload this image for your profile?')">Save Change</button>
            </form>
        </div>


        <div class="profile-details">
            <div class="detail text-center">
                <span style="font-size:24px; color:white;">{{session('tenant')->tenantName }}</span>
            </div>
            <div class="detail text-center">
                <i class="fas fa-envelope"></i>
                <span>{{ session('tenant')->tenantEmail }}</span>
            </div>
        </div>

        <ul class="profile-menu">
            <li data-content="profile">Edit My Profile</li>
            <li data-content="reset-password">Set New Password</li>
            <li data-content="reviews">My Reviews</li>
        </ul>
    </div>

    <div class="content-right">

        <div id="profile" class="dynamic-content">

            @if(session('update-success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('update-success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @elseif(session('update-error'))
            <div class="alert alert-error alert-dismissible fade show">
                {{ session('update-error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="edit-profile-title text-center">
                <h1>Profile Settings</h1>
                <p>You can edit your profile here.</p>
            </div>

            <form action="{{ route('UpdateProfile') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="tenantID">User ID:</label>
                    <input type="text" id="tenantID" name="tenantID" value="{{ session('tenant')->tenantID }}"
                        class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="tenantName">User Name:</label>
                    <input type="text" class="form-control @error('tenantName') is-invalid @enderror" id="tenantName"
                        name="tenantName" placeholder="Enter Your User Name" value="{{ session('tenant')->tenantName }}"
                        required>
                    @error('tenantName')
                    <span class="text-danger">{{ $errors->first('tenantName') }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tenantEmail">Email Address:</label>
                    <input type="email" id="tenantEmail" name="tenantEmail" value="{{ session('tenant')->tenantEmail }}"
                        class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="tenantPhone">Contact Number:</label>
                    <input type="tel" id="tenantPhone" name="tenantPhone" value="{{ session('tenant')->tenantPhone }}"
                        placeholder="Enter Your Contact Number (Eg: 012-8697043)"
                        class="form-control @error('tenantPhone') is-invalid @enderror">
                    @error('tenantPhone')
                    <span class="text-danger">{{ $errors->first('tenantPhone') }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="dateofbirth">Date of Birth:</label>
                    <input type="date" id="tenantDOB" name="tenantDOB"
                        class="form-control @error('tenantDOB') is-invalid @enderror"
                        value="{{ date('Y-m-d', strtotime(session('tenant')->tenantDOB)) }}">
                    @error('tenantDOB')
                    <span class="text-danger">{{ $errors->first('tenantDOB') }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror">
                        <option value="M" {{ (session('tenant')->gender == 'M') ? 'selected' : '' }}>Male</option>
                        <option value="F" {{ (session('tenant')->gender == 'F') ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                    <span class="text-danger">{{ $errors->first('gender') }}</span>
                    @enderror
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary"
                        onclick="return confirm('Are you sure to update your profile?')">Save Change</button>
                </div>
            </form>

        </div>


        <div id="reset-password" class="dynamic-content">

            @if(session('reset-error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('reset-error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @elseif(session('reset-success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('reset-success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="edit-profile-title text-center">
                <h1>Reset New Password</h1>
                <p>You can set your new password here.</p>
            </div>

            <div class="password-rules">
                <p class="text-muted">Password Rules:</p>
                <ol>
                    <li>Minimum 6 characters</li>
                    <li>Maximum 15 characters</li>
                </ol>
            </div>

            <div class="password-safety">
                <p class="text-muted">Security Information:</p>
                <ul>
                    <li>To change the password whenever necessary.</li>
                    <li>You are responsible for keeping the password safe.</li>
                    <li>Do not share your password with anyone.</li>
                </ul>
            </div>

            <form action="{{ route('UpdatePassword') }}" method="post">
                @csrf

                <input type="hidden" id="tenantID" name="tenantID" value="{{ session('tenant')->tenantID }}"
                    class="form-control">
                <div class="form-group">

                    <label for="currentPassword">Current Password:</label>
                    <input type="password" id="currentPassword" name="currentPassword"
                        class="form-control @error('currentPassword') is-invalid @enderror"
                        placeholder="Enter Your Current Password" required>
                    @error('currentPassword')
                    <span class="text-danger">{{ $errors->first('currentPassword') }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" autocomplete="new-password"
                        placeholder="Enter Your New Password" required>
                    @error('password')
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                        id="password-confirm" name="password_confirmation" placeholder="Confirm Your Confirm Password"
                        autocomplete="new-password" required>
                    @error('password')
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                    @enderror
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary"
                        onclick="return confirm('Are you sure to reset your password?')">Reset Password</button>
                </div>
            </form>

        </div>


        <div id="reviews" class="dynamic-content">
            <div class="edit-profile-title text-center">
                <h1>My Reviews</h1>
                <p>You can view all reviews that review by you here.</p>
            </div>


            @foreach ($userReviews as $review)
            <div class="review-account-container">

                <div class="item-name specific-item-name">Review for {{ $review->itemName }}</div>
                <div class="review-account-date">Reviewed
                    {{ \Carbon\Carbon::parse($review->reviewDate)->diffForHumans() }}</div>
                <div class="account-comment">{{ $review->comment }}</div>
                <div class="account-rating">
                    Rating:
                    @for ($i = 1; $i <= 5; $i++) @if ($i <=$review->rating)
                        <i class="fas fa-star" style="color: #00ada0;"></i>
                        @else
                        <i class="fas fa-star" style="color: #ddd;"></i>
                        @endif
                        @endfor
                </div>

                <div class="details-link">
            @if (Str::startsWith($review->reviewItemID, 'PRO'))
                <a href="{{ route('properties.show', $review->reviewItemID) }}">See what others have commented about {{ $review->itemName }}</a>
            @elseif (Str::startsWith($review->reviewItemID, 'AGT'))
                <a href="{{ route('AgentDetails', ['id' => $review->reviewItemID]) }}">See what others have commented about {{ $review->itemName }}</a>
            @endif
        </div>


            </div>
            @endforeach



            </ul>

        </div>

    </div>
</div>


@endsection