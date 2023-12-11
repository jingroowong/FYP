@extends('layouts.header')
<link rel="stylesheet" href="{{ asset('/storage/css/Wishlist.css') }}" media="screen">
<link rel="stylesheet" href="{{ asset('/storage/css/SearchProperty.css') }}" media="screen">
<link rel="stylesheet"
    href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <style>
        #map {
        width: "100%";
        height: 280px;
        margin:0 0 25px 0;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }
            .icon-map{
            cursor: pointer;
            }

            .map-icon:hover {
            cursor: pointer;
        }
        .wishlist-star {
            cursor: pointer;
            float:right;
            font-size: 24px;
          
        }

        .wishlist-star:hover {
            transform: scale(1.05);
            color:#f8db36 !important;
        }
    </style>
@section('content')
                           
    
<div class="container-fluid" style="margin-left:20px; ">
    <div class="row">
        
        <div class="col-md-1 custom-offset">
            <div class="mb-3">
                <label for="text" style="font-weight:bold; margin-bottom: 10px;">Search Property Here</label>
                <a href="#" title="See on Map" style="float:right; " onclick="toggleMap()">
            <i class="fa fa-map map-icon" style="color: #007bff; margin-top: 7px;"></i>
        </a>
            </div>

            <div class="mb-3" id="mapContainer" style="display: none;">
            <div id="map"></div>
                </div>

            <form action="{{ route('home.search') }}" method="GET">
                <div class="mb-3">
                    <p for="address" class="search-text">Address</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fa fa-map-marker" style="color: #007bff; background-color:transparent;"
                                    aria-hidden="true"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="address" name="location"
                            placeholder="Search by location">
                    </div>
                </div>
                <div class="mb-3">
                    <p for="state" class="search-text">State</p>
                    <select class="search-select" name="state" style="border:solid 1px #eee;">
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
                </div>
                <div class="mb-3">
                    <p for="rental_amount" class="search-text">Pricing of Rental</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pricing" id="low" value="low">
                        <label class="form-check-label" for="low">
                            200-400
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pricing" id="medium" value="medium">
                        <label class="form-check-label" for="medium">
                            401-600
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pricing" id="high" value="high">
                        <label class="form-check-label" for="high">
                            601-800
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pricing" id="very_high" value="very high">
                        <label class="form-check-label" for="very_high">
                            800 above
                        </label>
                    </div>
                </div>

                <div class="mb-3 text-center">
                    <button type="submit" style="width:200px; border-radius:20px;"
                        class="btn btn-primary">Search</button>
                </div>

            </form>
            <div class="mb-3 advence-filter">
                <a href="#" id="openModalBtn" style="color: #007bff; float:right; transition: color 0.3s;">Advanced
                    Search Filter <i class="fa fa-sliders" aria-hidden="true"></i></a>
            </div>

        </div>
     
        

        <div class="col-md-8 custom-offset2">

            @if($properties->count()>0)
            <div class="property-listing mb-3">
                <h6>Total {{$properties->count()}} results found</h6>
            </div>

            <div class="property-listing d-flex justify-content-between align-items-center">
    <h3>Rent Property Listings</h3>
    <form method="GET" action="{{ route('sort.properties') }}" id="sortForm">
        @csrf
        <input type="hidden" name="properties" value="{{ json_encode($properties) }}">
        <select class="form-control custom-dropdown" id="sort_by" name="sort_by" onchange="submitForm()">
            <option value="All">Sort by</option>
            <option value="latest">Sort by Latest</option>
            <option value="oldest">Sort by Oldest</option>
            <option value="Epricing">Sort by Highest Pricing</option>
            <option value="pricing">Sort by Lowest Pricing</option>
            <option value="Hrating">Sort by Highest Rating</option>
            <option value="Lrating">Sort by Lowest Rating</option>
        </select>
    </form>
