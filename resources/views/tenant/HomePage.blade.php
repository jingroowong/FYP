@extends('layouts.header')
<link rel="stylesheet" href="{{ asset('/storage/css/HomePage.css') }}" media="screen">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-ezgk0SpaBGgz5z1rK7F2nb8FqWizfeO20mls3fjlTrcl5xFG49FwiQbxpD1xkVWZ" crossorigin="anonymous">

<style>
 .recommend-title {
      margin-left: 60px;
      text-align: left;
   }

   .recommend-p {
      margin-left: 60px;
      text-align: center;
      position: relative;
      width: 95%;
   }

   .property-container {
      position: relative;
      width: 95%;
      overflow: hidden;
      margin-left: 40px;
   }

   .scroll-buttons {
      position: absolute;
      top: 60%;
      transform: translateY(-50%);
      width: 100%;
      display: flex;
      justify-content: space-between;
   }

   .scroll-button {
      background-color: rgba(255, 255, 255, 0.7);
      border: 1px solid #ccc;
      padding: 5px 10px;
      margin: 0 20px;
      cursor: pointer;
      transition: background-color 0.3s ease;
   }

   .scroll-button:hover {
      background-color: #eee;
   }

   .property-list {
      display: flex;
      transition: transform 0.5s ease; /* Add transition effect */
   }

   .property-card {
      flex: 0 0 auto;
      margin-right: 10px;
      border: 1px solid #ccc;
      padding: 10px;
      width: 350px; /* Adjust the width as needed */
   }
   .property-card p{
    margin:0;
    text-align: left;
   }
   .property-card:hover{
    cursor:pointer;
   }
   
   .property-img {
      max-width: 100%;
      height: 250px;
      margin-bottom: 10px;
   }
.desc{
    overflow: hidden;
    word-wrap: break-word;
    height:50px;
    color:#777;
    text-overflow: ellipsis;
}
.rental-fee{
    color:#1f98af;
}
   
</style>

@section('content')


