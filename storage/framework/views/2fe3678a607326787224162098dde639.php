<link rel="stylesheet" href="<?php echo e(asset('/storage/css/CompareProperty.css')); ?>" media="screen">
<link rel="stylesheet"
    href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

<?php $__env->startSection('content'); ?>
<div class="text-center compare-title">
    <h2>Compare Rent Properties from Wish List</h2>
    <p>Please select rent properties that you want to compare in your wishlist</p>
</div>

<div class="compare-container">
    <div class="row">
        <div class="col selected-compare" id="property1">
            <img id="plus-img1" src="<?php echo e(asset('storage/images/plus.png')); ?>"
                onclick="toggleCompare('<?php echo e(session('tenant')->tenantID); ?>', 1)">
            <p>Select Rental Property</p>
        </div>
        <div class="col selected-compare" id="property2">
            <img id="plus-img2" src="<?php echo e(asset('storage/images/plus.png')); ?>"
                onclick="toggleCompare('<?php echo e(session('tenant')->tenantID); ?>', 2)">
            <p>Select Rental Property</p>
        </div>
        <div class="col selected-compare" id="property3">
            <img id="plus-img3" src="<?php echo e(asset('storage/images/plus.png')); ?>"
                onclick="toggleCompare('<?php echo e(session('tenant')->tenantID); ?>', 3)">
            <p>Select Rental Property</p>
        </div>
        <div class="col">
            <button class="btn btn-primary compare-button" onclick="compareProperties()">Compare</button>
        </div>
    </div>
</div>

<div id="attributes" style="display: none;">
    <table class="attributes-table">
        <tbody>
            <tr>
                <td class="col-md-3">Property Outlook</td>
                <td id="outlook-p1"></td>
                <td id="outlook-p2"></td>
                <td id="outlook-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">Property Name</td>
                <td id="property-name-p1"></td>
                <td id="property-name-p2"></td>
                <td id="property-name-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">Property Address</td>
                <td id="property-address-p1"></td>
                <td id="property-address-p2"></td>
                <td id="property-address-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">State</td>
                <td id="state-p1"></td>
                <td id="state-p2"></td>
                <td id="state-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">Porperty Description</td>
                <td id="property-description-p1"></td>
                <td id="property-description-p2"></td>
                <td id="property-description-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">Build Year</td>
                <td id="build-year-p1"></td>
                <td id="build-year-p2"></td>
                <td id="build-year-p3"></td>
            </tr>
            <!-- Rent Property's detail information -->
            <tr>
                <th colspan="4">Rent Property's detail information</th>
            </tr>
            <tr>
                <td class="col-md-3">Property Type</td>
                <td id="property-type-p1"></td>
                <td id="property-type-p2"></td>
                <td id="property-type-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">Number of Bedrooms</td>
                <td id="bedroom-count-p1"></td>
                <td id="bedroom-count-p2"></td>
                <td id="bedroom-count-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">Number of Bathroom</td>
                <td id="bathroom-count-p1"></td>
                <td id="bathroom-count-p2"></td>
                <td id="bathroom-count-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">Furnising Type</td>
                <td id="furnishing-type-p1"></td>
                <td id="furnishing-type-p2"></td>
                <td id="furnishing-type-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">Facilities</td>
                <td id="facilities-p1"></td>
                <td id="facilities-p2"></td>
                <td id="facilities-p3"></td>
            </tr>
            <!-- About Pricing -->
            <tr>
                <th colspan="4">About Pricing</th>
            </tr>
            <tr>
                <td class="col-md-3">Rental Fee per month</td>
                <td id="rental-fee-p1"></td>
                <td id="rental-fee-p2"></td>
                <td id="rental-fee-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">Deposit Amount</td>
                <td id="deposit-amount-p1"></td>
                <td id="deposit-amount-p2"></td>
                <td id="deposit-amount-p3"></td>
            </tr>
            <!-- Make Enquire -->
            <tr>
                <th colspan="4">Make Enquire</th>
            </tr>
            <tr>
                <td class="col-md-3">Agent Name</td>
                <td id="agent-name-p1"></td>
                <td id="agent-name-p2"></td>
                <td id="agent-name-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">Agent Email</td>
                <td id="agent-email-p1"></td>
                <td id="agent-email-p2"></td>
                <td id="agent-email-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">Agent Phone</td>
                <td id="agent-phone-p1"></td>
                <td id="agent-phone-p2"></td>
                <td id="agent-phone-p3"></td>
            </tr>
            <tr>
                <td class="col-md-3">Agent Overall Review</td>
                <td id="agent-review-p1"></td>
                <td id="agent-review-p2"></td>
                <td id="agent-review-p3"></td>
            </tr>
        </tbody>
    </table>