</div>

            @foreach($properties as $property)
                            @php
                            $tenant = session('tenant');
                            if ($tenant) {
                            $wishlistData =
                            app('App\Http\Controllers\WishListController')->getWishlistData($property->propertyID,
                            $tenant->tenantID);
                            \Log::info('Wishlist Data:', ['wishlistData' => $wishlistData]);
                            } else {

                            $wishlistData = null;
                            }
                            @endphp
            <div class="col-md-12 mb-4">
    <div class="card">
        <div class="row no-gutters">
            <div class="col-md-4">
                <img src="{{ Storage::url($property->propertyPhotos[0]->propertyPath) }}"
                    class="card-img-top" height="220" alt="Property Image">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">{{ $property->propertyName }}</h5>
                            <p class="card-room-type">{{ $property->roomType }}</p>
                            <p class="card-property-type">{{ $property->propertyType }} - {{ $property->housingType}}</p>
                            <p class="card-property-description">{{ $property->propertyDesc }}</p>
                            <div class="row">
                            <div class="col-md-6">
                                <p><i class="lab la-buffer"></i> {{ $property->squareFeet }} SQ.FT</p>
                                <p><i class="las la-bed"></i> {{ $property->bedroomNum }} bedroom(s)</p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="las la-tools"></i> {{ $property->furnishingType }}</p>
                                <p><i class="las la-bath"></i> {{ $property->bathroomNum }} bathroom(s)</p>
                            </div>
                        </div>
                             
                        </div>

                       
                        <div class="col-md-3">
                            <div class="float-right">
                            @if ($property->total_reviews > 0)
                       
                                <p class="rating-text text-center" id="ratingText">
                                @if ($property->average_rating >= 8.1)
                                        <span class="excellent-text">Excellent {{ number_format($property->average_rating, 1) }}</span>
                                    @elseif ($property->average_rating >= 6.1)
                                        <span class="good-text">Good {{ number_format($property->average_rating, 1) }}</span>
                                    @elseif ($property->average_rating >= 4.1)
                                        <span class="average-text">Average {{ number_format($property->average_rating, 1) }}</span>
                                    @elseif ($property->average_rating >= 2.1)
                                        <span class="bad-text">Bad {{ number_format($property->average_rating, 1) }}</span>
                                    @else
                                        <span class="very-bad-text">Very Bad {{ number_format($property->average_rating, 1) }}</span>
                                    @endif
                                </p>
                                <p class="review-text text-center">{{ $property->total_reviews }} reviews</p>
                            @else
                            <p class="no-review text-center"> No reviews</p>
                          
                            @endif
                                <p class="rental-amount text-center">RM {{ $property->rentalAmount }}</p>
                                <a href="{{ route('properties.show', $property->propertyID)}}"
                                class="btn btn-primary text-center">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="undeline" style="border:solid 1px #eee; width:100%;"></div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0;">
                        <p style="color: gray; font-style: italic; margin-bottom: 0;">
                            <i class="fa fa-clock"></i> Posted at {{ \Carbon\Carbon::parse($property->created_at)->diffForHumans() }}
                        </p>
                        <form id="wishlistForm{{ $property->propertyID }}" action="{{ route('ToggleWishList') }}" method="post">
                            @csrf
                            <input type="hidden" name="propertyID" id="propertyID" value="{{ $property->propertyID }}">
                            <input type="hidden" name="tenantID" id="tenantID" value="{{ session('tenant') ? session('tenant')->tenantID : '' }}">
                            <i class="fas fa-bookmark wishlist-star" title="Add to Wishlist" id="wishlistStar{{ $property->propertyID }}" style=" margin: 15px 20px 0 0; color: {{ $wishlistData ? '#FFD700' : 'grey' }}"></i>
                        </form>
                    </div>


                </div>
            </div>
          
        </div>
    </div>
</div>

            @endforeach
            <div class="row">
                <div class="col-md-12 d-flex justify-content-center result-page">
                    {{ $properties->links() }}
                </div>
            </div>

        </div>
        @else
        <div class="wish-no-found">
            <h2>No Search Properties Found</h2>
            <p>Seeking for a specific house or room? Start searching your dream home now!</p>
            <a href="{{ route('propertyList') }}">Show All Property</a>
        </div>
        @endif

    </div>
</div>
</div>