<div class="clearfix">

    <div class="topImage" style="background-image: url('{{ asset('storage/images/brt.png') }}');">

        <div class="hometop-description">
            <p class="topd-1">Find Rental Property That Feel Like Home</p>
            <p class="topd-2">Your Journey Start Here</p>
        </div>

        <div class="search-container text-center">
            <div class="search-title">Rent Property</div>
            <div class="divider"></div>

            <form method="GET" action="{{ route('home.search') }}" class="search-form">
                @csrf

                <div class="input-button-container">
                    <input class="search-input" type="text" name="location" placeholder="Search by location">
                    <button class="search-button" type="submit">Search</button>
                </div>

                <div class="search-select-container">

                    <select class="search-select" name="propertyType">
                        <option value="">Rent Property Type</option>
                        <option value="Residential Apartment">Residential Apartment</option>
                        <option value="House">House</option>
                        <option value="Condominium">Condominium</option>
                        <option value="Commercial Space">Commercial Space</option>

                    </select>

                    <select class="search-select" name="pricing">
                        <option value="">Pricing(Any)</option>
                        <option value="low">200-400</option>
                        <option value="medium">401-600</option>
                        <option value="high">601-800</option>
                        <option value="very high">800 above</option>
                    </select>

                    <select class="search-select" name="state">
                        <option value="">State(Any)</option>
                        <option value="1">Johor</option>
                        <option value="2">Kedah</option>
                        <option value="3">Kelantan</option>
                        <option value="4">Kuala Lumpur</option>
                        <option value="5">Labuan</option>
                        <option value="6">Melaka</option>
                        <option value="7">Negeri Sembilan</option>
                        <option value="8">Pahang</option>
                        <option value="9">Penang</option>
                        <option value="10">Perak</option>
                        <option value="11">Perlis</option>
                        <option value="12">Perlis</option>
                        <option value="13">Sabah</option>
                        <option value="14">Sarawak</option>
                        <option value="15">Selangor</option>
                        <option value="16">Terengganu</option>
                    </select>


                    <select class="search-select" name="bedrooms">
                        <option value="">Bedrooms</option>
                        <option value="1">1 Bedroom</option>
                        <option value="2">2 Bedrooms</option>
                        <option value="3">3 Bedrooms</option>
                        <option value="4">4 Bedrooms</option>
                        <option value="5">5 Bedrooms</option>
                    </select>

                </div>
            </form>
        </div>
    </div>

    <div class="main-home">
        <div class="text-above-a">Bringing You Closer To Home Sweet Home</div>
        <div class="main-home-container">
            <div class="content-box ab">

                <div class="content-box a">
                    <div class="abox-title">
                        <p>Finding Home</p>
                    </div>
                    <div class="abox-description">
                        <div class="description-text">
                            <p>Guided By Professional Agent in RentSpace</p>
                        </div>
                        <div class="description-image">
                            <img src="{{ asset('storage/images/agreement.png') }}" alt="Description">
                        </div>
                    </div>

                    <a class="link-button" href="{{ route('AgentLists') }}">Explore Agent</a>
                </div>

                <div class="content-box b">
                    <div class="abox-title">
                        <p>Curious to Know More?</p>
                    </div>
                    <div class="abox-description">
                        <div class="description-text">
                            <p>Here the scope on who we are and what we stand for</p>
                        </div>
                        <div class="description-image">
                            <img src="{{ asset('storage/images/request.png') }}" alt="Description">
                        </div>
                    </div>

                    <a class="link-button" href="your-link-here">View About Us</a>
                </div>
            </div>
            <div class="content-box c">
                <div class="content-box c-content">
                    <div class="cbox-title">
                        <p>Finding Your Place in RentSpace</p>
                    </div>
                    <div class="cbox-description">
                        <div class="cbox-description-text">
                            <p>Lets Browse Our Latest Rent Properties</p>
                        </div>
                        <div class="cbox-description-image">
                            <img src="{{ asset('storage/images/house.png') }}" alt="Description">
                        </div>
                    </div>

                    <a class="cbox-link-button" href="{{ route('PropertyDetails') }}">Find Out More</a>

                </div>

            </div>
        </div>
    </div>
    <div class="recommend-p">
         <h2 class="recommend-title">Recommendation Properties</h2>

         <div class="property-container">
            <!-- Add buttons for scrolling -->
          
            <div class="property-list" id="propertyList">
            
            @if ($result)
                    @foreach ($result as $property)
                    <div class="property-card" onclick="redirectToPropertyPage('{{ $property->propertyID }}')">
                    <img class="property-img" src="{{ Storage::url($property->propertyPhotos[0]->propertyPath) }}" alt="Property 3">
                    <p><strong>{{$property->propertyAddress}}</strong></p>
                    <p class="rental-fee"><strong>Rental Amount:</strong> {{$property->rentalAmount}}</p>
                    <p class="desc">{{$property->propertyDesc}}</p>
                    <p><strong>{{$property->propertyType}} - {{$property->housingType}}</strong></p>
                    <p style="font-style:italic;">Room Type: {{$property->roomType}}</p>
                    </div>
                    @endforeach
                @endif
            </div>
         </div>
         <div class="scroll-buttons">
         <div class="scroll-button scroll-left" onclick="customScrollLeft()">
            <i class="fas fa-chevron-left"></i>
        </div>
            <div class="scroll-button scroll-right" onclick="scrollRight()">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
      </div>
</div>

<script>
    const propertyContainer = document.querySelector('.property-container');

    function customScrollLeft() {
        // Ensure there is content to scroll left
        if (propertyContainer.scrollLeft >= 320) {
            propertyContainer.scrollTo({
                left: propertyContainer.scrollLeft - 320,
                behavior: 'smooth'
            });
        }
    }

    function scrollRight() {
        // Adjust the condition as needed based on your content
        if (propertyContainer.scrollLeft + propertyContainer.clientWidth < propertyContainer.scrollWidth) {
            propertyContainer.scrollTo({
                left: propertyContainer.scrollLeft + 320,
                behavior: 'smooth'
            });
        }
    }

    function redirectToPropertyPage(propertyId) {
        var propertyPageRoute = "{{ route('properties.show', ':id') }}";
        propertyPageRoute = propertyPageRoute.replace(':id', propertyId);
        window.location.href = propertyPageRoute;
    }

</script>




@endsection