</div>


<div class="modal" id="wishlistDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="min-width:750px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Rent Properties</h5>
                <button type="button" id="compareCloseBtn" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="wishlistDetailsModalBody">
                <!-- Your modal body content here -->
            </div>
        </div>
    </div>
</div>

<script>
let selectedProperties = {};
let wishlist = [];

async function toggleCompare(tenantID, propertyNumber) {
    try {
        // Fetch the user's wishlist from the server
        const response = await fetch(`/api/${tenantID}/wishLists`);

        // Check if the request was successful (status code 200)
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        // Parse the JSON response
        wishlist = await response.json();


        // Reference to the modal body
        const modalBody = document.getElementById('wishlistDetailsModalBody');

        // Check if the wishlist is not empty
        if (wishlist.length > 0) {
            // Clear the modal body
            modalBody.innerHTML = '';

            wishlist.forEach((property) => {
                // Create a new property container
                const propertyContainer = document.createElement('div');


                // Fill the property container with data
                propertyContainer.innerHTML = `
                    <div class="latest-property-container" onclick="selectProperty('${property.propertyID}', '${propertyNumber}', '${property.propertyPhotoPath}')">
                            <div class="latest-property">
                                <div class="latest-property-image">
                                    <img id="propertyImage${propertyNumber}" src="<?php echo e(asset('storage/') . '/'); ?>${property.propertyPhotoPath}" alt="Property Image">
                                    <div class="image-overlay">
                                        <p class="photo-count"><i class="fas fa-image"></i> <span id="photoCount">${property.photos_count}</span> Photos</p>
                                    </div>
                                </div>
                                <div class="latest-property-details">
                                    <!-- Fill other property details dynamically -->
                                    <p class="latest-property-name" id="propertyName">${property.propertyName}</p>
                                    <p class="latest-property-address" id="propertyAddress">${property.propertyAddress}</p>
                                    <p class="latest-property-price" id="rentAmount">For Rent: <span id="rentAmount">RM${property.rentalAmount}</span></p>
                                    <div class="latest-property-filter">
                                        <p><i class="las la-city"></i><span id="propertyType">${property.propertyType}</span></p>
                                        <p><i class="las la-tools"></i><span id="propertyFacility">${property.furnishingType}</span></p>
                                        <p><i class="lab la-buffer"></i>${property.squareFeet} SQ.FT</p>
                                        <p><i class="las la-flag"></i>${property.stateName}</p>
                                        <p><i class="las la-bed"></i><span id="bedroomCount">${property.bedroomNum}</span></p>
                                        <p><i class="las la-bath"></i><span id="bathroomCount">${property.bathroomNum}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                // Append the property container to the modal body
                modalBody.appendChild(propertyContainer);
            });
        } else {
            // Display a message if the wishlist is empty
            modalBody.innerHTML = 'Your wishlist is empty.';
        }

        // Open the modal
        $('#wishlistDetailsModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#wishlistDetailsModal').modal('show');
    } catch (error) {
        console.error('Error fetching wishlist:', error);
    }
}

function selectProperty(propertyID, propertyNumber, propertyPath) {
    // Now you can use the global wishlist variable
    selectedProperties[propertyNumber] = {
        propertyID,
        propertyPath,
        propertyDetails: wishlist.find(property => property.propertyID === propertyID),
    };

    // Update the image for the selected property
    const plusIconImage = document.getElementById(`plus-img${propertyNumber}`);
    plusIconImage.src = `<?php echo e(asset('storage/') . '/'); ?>${propertyPath}`;
    $('#wishlistDetailsModal').modal('hide');
}

function compareProperties() {
    console.log(selectedProperties);

    if (Object.keys(selectedProperties).length === 0) {
        alert('Please select at least one property for comparison.');
        return; // Exit the function if no properties are selected
    }

    // Reference to the comparison table
    const comparisonTable = document.getElementById('attributes');

    // Reference to the table body where content will be added
    const tableBody = comparisonTable.querySelector('tbody');

    // Clear the table body
    tableBody.innerHTML = '';

    // Create a mapping between user-friendly attribute names and actual attribute names for each section
    const attributeMappings = {
        'About Selected Rent Properties': {
            'Property Outlook': 'propertyPath',
            'Property Name': 'propertyName',
            'Property Address': 'propertyAddress',
            'State': 'stateName',
            'Property Description': 'propertyDesc',
            'Build Year': 'buildYear',
        },
        'Rent Property details information': {
            'Property Type': 'propertyType',
            'Number of bedrooms': 'bedroomNum',
            'Number of bathroom': 'bathroomNum',
            'Furnishing Type': 'furnishingType',
            'Housing Type': 'housingType',
            'Room Type': 'roomType',
            'Facilities': 'facilityName',
            'Overall Rating': 'propertyAverageRating',

        },
        'About Pricing': {
            'Rental Fee per month (RM)': 'rentalAmount',
            'Deposit Amount (RM)': 'depositAmount',
        },
        'Make Enquiry': {
            'Agent Name': 'agentName',
            'Agent Email': 'agentEmail',
            'Agent Phone': 'agentPhone',
            'Agent Overall Rating': 'agentAverageRating',
        },
        // Add more sections as needed
    };

    console.log('selectedProperties[1]:', selectedProperties[1]);

    console.log('propertyDetails.propertyID:', selectedProperties[1]?.propertyDetails?.propertyID);
    // Loop through each section
    for (const sectionKey of Object.keys(attributeMappings)) {
        const section = attributeMappings[sectionKey];

        // Add a header row for the section
        const sectionHeaderRow = document.createElement('tr');
        const sectionHeaderCell = document.createElement('th');
        sectionHeaderCell.colSpan = 4; // Set the appropriate colspan for your design
        sectionHeaderCell.innerText = sectionKey;
        sectionHeaderRow.appendChild(sectionHeaderCell);
        tableBody.appendChild(sectionHeaderRow);

        // Loop through each attribute in the section
        for (const attributeName of Object.keys(section)) {
            // Create a new table row
            const tableRow = document.createElement('tr');

            // Fill the first cell with the user-friendly attribute name
            const attributeCell = document.createElement('td');
            attributeCell.className = 'col-md-3';
            attributeCell.innerText = attributeName;
            tableRow.appendChild(attributeCell);

            // Add cells for each selected property
            for (let i = 1; i <= 3; i++) {
                if (selectedProperties[i]) {
                    const propertyCell = document.createElement('td');
                    propertyCell.id = `${section[attributeName].toLowerCase()}-p${i}`;

                    // Get the attribute value from the selected property's propertyDetails
                    let attributeValue =
                        attributeName === 'Facilities' ?
                        createFacilitiesList(selectedProperties[i].propertyDetails[section[attributeName]]) :
                        attributeName === 'Property Outlook' ?
                        `<img src="<?php echo e(asset('storage/') . '/'); ?>${selectedProperties[i].propertyPath}" style="height:150px; width:285px;" alt="Property Outlook">` :
                        selectedProperties[i].propertyDetails[section[attributeName]] || '';

                    // If the attribute is 'Overall Rating' or 'Agent Overall Rating', append '/5' to the value
                    if (attributeName.includes('Overall Rating') && attributeValue !== null && attributeValue > 0) {
                        attributeValue = `${attributeValue}/5.0`;
                    }

                    // Set the inner HTML of the property cell
                    propertyCell.innerHTML = attributeValue;

                    // Append the property cell to the table row
                    tableRow.appendChild(propertyCell);
                }
            }

            // Append the table row to the table body
            tableBody.appendChild(tableRow);

        }
    }

    // Show the comparison table
    comparisonTable.style.display = 'block';
}

document.getElementById('compareCloseBtn').addEventListener('click', function() {
    $('#wishlistDetailsModal').modal('hide');
});

function createFacilitiesList(facilitiesString) {
    const facilities = facilitiesString.split(',').map(facility => facility.trim());
    let listHTML = '<ul class="facility-list">';

    for (const facility of facilities) {
        // Check if facility is not an empty string
        if (facility !== "") {
            listHTML += `<li>${facility}</li>`;
        }
    }

    listHTML += '</ul>';
    return listHTML;
}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/tenant/CompareWishList.blade.php ENDPATH**/ ?>