<div class="modal fade" id="advancedFilterModal">
    <div class="modal-dialog custom-modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Advanced Search Filter</h4>
                <button type="button" id="closeBtn" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <form action="{{ route('advanced.filter') }}" method="GET">
            <!-- Modal Body -->
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
            
                <!-- Location -->
                <div class="sub">
                    <h5 class="sub-title" data-toggle="collapse" data-target="#locationDetails" aria-expanded="true">
                        Location
                        <i class="fa fa-angle-up icon-down" aria-hidden="true"></i>
                    </h5>
                    <div id="locationDetails" class="collapse show">
                        <input type="text" class="form-control" id="address" name="location"
                            placeholder="Search by location name, state ....">
                    </div>
                </div>
                <div class="under" style="border:solid 1px #eee; padding:0 10px;"></div>
                <!-- Property Type -->
                <div class="sub">
                    <h5 class="sub-title" data-toggle="collapse" data-target="#propertyTypeDetails"
                        aria-expanded="true">
                        Property Type
                        <i class="fa fa-angle-up icon-down" aria-hidden="true"></i>
                    </h5>
                    <div id="propertyTypeDetails" class="collapse show">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            @foreach($filters as $filter)
                            @if ($filter->filterAttribute == "Property Type")
                            <label class="btn btn-dark active mr-2 rounded-2">
                                <input type="radio" name="propertyType" value="{{$filter->filterValue}}"
                                    id="allPropertyType" {{ $filter->filterValue == 'All' ? 'checked' : '' }}>
                                {{$filter->filterValue}}
                            </label>
                            @endif
                            @endforeach
                        </div>
                        <div id="propertyList" style="margin: 5px 10px;">
                        
                    </div>
                    </div>
                    
                </div>

                <div class="under" style="border:solid 1px #eee; padding:0 10px;"></div>
                <!-- Price -->
                <div class="sub">
                    <h5 class="sub-title" data-toggle="collapse" data-target="#pricingDetail" aria-expanded="true">
                        Pricing
                        <i class="fa fa-angle-up icon-down" aria-hidden="true"></i>
                    </h5>
                    <div id="pricingDetail" class="collapse show">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RM</span>
                                    </div>
                                    <input type="text" class="form-control" id="minPrice" name="minPrice"
                                        placeholder="Minimum Price">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RM</span>
                                    </div>
                                    <input type="text" class="form-control" id="maxPrice" name="maxPrice"
                                        placeholder="Maximum Price">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="under" style="border:solid 1px #eee; padding:0 10px;"></div>
                <!-- Housing Type -->
                <div class="sub">
                    <h5 class="sub-title" data-toggle="collapse" data-target="#roomDetail" aria-expanded="true">
                        Room Type
                        <i class="fa fa-angle-up icon-down" aria-hidden="true"></i>
                    </h5>
                    <div id="roomDetail" class="collapse show">
                        <div class="row">
                            @foreach($filters as $filter)
                            @if ($filter->filterAttribute == "Room Type")
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="roomType" id="house"
                                        value="{{$filter->filterValue}}">
                                    <label class="form-check-label"
                                        for="{{$filter->filterValue}}">{{$filter->filterValue}}</label>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>


                <div class="under" style="border:solid 1px #eee; padding:0 10px;"></div>
                <!-- Bedroom -->
                <div class="sub bedroom-section">
                    <h5 class="sub-title" data-toggle="collapse" data-target="#bedroomDetail" aria-expanded="true">
                        Bedroom
                        <i class="fa fa-angle-up icon-down" aria-hidden="true"></i>
                    </h5>
                    <div id="bedroomDetail" class="collapse show">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Min Beds</span>
                                    </div>
                                    <input type="text" class="form-control" id="minBeds" name="minBeds"
                                        placeholder="Minimum Beds">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Max Beds</span>
                                    </div>
                                    <input type="text" class="form-control" id="maxBeds" name="maxBeds"
                                        placeholder="Maximum Beds">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="under" style="border:solid 1px #eee; padding:0 10px;"></div>
                <!-- Bathroom -->
                <div class="sub">
                    <h5 class="sub-title" data-toggle="collapse" data-target="#bathroomDetail" aria-expanded="true">
                        Bathroom
                        <i class="fa fa-angle-up icon-down" aria-hidden="true"></i>
                    </h5>
                    <div id="bathroomDetail" class="collapse show">
                        <!-- Input for location search -->

                        <div class="row justify-content-center">
                            @foreach($filters as $filter)
                            @if ($filter->filterName == "Min Bathrooms")
                            <div class="form-check form-check-inline mx-2">
                                <input class="form-check-input" type="radio" name="bathroomCount" id="bathroom1"
                                    value="{{$filter->filterValue}}">
                                <label class="form-check-label mr-2" for="bathroom1">{{$filter->filterValue}}</label>
                            </div>
                            @endif
                            @endforeach
                        </div>


                    </div>
                </div>

                <div class="under" style="border:solid 1px #eee; padding:0 10px;"></div>
                <!-- Floor Size -->
                <div class="sub">
                    <h5 class="sub-title" data-toggle="collapse" data-target="#floorSizeDetail" aria-expanded="true">
                        Floor Size
                        <i class="fa fa-angle-up icon-down" aria-hidden="true"></i>
                    </h5>
                    <div id="floorSizeDetail" class="collapse show">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Sq. Ft.</span>
                                    </div>
                                    <input type="text" class="form-control" id="minFloorSize" name="minFloorSize"
                                        placeholder="Minimum Floor Size">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Sq. Ft.</span>
                                    </div>
                                    <input type="text" class="form-control" id="maxFloorSize" name="maxFloorSize"
                                        placeholder="Maximum Floor Size">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="under" style="border:solid 1px #eee; padding:0 10px;"></div>
                <!-- Build Year -->
                <div class="sub">
                    <h5 class="sub-title" data-toggle="collapse" data-target="#buildYearDetail" aria-expanded="true">
                        Build Year
                        <i class="fa fa-angle-up icon-down" aria-hidden="true"></i>
                    </h5>
                    <div id="buildYearDetail" class="collapse show">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Year</span>
                                    </div>
                                    <input type="text" class="form-control" id="minBuildYear" name="minBuildYear"
                                        placeholder="Minimum Build Year">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Year</span>
                                    </div>
                                    <input type="text" class="form-control" id="maxBuildYear" name="maxBuildYear"
                                        placeholder="Maximum Build Year">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="under" style="border:solid 1px #eee; padding:0 10px;"></div>
                <!-- Furnishing -->

                <div class="sub">
                    <h5 class="sub-title" data-toggle="collapse" data-target="#furnishingDetail" aria-expanded="true">
                        Furnishing
                        <i class="fa fa-angle-up icon-down" aria-hidden="true"></i>
                    </h5>
                    <div id="furnishingDetail" class="collapse show">
                        <!-- Furnishing specific radio buttons -->

                        <div class="row justify-content-center">
                            @foreach($filters as $filter)
                            @if ($filter->filterAttribute == "Furnishing")
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="{{ $filter->filterAttribute }}"
                                    id="{{ $filter->filterAttribute }}" value="{{ $filter->filterValue }}">
                                <label class="form-check-label"
                                    for="{{ $filter->filterAttribute }}">{{$filter->filterValue}}</label>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="under" style="border:solid 1px #eee; padding:0 10px;"></div>
                <div class="sub">
                    <h5 class="sub-title" data-toggle="collapse" data-target="#postedDetails" aria-expanded="true">
                        Posted Date
                        <i class="fa fa-angle-up icon-down" aria-hidden="true"></i>
                    </h5>
                    <div id="postedDetails" class="collapse show">

                        <div class="row">
                            @foreach($filters as $filter)
                            @if ($filter->filterAttribute == "Posted Date")
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-check-inline mx-2">
                                    <input class="form-check-input" type="radio" name="postedDate" id="bathroom1"
                                        value="{{$filter->filterValue}}">
                                    <label class="form-check-label mr-2"
                                        for="postedDate">{{$filter->filterValue}}</label>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>


                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger" data-dismiss="modal">Apply Filter</button>
            </div>
            </form>
        </div>
    </div>
