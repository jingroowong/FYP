<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo e(asset('/storage/css/ViewAgent.css')); ?>" media="screen">
    <link rel="stylesheet" href="<?php echo e(asset('/storage/css/AgentMenu.css')); ?>" media="screen">
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <title>View Agents</title>
    <style>
    .agent {
        width: 90%;
    }
    </style>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2 agent container">
        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back
        </a>

     
            <div class="agent-detail-top"
                style="background-image: url('<?php echo e(asset('storage/images/aBackground.png')); ?>');">
                <div class="agent-detail-info">
                    <div class="agent-top-info">
                        <div class="oval-image-container">
                            <?php if(!empty($agent->photo)): ?>
                            <img class="oval-image" src="<?php echo e(asset('storage/'. $agent->photo)); ?>" alt="Agent Photo">
                            <?php else: ?>
                            <img class="oval-image" src="<?php echo e(asset('storage/users-avatar/agent.png')); ?>"
                                alt="Default Image">
                            <?php endif; ?>
                        </div>
                        <div class="agent-details">
                            <p><?php echo e($agent->agentName); ?> #<?php echo e($agent->licenseNum ?: '-'); ?></p>
                            <p>
                                <?php for($i = 1; $i <= 5; $i++): ?> <?php if($i <=number_format($averageRating)): ?> <i
                                    class="fa fa-star gold-star"></i>
                                    <?php else: ?>
                                    <i class="fa fa-star gray-star"></i>
                                    <?php endif; ?>
                                    <?php endfor; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>




           

                <div class="agent-detail-main">
                    <div class="agent-name">About <?php echo e($agent->agentName); ?></div>
                </div>

                <nav class="agent-list-menu">
                    <ul>
                        <li><a href="#overview" data-section="overview"><?php echo e($agent->agentName); ?>'s Overview</a></li>
                        <li><a href="#community-reviews" data-section="community-reviews">Community Reviews</a></li>
                        <li><a href="#rent-properties" data-section="rent-properties">Rent for Properties</a></li>
                    </ul>
                </nav>

                <div class="property-find-container">
                    <div class="agent-property">
                        <div id="overview-content" class="agent-list-section active-section" data-section="overview">
                            <div class="agent-list-section-content">
                                <p>Hi, I'm <?php echo e($agent->agentName); ?>, your dedicated rental properties agent in RentSpace.
                                    My role
                                    is to make your renting experience seamless and stress-free.
                                    Whether you're searching for the perfect rental property or entrusting me with the
                                    management of your investment,
                                    I'm here to ensure your needs are met with professionalism and expertise.</p>

                                <p>I bring a wealth of expertise in the dynamic field of rental properties. Whether
                                    you're in
                                    search of the perfect home, I am here to guide you every
                                    step of the way. My goal is to simplify the renting process and ensure a positive
                                    experience
                                    for both tenants and property owners.</p>
                                <br />
                                <p>The areas I serve are as follows:</p>
                            </div>

                            <div class="agent-list-section-content-2">
                                <p class="latest-rent-property">
                                    <i class="fas fa-scroll"></i> Latest for Rent Poster
                                </p>
                                <?php
                                $latestproperties = $properties->take(3);
                                ?>

                                <?php $__currentLoopData = $latestproperties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="latest-property-container"
                                    onclick="navigateToPropertyDetails('<?php echo e(route('properties.show', $property->propertyID)); ?>')">
                                    <div class="latest-property">

                                        <div class="latest-property-image">
                                            <img src="<?php echo e(asset('storage/'. $property->propertyPhotoPath)); ?>">
                                            <div class="image-overlay">
                                                <p class="photo-count"><i class="fas fa-image"></i>
                                                    <?php echo e($property->photos_count); ?>

                                                    Photos</p>

                                            </div>
                                        </div>
                                        <?php
                                        $state =
                                        app('App\Http\Controllers\AgentController')->getPropertyState($property->propertyID);
                                        ?>

                                        <div class="latest-property-details">
                                            <p class="latest-property-name"><?php echo e($property->propertyName); ?></p>
                                            <p class="latest-property-price">For Rent:
                                                <span>RM<?php echo e($property->rentalAmount); ?></span>
                                            </p>
                                            <div class="latest-property-filter">
                                                <p><i class="las la-city"></i><?php echo e($property->propertyType); ?></p>
                                                <p><i class="las la-tools"></i><?php echo e($property->furnishingType); ?></p>
                                                <p><i class="lab la-buffer"></i><?php echo e($property->squareFeet); ?> SQ.FT</p>
                                                <p><i class="las la-flag"></i><?php echo e($state); ?></p>
                                                <p><i class="las la-bed"></i><?php echo e($property->bedroomNum); ?> bedroom</p>
                                                <p><i class="las la-bath"></i><?php echo e($property->bathroomNum); ?> bathrooms</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="latest-property-description"><?php echo e($property->propertyDesc); ?></p>
                                    <div class="latest-agent-details">
                                        <?php if($agent->photo !== null): ?>
                                        <img src="<?php echo e(asset('storage/' . $agent->photo)); ?>" alt="Agent Avatar">
                                        <?php else: ?>
                                        <img src="<?php echo e(asset('storage/users-avatar/agent.png')); ?>" alt="Agent Avatar">
                                        <?php endif; ?>

                                        <div class="latest-agent-info">
                                            <p class="latest-agent-name"><?php echo e($agent->agentName); ?></p>
                                            <p class="latest-update-time">Updated at
                                                <?php echo e(\Carbon\Carbon::parse($property->updated_at)->diffForHumans()); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>
                        </div>

                        <div id="community-reviews-content" class="agent-list-section" data-section="community-reviews"
                            style="display:none;">

                            <div class="agent-list-section-content">
                                <p class="overall-rating-agent">
                                    Overall Rating: <?php echo e(number_format($averageRating) *2); ?>/10
                                </p>

                                <?php if($reviews->isEmpty()): ?>

                                <?php else: ?>
                                <ul>
                                    <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $photoPath = $review->agent && $review->agent->photo
                                    ? $review->agent->photo
                                    : ($review->agent ? '/users-avatar/agent.png' : ($review->tenant &&
                                    $review->tenant->photo ?
                                    $review->tenant->photo : '/users-avatar/landlord.png'));
                                    ?>

                                    <div class="users-review">

                                        <div class="user-review">

                                            <div class="avatar-review">
                                                <img class="user-review-avatar"
                                                    src="<?php echo e(asset('storage/'. $photoPath)); ?>" alt="User Avatar">
                                                <div class="info-box">
                                                    <div class="info-box-content">
                                                        <img class="info-box-avatar"
                                                            src="<?php echo e(asset('storage/'. $photoPath)); ?>" alt="User Avatar">
                                                        <span class="info-box-name">
                                                            <?php if($review->agent): ?>
                                                            <?php echo e($review->agent->agentName); ?>

                                                            <?php elseif($review->tenant): ?>
                                                            <?php echo e($review->tenant->tenantName); ?>

                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                    <a href="<?php echo e(url('/chatify/' . $review->reviewerID)); ?>"
                                                        class="btn btn-primary">Chat With
                                                        Me</a>
                                                </div>
                                            </div>

                                            <div class="user-review-info">
                                                <div class="user-review-details">
                                                    <p class="user-name">
                                                        <?php if($review->agent): ?>
                                                        <?php echo e($review->agent->agentName); ?>

                                                        <?php elseif($review->tenant): ?>
                                                        <?php echo e($review->tenant->tenantName); ?>

                                                        <?php endif; ?>
                                                    </p>

                                                    <p class="user-review-date">
                                                        <?php echo e(\Carbon\Carbon::parse($review->reviewDate)->diffForHumans()); ?>

                                                        reviewed
                                                    </p>
                                                </div>

                                                <div class="user-review-stars">
                                                    <?php for($i = 0; $i < 5; $i++): ?> <?php if($i < $review->rating): ?>
                                                        <i class="fas fa-star" style="color: #00ada0;"></i>
                                                        <?php else: ?>
                                                        <i class="fas fa-star" style="color: #ccc;"></i>
                                                        <?php endif; ?>
                                                        <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="user-review-comment">
                                            <p><?php echo e($review->comment); ?></p>
                                        </div>

                                        <div class="users-reply-container">
                                            <?php
                                            $reviewReplies = $replies->where('ParentReviewID',
                                            $review->reviewID)->take(3);
                                            $remainingRepliesCount = $replies->where('ParentReviewID',
                                            $review->reviewID)->count() - 3;
                                            ?>
                                            <div class="review-underline" style="margin-left:60px;"></div>

                                            <?php $__currentLoopData = $reviewReplies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                            $photoPath = $review->agent && $review->agent->photo
                                            ? $reply->agent->photo
                                            : ($reply->agent ? '/users-avatar/agent.png' : ($reply->tenant &&
                                            $review->tenant->photo ?
                                            $reply->tenant->photo : '/users-avatar/landlord.png'));
                                            ?>
                                            <div class="users-reply">

                                                <div class="user-reply">

                                                    <div class="avatar-review">
                                                        <img class="user-reply-avatar"
                                                            src="<?php echo e(asset('storage/'. $photoPath)); ?>" alt="User Avatar">
                                                        <div class="info-box">
                                                            <div class="info-box-content">
                                                                <img class="info-box-avatar"
                                                                    src="<?php echo e(asset('storage/'. $photoPath)); ?>"
                                                                    alt="User Avatar">
                                                                <span class="info-box-name">
                                                                    <?php if($reply->agent): ?>
                                                                    <?php echo e($reply->agent->agentName); ?>

                                                                    <?php elseif($reply->tenant): ?>
                                                                    <?php echo e($reply->tenant->tenantName); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                            <a href="<?php echo e(url('/chatify/' . $reply->reviewerID)); ?>"
                                                                class="btn btn-primary">Chat With Me</a>
                                                        </div>
                                                    </div>
                                                    <div class="user-reply-info">
                                                        <div class="user-reply-details">
                                                            <p class="user-reply-name">
                                                                <?php if($reply->agent): ?>
                                                                <?php echo e($reply->agent->agentName); ?>

                                                                <?php elseif($reply->tenant): ?>
                                                                <?php echo e($reply->tenant->tenantName); ?>

                                                                <?php endif; ?>
                                                            </p>

                                                            <p class="user-reply-date">
                                                                <?php echo e(\Carbon\Carbon::parse($reply->reviewDate)->diffForHumans()); ?>

                                                                reviewed
                                                            </p>
                                                        </div>

                                                        <div class="user-reply-stars">
                                                            <?php for($i = 0; $i < 5; $i++): ?> <?php if($i < $reply->rating): ?>
                                                                <i class="fas fa-star" style="color: #00ada0;"></i>
                                                                <?php else: ?>
                                                                <i class="fas fa-star" style="color: #ccc;"></i>
                                                                <?php endif; ?>
                                                                <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="user-reply-comment">
                                                    <p><?php echo e($reply->comment); ?></p>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                                            <div id="replies-container-<?php echo e($review->reviewID); ?>"
                                                class="replies-container">
                                                <?php $__currentLoopData = $replies->where('ParentReviewID', $review->reviewID)->skip(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remainingReply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                $photoPath = $remainingReply->agent && $remainingReply->agent->photo
                                                ? $remainingReply->agent->photo
                                                : ($remainingReply->agent ? '/users-avatar/agent.png' :
                                                ($remainingReply->tenant &&
                                                $review->tenant->photo ? $remainingReply->tenant->photo :
                                                '/users-avatar/landlord.png'));
                                                ?>
                                                <div class="users-reply">

                                                    <div class="user-reply">
                                                        <div class="avatar-review">
                                                            <img class="user-reply-avatar"
                                                                src="<?php echo e(asset('storage/'. $photoPath)); ?>"
                                                                alt="User Avatar">
                                                            <div class="info-box">
                                                                <div class="info-box-content">
                                                                    <img class="info-box-avatar"
                                                                        src="<?php echo e(asset('storage/'. $photoPath)); ?>"
                                                                        alt="User Avatar">
                                                                    <span class="info-box-name">
                                                                        <?php if($remainingReply->agent): ?>
                                                                        <?php echo e($remainingReply->agent->agentName); ?>

                                                                        <?php elseif($remainingReply->tenant): ?>
                                                                        <?php echo e($remainingReply->tenant->tenantName); ?>

                                                                        <?php endif; ?>
                                                                    </span>
                                                                </div>
                                                                <a href="<?php echo e(url('/chatify/' . $remainingReply->reviewerID)); ?>"
                                                                    class="btn btn-primary">Chat With Me</a>
                                                            </div>
                                                        </div>
                                                        <div class="user-reply-info">
                                                            <div class="user-reply-details">
                                                                <p class="user-reply-name">
                                                                    <?php if($remainingReply->agent): ?>
                                                                    <?php echo e($remainingReply->agent->agentName); ?>

                                                                    <?php elseif($remainingReply->tenant): ?>
                                                                    <?php echo e($remainingReply->tenant->tenantName); ?>


                                                                    <?php endif; ?>
                                                                </p>
                                                                <p class="user-reply-date">
                                                                    <?php echo e(\Carbon\Carbon::parse($remainingReply->reviewDate)->diffForHumans()); ?>

                                                                    reviewed
                                                                </p>
                                                            </div>
                                                            <div class="user-reply-stars">
                                                                <?php for($i = 0; $i < 5; $i++): ?> <?php if($i < $remainingReply->
                                                                    rating): ?>
                                                                    <i class="fas fa-star" style="color: #00ada0;"></i>
                                                                    <?php else: ?>
                                                                    <i class="fas fa-star" style="color: #ccc;"></i>
                                                                    <?php endif; ?>
                                                                    <?php endfor; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="user-reply-comment">
                                                        <p><?php echo e($remainingReply->comment); ?></p>
                                                    </div>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </div>
                                        </div>

                                        <?php if($remainingRepliesCount > 0): ?>
                                        <div class="view-more-btn-container">
                                            <button class="view-more-btn"
                                                onclick="toggleReplies('<?php echo e($review->reviewID); ?>', <?php echo e($remainingRepliesCount); ?>)">
                                                <i id="icon-<?php echo e($review->reviewID); ?>" class="fas fa-chevron-down"></i>
                                                <span id="text-<?php echo e($review->reviewID); ?>">Show
                                                    <?php echo e($remainingRepliesCount); ?> more
                                                    replies</span>
                                            </button>
                                        </div>
                                        <?php else: ?>


                                        <?php endif; ?>
                                        <div class="review-underline" style="  margin-bottom: 30px;"></div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div id="rent-properties-content" class="agent-list-section" data-section="rent-properties"
                            style="display:none;">
                            <p class="latest-rent-property">
                                <i class="fas fa-scroll"></i>Total <?php echo e($properties->count()); ?> Rent Properties Poster
                            </p>

                            <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="latest-property-container"
                                onclick="navigateToPropertyDetails('<?php echo e(route('properties.show', $property->propertyID)); ?>')">

                                <div class="latest-property">

                                    <div class="latest-property-image">
                                        <img src="<?php echo e(asset('storage/'. $property->propertyPhotoPath)); ?>">
                                        <div class="image-overlay">
                                            <p class="photo-count"><i class="fas fa-image"></i>
                                                <?php echo e($property->photos_count); ?>

                                                Photos</p>

                                        </div>
                                    </div>

                                    <?php
                                    $state =
                                    app('App\Http\Controllers\AgentController')->getPropertyState($property->propertyID);
                                    ?>

                                    <div class="latest-property-details">
                                        <p class="latest-property-name"><?php echo e($property->propertyName); ?></p>
                                        <p class="latest-property-price">For Rent:
                                            <span>RM<?php echo e($property->rentalAmount); ?></span>
                                        </p>
                                        <div class="latest-property-filter">

                                            <p><i class="las la-city"></i><?php echo e($property->propertyType); ?></p>
                                            <p><i class="las la-tools"></i><?php echo e($property->furnishingType); ?></p>
                                            <p><i class="lab la-buffer"></i><?php echo e($property->squareFeet); ?> SQ.FT</p>
                                            <p><i class="las la-flag"></i><?php echo e($state); ?></p>
                                            <p><i class="las la-bed"></i><?php echo e($property->bedroomNum); ?> bedroom</p>
                                            <p><i class="las la-bath"></i><?php echo e($property->bathroomNum); ?> bathrooms</p>

                                        </div>
                                    </div>
                                </div>
                                <p class="latest-property-description"><?php echo e($property->propertyDesc); ?></p>
                                <div class="latest-agent-details">
                                    <?php if(!empty($agent->photo)): ?>
                                    <img class="oval-image" src="<?php echo e(asset('storage/users-avatar/'. $agent->photo)); ?>"
                                        alt="Agent Photo">
                                    <?php else: ?>
                                    <img class="oval-image" src="<?php echo e(asset('storage/users-avatar/agent.png')); ?>"
                                        alt="Default Image">
                                    <?php endif; ?>
                                    <div class="latest-agent-info">
                                        <p class="latest-agent-name"><?php echo e($agent->agentName); ?></p>
                                        <p class="latest-update-time">Updated at
                                            <?php echo e(\Carbon\Carbon::parse($property->updated_at)->diffForHumans()); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="agent-find">
                        <div class="find-inform">
                            <p>Available Time <i class="fas fa-clock"></i></p>
                        </div>
                        <div class="find-setting">
                            <p> <i class="far fa-calendar-alt"></i> Start time - End Time</p>
                        </div>

                        <div class="find-inform">
                            <p>Find Me @</i></p>
                        </div>
                        <div class="find-setting">
                            <p> <i class="fa fa-envelope"></i> <?php echo e($agent->agentEmail); ?></p>
                            <p> <i class=" fa fa-phone"></i> <?php echo e($agent->agentPhone); ?></p>
                        </div>
                    </div>
                </div>
           
                




        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.agent-list-menu a');
            const contentSections = document.querySelectorAll('.agent-property .agent-list-section');


            contentSections.forEach(section => {
                if (section.id === 'overview-content') {
                    section.classList.add('active-section');
                } else {
                    section.style.display = 'none';
                }
            });

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();


                    const targetSectionId = this.getAttribute('data-section');

                    console.log('Clicked menu item:', targetSectionId);


                    contentSections.forEach(section => {
                        section.style.display = 'none';
                    });


                    const targetContentSection = document.querySelector(
                        `#${targetSectionId}-content`);
                    console.log('Target content section:', targetContentSection);
                    if (targetContentSection) {
                        targetContentSection.style.display = 'block';
                    }


                    navLinks.forEach(link => {
                        link.style.color = '';
                        link.style.backgroundColor = '';
                        link.style.borderBottom = '';
                    });


                    this.style.color = 'white';
                    this.style.backgroundColor = '#1f98af';
                    this.style.borderBottom = '2px solid #1f98af';
                });
            });
        });

        function navigateToPropertyDetails(route) {

            window.location.href = route;
        }

        let selectedRatings = {};


        function toggleReplies(reviewID, remainingRepliesCount) {
            const repliesContainer = document.getElementById(`replies-container-${reviewID}`);
            const icon = document.getElementById(`icon-${reviewID}`);
            const textSpan = document.getElementById(`text-${reviewID}`);
            const button = document.querySelector(`.view-more-btn[data-review-id="${reviewID}"]`);

            const isExpanded = repliesContainer.classList.toggle('expanded');


            icon.className = isExpanded ? 'fas fa-chevron-up' : 'fas fa-chevron-down';


            textSpan.textContent = isExpanded ? `Hide ${remainingRepliesCount} replies` :
                `Show ${remainingRepliesCount} more replies`;


            if (isExpanded) {
                const allRepliesContainers = document.querySelectorAll('.replies-container');
                allRepliesContainers.forEach(container => {
                    if (container.id !== `replies-container-${reviewID}`) {
                        container.classList.remove('expanded');
                    }
                });


                const allButtons = document.querySelectorAll('.view-more-btn');
                allButtons.forEach(otherButton => {
                    const otherReviewID = otherButton.dataset.reviewId;
                    if (otherReviewID !== reviewID) {
                        const otherIcon = document.getElementById(`icon-${otherReviewID}`);
                        const otherTextSpan = document.getElementById(`text-${otherReviewID}`);
                        otherIcon.className = 'fas fa-chevron-down';
                        otherTextSpan.textContent = `Show ${remainingRepliesCount} more replies`;
                    }
                });
            }
        }
        </script>

    </div>
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/admin/agentViewProfile.blade.php ENDPATH**/ ?>