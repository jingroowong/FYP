@extends('layouts.header')
<link rel="stylesheet" href="{{ asset('/storage/css/Wishlist.css') }}" media="screen">
<link rel="stylesheet" href="{{ asset('/storage/css/SearchProperty.css') }}" media="screen">

@section('content')
<div class="container-fluid" style="margin-left:20px; ">
    <div class="row">
        <div class="col-md-1 custom-offset">
            <div class="mb-3">
                <label for="text" style="font-weight:bold; margin-bottom: 10px;">Search Property Here</label>
                <i class="fa fa-map" style="color: #007bff; float: right; margin-top: 7px;"></i>
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
                <select class="form-control custom-dropdown" id="sort_by" name="sort_by">
                    <option value="" selected>Sort by</option>
                    <option value="latest">Sort by Latest</option>
                    <option value="rating">Sort by Rating</option>
                    <option value="pricing">Sort by Pricing</option>
                </select>
            </div>

            @foreach($properties as $property)
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="{{ Storage::url($property->propertyPhotos[0]->propertyPath) }}"
                                class="card-img-top" alt="Property Image">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ $property->propertyName }}</h5>
                                <p class="card-text">{{ $property->propertyType }}</p>
                                <p class="card-text">{{ $property->description }}</p>
                                <p class="card-text">Rental Amount: ${{ $property->rentalAmount }}</p>
                                <a href="{{ route('properties.show', $property->propertyID)}}"
                                    class="btn btn-primary">View Details</a>
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
            <div class="under" style="border:solid 1px #eee; padding:3px 10px;"></div>

            <!-- Modal Body -->
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">

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
                            <label class="btn btn-dark active mr-2 rounded-2">
                                <input type="radio" name="propertyType" id="allPropertyType" checked> All
                            </label>
                            <label class="btn btn-dark mr-2 rounded-2">
                                <input type="radio" name="propertyType" id="housePropertyType"> House
                            </label>
                            <label class="btn btn-dark mr-2 rounded-2">
                                <input type="radio" name="propertyType" id="condoPropertyType"> Condominium
                            </label>
                            <label class="btn btn-dark mr-2 rounded-2">
                                <input type="radio" name="propertyType" id="commercialPropertyType"> Commercial Space
                            </label>
                            <label class="btn btn-dark rounded-2">
                                <input type="radio" name="propertyType" id="residentialPropertyType"> Residential
                                Apartment
                            </label>
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
                    <h5 class="sub-title" data-toggle="collapse" data-target="#housingDetail" aria-expanded="true">
                        Housing Type
                        <i class="fa fa-angle-up icon-down" aria-hidden="true"></i>
                    </h5>
                    <div id="housingDetail" class="collapse show">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="housingType" id="house"
                                        value="house">
                                    <label class="form-check-label" for="house">House</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="housingType" id="condominium"
                                        value="condominium">
                                    <label class="form-check-label" for="condominium">Condominium</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="housingType" id="commercialSpace"
                                        value="commercialSpace">
                                    <label class="form-check-label" for="commercialSpace">Commercial Space</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="housingType"
                                        id="residentialApartment" value="residentialApartment">
                                    <label class="form-check-label" for="residentialApartment">Residential
                                        Apartment</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="under" style="border:solid 1px #eee; padding:0 10px;"></div>
                <!-- Bedroom -->
                <div class="sub">
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
        <input type="text" class="form-control mb-3" id="address" name="location"
            placeholder="Search by location name, state ....">

        <!-- Radio buttons for selecting the number of bathrooms (1 to 5) -->
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bathroomCount" id="bathroom1" value="1">
            <label class="form-check-label" for="bathroom1">1</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bathroomCount" id="bathroom2" value="2">
            <label class="form-check-label" for="bathroom2">2</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bathroomCount" id="bathroom3" value="3">
            <label class="form-check-label" for="bathroom3">3</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bathroomCount" id="bathroom4" value="4">
            <label class="form-check-label" for="bathroom4">4</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bathroomCount" id="bathroom5" value="5">
            <label class="form-check-label" for="bathroom5">5</label>
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
                    <h5 class="sub-title" data-toggle="collapse" data-target="#{{ $filterValue }}Detail"
                        aria-expanded="true">
                        {{ $filterName }}
                        <i class="fa fa-angle-up icon-down" aria-hidden="true"></i>
                    </h5>
                    <div id="{{ $filterValue }}Detail" class="collapse show">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            @foreach ($filterOptions as $option)
                            <label class="btn btn-outline-primary mx-2" style="border-radius: 0.25rem;">
                                <input type="radio" name="{{ $filterValue }}" id="{{ $option }}" value="{{ $option }}">
                                {{ $option }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<script>
document.addEventListener("DOMContentLoaded", function() {
    $('.sub-title').on('click', function() {
        var arrowIcon = $(this).find('.fa');
        arrowIcon.toggleClass('fa-angle-down fa-angle-up');
    });
});
$(document).ready(function() {
    $("#openModalBtn").click(function() {
        $.ajax({
            url: '/get-filters', // Update with your actual route
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Handle the response, which contains the filters
                console.log(response.filters);
                // Populate your modal with the filter data
            },
            error: function(error) {
                console.error('Error fetching filters:', error);
            }
        });
        $("#advancedFilterModal").modal('show');
    });

    $("#closeBtn").click(function() {
        $("#advancedFilterModal").modal('hide');
    });
});
</script>


@endsection