</div>



<script>
    
   function submitForm() {
        // Submit the form when the dropdown value changes
        document.getElementById("sortForm").submit();
    }

document.addEventListener("DOMContentLoaded", function() {

    $('.sub-title').on('click', function() {
        var arrowIcon = $(this).find('.fa');
        arrowIcon.toggleClass('fa-angle-down fa-angle-up');
    });
});
$(document).ready(function() {
    $("#openModalBtn").click(function() {
        console.log('Button clicked');
        $("#advancedFilterModal").modal('show');
    });

    $("#closeBtn").click(function() {
        $("#advancedFilterModal").modal('hide');
    });
});

var filters = <?php echo json_encode($filters); ?>;

$('input[name="propertyType"]').change(function() {
    var selectedPropertyType = $('input[name="propertyType"]:checked').val();

    // Check if the selected property type is "All"
    if (selectedPropertyType !== 'All') {
        // Filter the relevant filters based on the selected property type
        var filteredFilters = filters.filter(function(filter) {
            return filter.propertyType === selectedPropertyType;
        });

        // Display the filtered property list
        displayPropertyList(filteredFilters);
    } else {
        // If "All" is selected, you may choose to do nothing or handle it differently
        // For now, let's clear the propertyList div
        $('#propertyList').html('');
    }
});

