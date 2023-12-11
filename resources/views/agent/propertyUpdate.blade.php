<html>

<head>
    <meta charset="UTF-8">
    <title>Update Property</title>
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
    .hidden {
        display: none;
    }

    /* CSS to set a fixed size for the images */
    #uploadedImages img,
    #retrievedImages img,
    #photoPreview img {
        width: 100px;
        /* Adjust the width as needed */
        height: auto;
        /* Maintain aspect ratio */
        margin: 5px;
        /* Add spacing between images */
    }

    #uploadedImages button {
        background-color: red;
        color: white;
        border: none;
        padding: 5px;
        cursor: pointer;
    }

    .retrievedImages {}
    </style>
</head>

<body>
    @extends('layouts.adminApp')

    @section('content')
    <div class="container">
        <h2>Update Property</h2>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="33" aria-valuemin="0"
                aria-valuemax="100">Stage 1</div>
        </div>
        <form method="POST" action="{{ route('properties.update', $property->propertyID) }}"
            enctype="multipart/form-data">
            @csrf
            <!-- Stage 1: Fill in Property Form -->
            <div class="stage" id="stage-1">
                <h3>Stage 1 : Fill in Property Details</h3>

                <div class="row">
                    <!-- Property Name -->
                    <div class="form-group col-md-6">
                        <label for="propertyName">Property Name</label>
                        <input type="text" class="form-control @error('propertyName') is-invalid @enderror"
                            id="propertyName" name="propertyName" value="{{ $property->propertyName }}">
                        @error('propertyName')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Property Description -->
                    <div class="form-group col-md-12">
                        <label for="propertyDesc">Property Description</label>
                        <textarea class="form-control @error('propertyDesc') is-invalid @enderror" id="propertyDesc"
                            name="propertyDesc" rows="4">{{ $property->propertyDesc }}</textarea>
                        @error('propertyDesc')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                    <!-- Property Address -->
                    <div class="form-group col-md-6">
                        <label for="propertyAddress">Property Address</label>
                        <input type="text" class="form-control @error('propertyAddress') is-invalid @enderror"
                            id="propertyAddress" name="propertyAddress" value="{{ $property->propertyAddress }}">
                        @error('propertyAddress')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- State (Dropdown) -->
                    <div class="form-group col-md-6">
                        <label for="stateID">State</label>
                        <select class="form-control @error('stateID') is-invalid @enderror" id="stateID" name="stateID">
                            @foreach($states as $state)
                            <option value="{{ $state->stateID }}" {{ $state->stateID == $property->stateID ? 'selected'
                                : '' }}>{{ $state->stateName }}
                            </option>
                            @endforeach
                        </select>
                        @error('stateID')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="propertyType">Property Type</label>
                        <select class="form-control @error('propertyType') is-invalid @enderror" id="propertyType"
                            name="propertyType">
                            <option value="Residential apartment" {{ $property->propertyType =='Residential apartment'
                                    ? 'selected' : '' }}>Residential apartment</option>
                            <option value="House" {{ $property->propertyType =='House' ? 'selected' : '' }}>House
                            </option>
                            <option value="Condominium" {{ $property->propertyType =='Condominium' ? 'selected' : '' }}>
                                Condominium</option>
                            <option value="Commercial spaces" {{ $property->propertyType =='Commercial spaces'
                                    ? 'selected' : '' }}>Commercial spaces</option>
                        </select>
                        @error('propertyType')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Housing Type (Dropdown) based on Property Type -->
                    <div class="form-group col-md-6">
                        <label for="housingType">Housing Type</label>
                        @php
                        $housingTypes = [];
                        switch ($property->propertyType) {
                        case 'Residential apartment':
                        $housingTypes = ['Loft Apartment', 'Studio Apartment', 'Luxury Apartment', 'Garden Apartment',
                        'Duplex Apartment'];
                        break;
                        case 'Condominium':
                        $housingTypes = ['High-Rise Condominium', 'Low-Rise Condominium', 'Luxury Condominium',
                        'Executive Condominium'];
                        break;
                        case 'Commercial spaces':
                        $housingTypes = ['Terraced House', 'Detached House', 'Semi-Detached House', 'Bungalow House',
                        'Shop House'];
                        break;
                        case 'House':
                        $housingTypes = ['Retail Space', 'Office Building', 'Shopping Complex', 'Industrial Warehouse',
                        'Restaurant Space'];
                        break;
                        }
                        @endphp
                        <select class="form-control @error('housingType') is-invalid @enderror" id="housingType"
                            name="housingType">
                            @foreach($housingTypes as $type)
                            <option value="{{ $type }}" {{ $property->housingType == $type ? 'selected' : '' }}>
                                {{ $type }}</option>
                            @endforeach
                        </select>
                        @error('housingType')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Room Type (Dropdown) -->
                    <div class="form-group col-md-6">
                        <label for="roomType">Room Type</label>
                        <select class="form-control @error('roomType') is-invalid @enderror" id="roomType"
                            name="roomType">
                            @foreach(['Unit', 'Small Room', 'Medium Room', 'Big Medium Room', 'Master Room'] as $type)
                            <option value="{{ $type }}" {{ $property->roomType == $type ? 'selected' : '' }}>
                                {{ $type }}</option>
                            @endforeach
                        </select>
                        @error('roomType')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($property->roomType == 'Unit')
                    <!-- Prompt user for any number of bedrooms for Unit -->
                    <div class="form-group col-md-6">
                        <label for="bedroomNum">Number of Bedrooms</label>
                        <input type="number" class="form-control @error('bedroomNum') is-invalid @enderror"
                            id="bedroomNum" name="bedroomNum" value="{{ $property->bedroomNum }}">
                        @error('bedroomNum')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @else
                    <!-- Set number of bedrooms to 1 for other RoomTypes -->
                    <input type="hidden" name="bedroomNum" value="1">
                    @endif

                    <!-- Number of Bathrooms -->
                    <div class="form-group col-md-6">
                        <label for="bathroomNum">Number of Bathrooms</label>
                        <input type="number" class="form-control @error('bathroomNum') is-invalid @enderror"
                            id="bathroomNum" name="bathroomNum" value="{{ $property->bathroomNum }}">
                        @error('bathroomNum')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                    <!-- Property Size (Square Feet) -->
                    <div class="form-group col-md-6">
                        <label for="squareFeet">Property Size (Square Feet)</label>
                        <input type="number" class="form-control @error('squareFeet') is-invalid @enderror"
                            id="squareFeet" name="squareFeet" value="{{ $property->squareFeet }}">
                        @error('squareFeet')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Furnishing Type (Radio Buttons) -->
                    <div class="form-group col-md-6">
                        <label>Furnishing Type</label><br>
                        @foreach(['Fully Furnished', 'Partial Furnished', 'Unfurnished'] as $type)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="furnishingType"
                                id="{{ strtolower($type) }}" value="{{ $type }}" {{ $property->furnishingType == $type ?
                            'checked' : '' }}>
                            <label class="form-check-label" for="{{ strtolower($type) }}">{{ $type }}</label>
                        </div>
                        @endforeach
                        @error('furnishingType')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Build Year -->
                    <div class="form-group col-md-6">
                        <label for="buildYear">Build Year</label>
                        <input type="number" class="form-control @error('buildYear') is-invalid @enderror"
                            id="buildYear" name="buildYear" value="{{ $property->buildYear }}">
                        @error('buildYear')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Rental Amount -->
                    <div class="form-group col-md-6">
                        <label for="rentalAmount">Rental Amount (MYR)</label>
                        <input type="number" step="0.01"
                            class="form-control @error('rentalAmount') is-invalid @enderror" id="rentalAmount"
                            name="rentalAmount" value="{{ $property->rentalAmount }}">
                        @error('rentalAmount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deposit Amount -->
                    <div class="form-group col-md-6">
                        <label for="depositAmount">Deposit Amount (MYR)</label>
                        <input type="number" step="0.01"
                            class="form-control @error('depositAmount') is-invalid @enderror" id="depositAmount"
                            name="depositAmount" value="{{ $property->depositAmount }}">
                        @error('depositAmount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Property Availability (Radio Buttons) -->
                    <div class="form-group col-md-6">
                        <label>Property Availability</label><br>
                        @foreach([1 => 'Available', 0 => 'Not Available'] as $value => $label)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="propertyAvailability"
                                id="{{ strtolower($label) }}" value="{{ $value }}" {{ $property->propertyAvailability ==
                            $value ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ strtolower($label) }}">{{ $label }}</label>
                        </div>
                        @endforeach
                        @error('propertyAvailability')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                    </div>
                    </br>
                    </br>

                    <!-- Facilities (Checklist) -->
                    <div class="form-group col-md-6">
                        <label>Facilities (Check all that apply)</label><br>
                        @foreach($facilities->slice(0, 40) as $facility)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="facilities[]"
                                value="{{ $facility->facilityID }}" {{ in_array($facility->facilityID,
                            $property->propertyFacilities->pluck('facilityID')->toArray()) ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $facility->facilityID }}">
                                <i class="las {{ $facility->facilityIcon }}"></i> {{ $facility->facilityName }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <!-- Unit Features (Checklist) -->
                    <div class="form-group col-md-6">
                        <label>Unit Features (Check all that apply)</label><br>
                        @foreach($facilities->slice(40) as $facility)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="facilities[]"
                                value="{{ $facility->facilityID }}" {{ in_array($facility->facilityID,
                            $property->propertyFacilities->pluck('facilityID')->toArray()) ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $facility->facilityID }}">
                                <i class="las {{ $facility->facilityIcon }}"></i> {{ $facility->facilityName }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="btn btn-primary" id="next-stage-2">Next</button>
            </div>

            <!-- Stage 2: Upload Photos -->
            <div class="stage hidden" id="stage-2">
                <h3>Stage 2: Upload Photos</h3>

                <!-- Property Photos (File Upload) -->
                <div class="form-group col-md-6">
                    <label for="propertyPhotos">Property Photos</label>
                    <input type="file" class="form-control" id="propertyPhotos" name="propertyPhotos[]" accept="image/*"
                        multiple>
                </div>

                <!-- Display retrieved images -->
                <div id="retrievedImages" class="retrievedImages">

                    @foreach($property->propertyPhotos as $index => $photo)
                    <div>
                        <img src="{{ Storage::url($photo->propertyPath) }}" class="img-thumbnail"
                            alt="Property Photo {{ $index + 1 }}">
                    </div>
                    @endforeach
                </div>

                <!-- Display uploaded images -->
                <div id="uploadedImages"></div>

                <!-- Next button to move to Stage 3 -->
                <button type="button" class="btn btn-primary" id="next-stage-3">Next</button>
                <button type="button" class="btn btn-secondary" id="previous-stage-1">Previous</button>

                <script>
                // Function to display uploaded images
                function displayImages(event) {
                    const fileInput = event.target;
                    const uploadedImagesDiv = document.getElementById('uploadedImages');
                    const previewContainer = document.getElementById('photoPreview');


                    // Clear the previous images
                    uploadedImagesDiv.innerHTML = '';

                    // Display each selected image
                    for (const file of fileInput.files) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.alt = file.name;
                        uploadedImagesDiv.appendChild(img);

                        // Add a button to remove the image
                        const removeButton = document.createElement('button');
                        removeButton.textContent = 'X';

                        removeButton.addEventListener('click', function() {
                            img.remove();
                            removeButton.remove();
                            fileInput.value = '';
                            previewContainer.innerHTML =
                            ''; // Clear the input to allow re-uploading the same file
                        });
                        uploadedImagesDiv.appendChild(removeButton);
                    }
                }

                // Attach the displayImages function to the change event of the file input
                document.getElementById('propertyPhotos').addEventListener('change', displayImages);
                </script>
            </div>

            <!-- Stage 3: Preview -->
            <div class="stage hidden" id="stage-3">
                <h3>Stage 3: Preview</h3>
                <!-- Property Preview Container -->
                <div id="propertyPreview" class="card">
                    <div class="card-header">
                        <h4 class="card-title">Property Preview</h4>
                    </div>
                    <div class="card-body">
                        <!-- Property Name Preview -->
                        <p><strong>Property Name:</strong> <span id="previewPropertyName"></span></p>

                        <!-- Property Description Preview -->
                        <p><strong>Property Description:</strong> <span id="previewPropertyDesc"></span></p>

                        <!-- Property Address Preview -->
                        <p><strong>Property Address:</strong> <span id="previewPropertyAddress"></span></p>

                        <!-- State Preview -->
                        <p><strong>State:</strong> <span id="previewState"></span></p>

                        <!-- Number of Bedrooms Preview -->
                        <p><strong>Number of Bedrooms:</strong> <span id="previewBedroomNum"></span></p>

                        <!-- Number of Bathrooms Preview -->
                        <p><strong>Number of Bathrooms:</strong> <span id="previewBathroomNum"></span></p>

                        <!-- Property Type Preview -->
                        <p><strong>Property Type:</strong> <span id="previewPropertyType"></span></p>

                        <!-- Housing Type Preview -->
                        <p><strong>Housing Type:</strong> <span id="previewHousingType"></span></p>

                        <!-- Room Type Preview -->
                        <p><strong>Room Type:</strong> <span id="previewRoomType"></span></p>

                        <!-- Property Size Preview -->
                        <p><strong>Property Size (Square Feet):</strong> <span id="previewSquareFeet"></span></p>

                        <!-- Furnishing Type Preview -->
                        <p><strong>Furnishing Type:</strong> <span id="previewFurnishingType"></span></p>

                        <!-- Build Year Preview -->
                        <p><strong>Build Year:</strong> <span id="previewBuildYear"></span></p>

                        <!-- Rental Amount Preview -->
                        <p><strong>Rental Amount (MYR):</strong> <span id="previewRentalAmount"></span></p>

                        <!-- Deposit Amount Preview -->
                        <p><strong>Deposit Amount (MYR):</strong> <span id="previewDepositAmount"></span></p>

                        <!-- Property Availability Preview -->
                        <p><strong>Property Availability:</strong> <span id="previewPropertyAvailability"></span></p>

                        <!-- Facilities Preview -->
                        <p><strong>Selected Facilities:</strong> <span id="previewFacilities"></span></p>

                        <!-- Photo Preview -->
                        <p><strong>Property Photos:</strong></p>
                        <div id="photoPreview"></div>
                        <!-- Display retrieved images -->
                        <div id="retrievedImages" class="retrievedImages">

                            @foreach($property->propertyPhotos as $index => $photo)
                            <div>
                                <img src="{{ Storage::url($photo->propertyPath) }}" class="img-thumbnail"
                                    alt="Property Photo {{ $index + 1 }}">
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
                <p><span style="color:red;">Notes :</span> Please make sure all the property information is correct
                    before proceed to next stage.</p>

                <!-- Next button to move to Stage 4 or Previous button to go back to Stage 2 -->
                <input type="submit" class="btn btn-success" id="confirm" value="Confirm Update">
                <button type="button" class="btn btn-secondary" id="previous-stage-3">Previous</button>
            </div>

            <script>
            // Function to update the preview based on user input
            function updatePreview() {
                // Update Property Name Preview
                document.getElementById('previewPropertyName').innerText = document.getElementById('propertyName')
                    .value;

                // Update Property Description Preview
                document.getElementById('previewPropertyDesc').innerText = document.getElementById('propertyDesc')
                    .value;

                // Update Property Address Preview
                document.getElementById('previewPropertyAddress').innerText = document.getElementById('propertyAddress')
                    .value;

                // Update State Preview
                document.getElementById('previewState').innerText = document.getElementById('stateID').options[document
                    .getElementById('stateID').selectedIndex].text;

                // Update Number of Bedrooms Preview
                document.getElementById('previewBedroomNum').innerText = document.getElementById('bedroomNum').value;

                // Update Number of Bathrooms Preview
                document.getElementById('previewBathroomNum').innerText = document.getElementById('bathroomNum').value;

                // Update Property Type Preview
                document.getElementById('previewPropertyType').innerText = document.getElementById('propertyType')
                    .value;

                // Update Room Type Preview
                document.getElementById('previewRoomType').innerText = document.getElementById('roomType')
                    .value;

                // Update Housing Type Preview
                document.getElementById('previewHousingType').innerText = document.getElementById('housingType')
                    .value;


                // Update Property Size Preview
                document.getElementById('previewSquareFeet').innerText = document.getElementById('squareFeet').value;

                // Update Furnishing Type Preview
                document.getElementById('previewFurnishingType').innerText = document.querySelector(
                    'input[name="furnishingType"]:checked').value;

                // Update Build Year Preview
                document.getElementById('previewBuildYear').innerText = document.getElementById('buildYear').value;

                // Update Rental Amount Preview
                document.getElementById('previewRentalAmount').innerText = document.getElementById('rentalAmount')
                    .value;

                // Update Deposit Amount Preview
                document.getElementById('previewDepositAmount').innerText = document.getElementById('depositAmount')
                    .value;

                // Update Property Availability Preview
                document.getElementById('previewPropertyAvailability').innerText = document.querySelector(
                    'input[name="propertyAvailability"]:checked').value === '1' ? 'Available' : 'Not Available';
            }

            // Function to handle file input changes
            function handleFileInput() {
                const input = document.getElementById('propertyPhotos');
                const previewContainer = document.getElementById('photoPreview');

                // Clear previous previews
                previewContainer.innerHTML = '';

                // Display preview for each selected file
                for (const file of input.files) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail';
                        previewContainer.appendChild(img);
                    };

                    reader.readAsDataURL(file);
                }
            }

            // Event listener for file input changes
            document.getElementById('propertyPhotos').addEventListener('change', handleFileInput);

            // Event listeners for input changes
            document.getElementById('propertyName').addEventListener('input', updatePreview);
            document.getElementById('propertyDesc').addEventListener('input', updatePreview);
            document.getElementById('propertyAddress').addEventListener('input', updatePreview);
            document.getElementById('stateID').addEventListener('change', updatePreview);
            document.getElementById('bedroomNum').addEventListener('input', updatePreview);
            document.getElementById('bathroomNum').addEventListener('input', updatePreview);
            document.getElementById('propertyType').addEventListener('change', updatePreview);
            document.getElementById('housingType').addEventListener('change', updatePreview);
            document.getElementById('roomType').addEventListener('change', updatePreview);
            document.getElementById('squareFeet').addEventListener('input', updatePreview);
            document.querySelectorAll('input[name="furnishingType"]').forEach(function(radio) {
                radio.addEventListener('change', updatePreview);
            });
            document.getElementById('buildYear').addEventListener('input', updatePreview);
            document.getElementById('rentalAmount').addEventListener('input', updatePreview);
            document.getElementById('depositAmount').addEventListener('input', updatePreview);
            document.querySelectorAll('input[name="propertyAvailability"]').forEach(function(radio) {
                radio.addEventListener('change', updatePreview);
            });

            // Function to update the number of bedrooms based on Room Type
            document.getElementById('roomType').addEventListener('change', function() {
                const bedroomNumInput = document.getElementById('bedroomNum');
                bedroomNumInput.value = this.value === 'Unit' ? '' : '1';
                bedroomNumInput.readOnly = this.value !== 'Unit';
            });

            // Function to handle property type changes
            function handlePropertyTypeChange() {
                const propertyType = document.getElementById('propertyType').value;
                const roomTypeSelect = document.getElementById('roomType');
                const housingTypeSelect = document.getElementById('housingType');

                // Reset options
                roomTypeSelect.innerHTML = '';
                housingTypeSelect.innerHTML = '';

                // Define options based on property type
                let roomTypeOptions = ['Unit', 'Small Room', 'Medium Room', 'Big Medium Room', 'Master Room'];
                let housingTypeOptions = [];

                switch (propertyType) {
                    case 'Residential apartment':
                        housingTypeOptions = ['Loft Apartment', 'Studio Apartment', 'Luxury Apartment',
                            'Garden Apartment', 'Duplex Apartment'
                        ];
                        break;
                    case 'Condominium':
                        housingTypeOptions = ['High-Rise Condominium', 'Low-Rise Condominium', 'Luxury Condominium',
                            'Executive Condominium'
                        ];
                        break;
                    case 'Commercial spaces':
                        housingTypeOptions = ['Terraced House', 'Detached House', 'Semi-Detached House',
                            'Bungalow House', 'Shop House'
                        ];
                        break;
                    case 'House':
                        housingTypeOptions = ['Retail Space', 'Office Building', 'Shopping Complex',
                            'Industrial Warehouse', 'Restaurant Space'
                        ];
                        break;
                }

                // Populate Room Type dropdown
                roomTypeOptions.forEach(option => {
                    const newOption = document.createElement('option');
                    newOption.value = option;
                    newOption.text = option;
                    roomTypeSelect.add(newOption);
                });

                // Populate Housing Type dropdown
                housingTypeOptions.forEach(option => {
                    const newOption = document.createElement('option');
                    newOption.value = option;
                    newOption.text = option;
                    housingTypeSelect.add(newOption);
                });


            }

            // Event listener for property type changes
            document.getElementById('propertyType').addEventListener('change', handlePropertyTypeChange);

            function updateFacilitiesPreview() {
                // Get all checked checkboxes
                var selectedFacilities = document.querySelectorAll('input[name="facilities[]"]:checked');
                // Extract the facility names and join them with a comma
                var selectedFacilityNames = Array.from(selectedFacilities).map(function(checkbox) {
                    return checkbox.nextElementSibling.innerText.trim();
                }).join(', ');

                // Update Facilities Preview
                document.getElementById('previewFacilities').innerText = selectedFacilityNames;
            }

            // Event listeners for checkbox changes
            var checkboxes = document.querySelectorAll('input[name="facilities[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateFacilitiesPreview);
            });
            </script>


            <!-- Stage 4: Completed -->
            <div class="stage hidden" id="stage-4">
                <!-- Confirm button to move to Stage 5 or Previous button to go back to Stage 3 -->

                <h3>Update Completed</h3>
                <!-- Completion message and option to start a new property posting -->
                <a href="{{ route('properties') }}" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>

        </form>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
        // JavaScript and jQuery code for handling stage transitions
        $(document).ready(function() {
            function updateProgressBar(stage) {
                // Define the total number of stages in your form
                const totalStages = 4;

                // Calculate the new width for the progress bar
                const newWidth = (stage / totalStages) * 100 + '%';

                // Update the progress bar width
                $('.progress-bar').css('width', newWidth);

                // Update the progress text (optional)
                $('.progress-bar').text('Stage ' + stage);
            }
            $('#next-stage-2').click(function() {
                // Move to Stage 2
                $('#stage-1').addClass('hidden');
                $('#stage-2').removeClass('hidden');
                updateProgressBar(2);
                updatePreview();
                updateFacilitiesPreview();
            });

            $('#next-stage-3').click(function() {
                // Move to Stage 3
                $('#stage-2').addClass('hidden');
                $('#stage-3').removeClass('hidden');
                updateProgressBar(3);
                updatePreview();
                updateFacilitiesPreview();
            });

            $('#previous-stage-1').click(function() {
                // Go back to Stage 1
                $('#stage-2').addClass('hidden');
                $('#stage-1').removeClass('hidden');
                updateProgressBar(1);
            });

            $('#previous-stage-2').click(function() {
                // Go back to Stage 2
                $('#stage-3').addClass('hidden');
                $('#stage-2').removeClass('hidden');
                updateProgressBar(2);
            });

            $('#previous-stage-3').click(function() {
                // Go back to Stage 3
                $('#stage-3').addClass('hidden');
                $('#stage-2').removeClass('hidden');
                updateProgressBar(3);
            });



        });

        document.getElementById('confirm').addEventListener('click', function() {
            document.querySelector('form').submit();
        });
        </script>
    </div>

    @endsection
</body>

</html>