$('input[name="roomType"]').change(function () {
            // Check if the selected Room Type is "unit"
            if ($(this).val() === 'Unit') {
                // If "unit" is selected, show the Bedroom section
                $('.bedroom-section').show();
            } else {
                // If a different Room Type is selected, hide the Bedroom section
                $('.bedroom-section').hide();
            }

        });
        

function displayPropertyList(propertyList) {
    // Update the propertyList div with the filtered data
    var html = '<ul class="list-unstyled">'; // Added "list-unstyled" class to remove list bullets
    propertyList.forEach(function(filter) {
        html += '<li>';
        html += '<div class="form-check">';
        html += '<input class="form-check-input" type="checkbox" name="selectedPropertyType[]" value="' + filter
            .filterValue + '" checked>'; // Set "checked" attribute to default to selected
        html += '<label class="form-check-label" for="propertyCheckbox' + filter.filterValue + '">' + filter
            .filterValue + '</label>';
        html += '</div>';
        html += '</li>';
    });
    html += '</ul>';
    $('#propertyList').html(html);
}

$('.wishlist-star').click(function(event) {
    event.stopPropagation();

    // Extract propertyID from the unique ID of the clicked wishlist icon
    var propertyId = $(this).attr('id').replace('wishlistStar', '');
    console.log(propertyId);
    $('#propertyID').val(propertyId);

    @if(session('tenant'))
    $('#wishlistForm' + propertyId).submit();
    @else
    alert('Please login first.');
    @endif
});
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBuCyWLDUnN2vQiE0fdBHoSmQhR9mbLvZ4&callback=initMap" async></script>
<script>

function toggleMap() {
        var mapContainer = document.getElementById("mapContainer");
        mapContainer.style.display = (mapContainer.style.display === "none") ? "block" : "none";
    }
    
 let map, activeInfoWindow, markers = [];

/* ----------------------------- Initialize Map ----------------------------- */
function initMap() {
    const initialMarkers = <?php echo json_encode($initialMarkers); ?>;
    console.log(initialMarkers);
    if(initialMarkers[0]!= null){
            for (let index = 0; index < initialMarkers.length; index++) {
                const markerData = initialMarkers[index];
                const marker = new google.maps.Marker({
                    position: markerData.position,
                    label: markerData.label,
                    draggable: markerData.draggable,
                    address: markerData.address,
                    map
                });
               
                
                map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: markerData.position.lat,
                    lng: markerData.position.lng,
                },
                zoom: 17
            });
            }
        }else{
            map = new google.maps.Map(document.getElementById("map"), {
                    center: {
                        lat: 3.0342324,
                        lng: 101.6170345,
                    },
                    zoom: 10
                });
        }
        

    map.addListener("click", function(event) {
        mapClicked(event);
    });

    initMarkers();
}
/* --------------------------- Initialize Markers --------------------------- */
function initMarkers() {
    const initialMarkers = <?php echo json_encode($initialMarkers); ?>;
    
    for (let index = 0; index < initialMarkers.length; index++) {
        const markerData = initialMarkers[index];
        const marker = new google.maps.Marker({
            position: markerData.position,
            label: markerData.label,
            draggable: markerData.draggable,
            address: markerData.address,
            map
        });
        
        markers.push(marker);
        const infowindow = new google.maps.InfoWindow({
            content: `<b>${markerData.address}</b>`,
        });
        marker.addListener("click", (event) => {
            if(activeInfoWindow) {
                activeInfoWindow.close();
            }
            infowindow.open({
                anchor: marker,
                shouldFocus: false,
                map
            });
            activeInfoWindow = infowindow;
            markerClicked(marker, index);
        });

        marker.addListener("dragend", (event) => {
            markerDragEnd(event, index);
        });
    }
}

function toggleMap() {
        var mapContainer = document.getElementById("mapContainer");
        mapContainer.style.display = (mapContainer.style.display === "none") ? "block" : "none";
    }

/* ------------------------- Handle Map Click Event ------------------------- */
function mapClicked(event) {
    console.log(map);
    console.log(event.latLng.lat(), event.latLng.lng());
}

/* ------------------------ Handle Marker Click Event ----------------------- */
function markerClicked(marker, index) {
    console.log(map);
    console.log(marker.position.lat());
    console.log(marker.position.lng());
}

/* ----------------------- Handle Marker DragEnd Event ---------------------- */
function markerDragEnd(event, index) {
    console.log(map);
    console.log(event.latLng.lat());
    console.log(event.latLng.lng());
}

</script>

@endsection