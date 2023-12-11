<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Detail</title>
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
        <link rel="stylesheet" href="<?php echo e(asset('/storage/css/Review.css')); ?>" media="screen">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>

    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .detail {
            padding: 20px;
        }

        .carousel-item img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
        }

        .agentProfile {
            border: 1px solid blue;
            padding: 10px;
            border-radius: 8px;
            background-color: #fff;
        }

        .agentProfile img {
            max-width: 50px;
            max-height: 50px;
        }

        .agentBtn a {
            width: 70%;
            margin-top: 10px;
        }

        .facilities {
            font-size: 18px;
            padding: 10px;
        }

        .facilities i {
            margin-right: 20px;
        }



        .propertyDetail td {
            font-size: 18px;
            padding: 10px;
        }

        .propertyDetail i {
            margin-right: 20px;
        }

        .propertyDetail {
            width: 80%;
        }



        .price {
            color: blue;
            font-weight: bold;
            font-family: 'Tahoma';
        }

        /* Add some spacing to improve readability */
        h2,
        h4,
        p {
            margin-bottom: 10px;
        }

        .meta-table-root {
            margin-top: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .agent-info-root {
                text-align: center;
            }

            .avatar-wrapper {
                margin-bottom: 10px;
            }
        }

        .photo {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 40%;
        }
        #map {
        width: "100%";
        height: 325px;
        margin:0 0 25px 0;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }
    </style>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2 detail">
        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back</a>

            <div class="row justify-content-center">
        <div class="col-md-6">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(\Session::get('success')); ?>.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php elseif(session('message')): ?>
            <div class="alert alert-primary alert-dismissible fade show text-center mx-auto" style="max-width: 550px;">
                <?php echo e(session('message')); ?>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        </div>
    </div>
        <h2>Property Details</h2>

        <!-- Property Photo Carousel -->

        <div id="propertyCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php $__currentLoopData = $property->propertyPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                    <img src="<?php echo e(Storage::url($photo->propertyPath)); ?>" alt="Property Photo <?php echo e($index + 1); ?>"
                        class="photo">
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <a class="carousel-control-prev" href="#propertyCarousel" role="button" data-slide="prev">
                <div class="btn btn-secondary"> <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </div>
                <span class="sr-only">Previous</span>
            </a>

            <a class="carousel-control-next" href="#propertyCarousel" role="button" data-slide="next">
                <div class="btn btn-secondary"> <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </div>
                <span class="sr-only">Next</span>
            </a>
        </div>


        <div id="map" style="display: none;"></div>
        <div class="container-sm">
            <!-- Property Overview Section -->
            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="location-info">
                        <h1 class="title"><?php echo e($property->propertyName); ?></h1>
                        <div class="full-address">
                            <p class="full-address__text">
                                <?php echo e($property->propertyAddress); ?>

                                <a role="button" tabindex="0"  class="actionable full-address__link btn btn-link primary" id="showMap">See on Map</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 ">
                    <div class="price d-flex justify-content-center">
                        <h3 class="amount">RM <?php echo e($property->rentalAmount); ?> /MONTH</h3>
                    </div>
                    <div class="rentButton d-flex justify-content-center">
                        <a href="#" class="btn btn-danger btn-lg">Rent This Space</a>
                    </div>
                </div>
            </div>

            </br>
            <div class="row">
                <div class="col-12 col-md-8">
                    <!-- Property Amenities Section -->
                    <div class="property-amenities-root">
                        <h4 class="meta-table__title">Property details</h4>
                        <ul class="property-amenities__tab-header nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation"><button type="button"
                                    id="react-aria-3-tab-Unit Features" role="tab"
                                    data-rr-ui-event-key="Property Overview"
                                    aria-controls="react-aria-3-tabpane-Property Overview" aria-selected="true"
                                    class="property-amenities__tab-header-item nav-link active">Property
                                    Overview</button>
                            </li>
                            <li class="nav-item" role="presentation"><button type="button"
                                    id="react-aria-3-tab-Facilities" role="tab" data-rr-ui-event-key="Facilities"
                                    aria-controls="react-aria-3-tabpane-Facilities" aria-selected="false" tabindex="-1"
                                    class="property-amenities__tab-header-item nav-link">Facilities</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" id="react-aria-3-tabpane-Property Overview"
                                aria-labelledby="react-aria-3-tab-Property Overview" class="fade tab-pane active show">
                                <div class="property-amenities__body">
                                    <div class="meta-table-root">
                                        <h6>
                                            <?php echo e($property->propertyDesc); ?>

                                        </h6>
                                        <table class="propertyDetail">
                                            <tr>
                                                <td class="meta-table__item-wrapper ">
                                                    <i class="las la-building"></i> <?php echo e($property->propertyType); ?>

                                                </td>
                                                <td class="meta-table__item-wrapper">
                                                    <i class="las la-crop-alt"></i> <?php echo e($property->squareFeet); ?> sqft
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="meta-table__item-wrapper">
                                                    <i class="las la-brush"></i> <?php echo e($property->furnishingType); ?>

                                                </td>
                                                <td class="meta-table__item-wrapper">
                                                    <i class="las la-brush"></i> <?php echo e($property->buildYear); ?> Year
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="meta-table__item-wrapper">
                                                    <i class="las la-bed"></i> <?php echo e($property->bedroomNum); ?> Bedroom
                                                </td>
                                                <td class="meta-table__item-wrapper">
                                                    <i class="las la-bath"></i> <?php echo e($property->bathroomNum); ?>

                                                    Bathroom
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" id="react-aria-3-tabpane-Facilities"
                                aria-labelledby="react-aria-3-tab-Facilities" class="fade tab-pane">
                                <div class="property-amenities__body">
                                    <div class="meta-table-root row facilities">
                                        <?php $__empty_1 = true; $__currentLoopData = $property->propertyFacilities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="col-md-6 col-12 mt-3">
                                            <i class="<?php echo e($facility->facility->facilityIcon); ?>"></i>
                                            <?php echo e($facility->facility->facilityName); ?>

                                        </div>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <li>No facilities available</li>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 agentProfile">
                    <!-- Agent Profile -->
                    <div class="card-header row">
                        <div class="col-2">
                            <img class="avatar" src="<?php echo e(Storage::url($agent->photo)); ?>"
                                alt="<?php echo e($agent->agentName); ?>">
                        </div>
                        <div class="col-10">
                            <h5 class="card-title"><?php echo e($agent->agentName); ?></h5>
                            <p class="card-text"><small class="text-muted">REN: 17144</small></p>

                        </div>
                    </div>
                    <div class="card-body text-center agentBtn">
                        <a href="#" class="btn btn-primary"> Book Appointment </a>

                        <a href="#" class="btn btn-primary"> View agent profile</a>

                        <a href="#" class="btn btn-primary"> Send Enquiry </a>

                    </div>
                    <div class="terms-and-policy">
                        By clicking the link, I confirm that I have read the <a href="#">privacy policy</a> and
                        allow my
                        information to be
                        shared with this agent who may contact me later.
                    </div>
                </div>
            </div>
        </div>
        </section>

        <p style="font-size:30px; font-weight:bold; margin-left:35px;">Reviews</p>
    <div class="review-com-container">
        <div class="rating-container">

            <p style="font-weight:bold; font-size:24px; text-align:right; color:#0074e4"><?php if($totalCount > 0): ?>
                <?php echo e(number_format((($veryGoodCount * 5 + $goodCount * 4  + $averageCount * 3  + $badCount * 2  + $veryBadCount * 1 ) / $totalCount ) * 2, 1)); ?>/10
                <?php else: ?>
                0/10
                <?php endif; ?>
            </p>

            <div class="rating-item">
                <div class="rating-label">
                    Excellent
                    <div class="rating-count">
                        <?php echo e($veryGoodCount); ?>

                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill excellent"
                        style="width:100%; background: linear-gradient(90deg, #60bf58 <?php echo e($veryGoodCount > 0 ? ($veryGoodCount / $totalCount) * 100 : 0); ?>%, <?php echo e($veryGoodCount > 0 ? '#eee' : '#eee'); ?> <?php echo e($veryGoodCount > 0 ? ($veryGoodCount / $totalCount) * 100 : 0); ?>%);">
                    </div>
                </div>
            </div>

            <div class="rating-item">
                <div class="rating-label">
                    Good
                    <div class="rating-count">
                        <?php echo e($goodCount); ?>

                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill good"
                        style="width:100%; background: linear-gradient(90deg, #80cd8b <?php echo e($goodCount > 0 ? ($goodCount / $totalCount) * 100 : 0); ?>%, <?php echo e($goodCount > 0 ? '#eee' : '#eee'); ?> <?php echo e($goodCount > 0 ? ($goodCount / $totalCount) * 100 : 0); ?>%);">
                    </div>
                </div>
            </div>

            <div class="rating-item">
                <div class="rating-label">
                    Average
                    <div class="rating-count">
                        <?php echo e($averageCount); ?>

                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill average"
                        style="width:100%; background: linear-gradient(90deg, #ffbd59 <?php echo e($averageCount > 0 ? ($averageCount / $totalCount) * 100 : 0); ?>%, <?php echo e($averageCount > 0 ? '#eee' : '#eee'); ?> <?php echo e($averageCount > 0 ? ($averageCount / $totalCount) * 100 : 0); ?>%);">
                    </div>
                </div>
            </div>

            <div class="rating-item">
                <div class="rating-label">
                    Bad
                    <div class="rating-count">
                        <?php echo e($badCount); ?>

                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill bad"
                        style="width:100%; background: linear-gradient(90deg, #ff7808 <?php echo e($badCount > 0 ? ($badCount / $totalCount) * 100 : 0); ?>%, <?php echo e($badCount > 0 ? '#eee' : '#eee'); ?> <?php echo e($badCount > 0 ? ($badCount / $totalCount) * 100 : 0); ?>%);">
                    </div>
                </div>
            </div>

            <div class="rating-item">
                <div class="rating-label">
                    Very Bad
                    <div class="rating-count">
                        <?php echo e($veryBadCount); ?>

                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill very-bad"
                        style="width:100%; background: linear-gradient(90deg, #ff3131 <?php echo e($veryBadCount > 0 ? ($veryBadCount / $totalCount) * 100 : 0); ?>%, <?php echo e($veryBadCount > 0 ? '#eee' : '#eee'); ?> <?php echo e($veryBadCount > 0 ? ($veryBadCount / $totalCount) * 100 : 0); ?>%);">
                    </div>
                </div>
            </div>

        </div>


        <div class="comment-container">
            <?php if($reviews->isEmpty()): ?>
            <H3>No Review</H3>
            <?php else: ?>
            <ul>
                <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $photoPath = $review->agent && $review->agent->photo
                ? $review->agent->photo
                : ($review->agent ? '/users-avatar/agent.png' : ($review->tenant && $review->tenant->photo ?
                $review->tenant->photo : '/users-avatar/landlord.png'));
                ?>
                <div class="users-review">
                    <?php if(session('agent')): ?>
                    <?php if($review->reviewerID == session('agent')->agentID): ?>
                    <div class="review-options-container">
                        <div class="review-options">
                            <i class="fas fa-bars" title="Setting"
                                onclick="toggleReviewOptions('<?php echo e($review->reviewID); ?>')"></i>
                            <div class="options-menu" id="options-menu-<?php echo e($review->reviewID); ?>">
                                <div class="option"
                                    onclick="openEditCommentModal('<?php echo e($review->reviewID); ?>', '<?php echo e($review->comment); ?>')">
                                    <i class="fas fa-edit"></i> Edit
                                </div>

                                <div class="option" onclick="confirmDelete('<?php echo e($review->reviewID); ?>')">
                                    <i class="fas fa-trash"></i> Delete
                                    <form id="delete-form-<?php echo e($review->reviewID); ?>" action="<?php echo e(route('delete_review')); ?>"
                                        method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="reviewID" value="<?php echo e($review->reviewID); ?>">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>

                    <div class="user-review">
                        <div class="avatar-review">
                            <img class="user-review-avatar" src="<?php echo e(asset('storage/'. $photoPath)); ?>" alt="User Avatar">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <img class="info-box-avatar" src="<?php echo e(asset('storage/'. $photoPath)); ?>"
                                        alt="User Avatar">
                                    <span class="info-box-name">
                                        <?php if($review->agent): ?>
                                        <?php echo e($review->agent->agentName); ?>

                                        <?php elseif($review->tenant): ?>
                                        <?php echo e($review->tenant->tenantName); ?>

                                        <?php endif; ?>
                                    </span>
                                </div>
                                <a href="<?php echo e(url('/chatify/' . $review->reviewerID)); ?>" class="btn btn-primary">Chat With
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
                                    <?php echo e(\Carbon\Carbon::parse($review->reviewDate)->diffForHumans()); ?> reviewed
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
                        $reviewReplies = $replies->where('ParentReviewID', $review->reviewID)->take(3);
                        $remainingRepliesCount = $replies->where('ParentReviewID', $review->reviewID)->count() - 3;
                        ?>
                        <div class="review-underline" style="margin-left:60px;"></div>

                        <?php $__currentLoopData = $reviewReplies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $photoPath = $reply->agent && $reply->agent->photo
                        ? $reply->agent->photo
                        : ($reply->agent ? '/users-avatar/agent.png' : ($reply->tenant && $reply->tenant->photo ?
                        $reply->tenant->photo : '/users-avatar/landlord.png'));
                        ?>
                        <div class="users-reply">
                            <?php if(session('agent')): ?>
                            <?php if($reply->reviewerID == session('agent')->agentID): ?>
                            <div class="review-options-container" style=" font-size: 14px;">
                                <div class="review-options">
                                    <i class="fas fa-bars" title="Setting"
                                        onclick="toggleReviewOptions('<?php echo e($reply->reviewID); ?>')"></i>
                                    <div class="options-menu" id="options-menu-<?php echo e($reply->reviewID); ?>">
                                        <div class="option"
                                            onclick="openEditCommentModal('<?php echo e($reply->reviewID); ?>', '<?php echo e($reply->comment); ?>')">
                                            <i class="fas fa-edit"></i> Edit
                                        </div>

                                        <div class="option" onclick="confirmDelete('<?php echo e($reply->reviewID); ?>')">
                                            <i class="fas fa-trash"></i> Delete
                                            <form id="delete-form-<?php echo e($reply->reviewID); ?>"
                                                action="<?php echo e(route('delete_review')); ?>" method="POST"
                                                style="display: none;">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="reviewID" value="<?php echo e($reply->reviewID); ?>">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>

                            <div class="user-reply">
                                <div class="avatar-review">
                                    <img class="user-reply-avatar" src="<?php echo e(asset('storage/'. $photoPath)); ?>"
                                        alt="User Avatar">
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <img class="info-box-avatar" src="<?php echo e(asset('storage/'. $photoPath)); ?>"
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

                        <div id="replies-container-<?php echo e($review->reviewID); ?>" class="replies-container">
                            <?php $__currentLoopData = $replies->where('ParentReviewID', $review->reviewID)->skip(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remainingReply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $photoPath = $remainingReply->agent && $remainingReply->agent->photo
                            ? $remainingReply->agent->photo
                            : ($remainingReply->agent ? '/users-avatar/agent.png' : ($remainingReply->tenant &&
                            $remainingReply->tenant->photo ? $remainingReply->tenant->photo : '/users-avatar/landlord.png'));
                            ?>
                            <div class="users-reply">
                                <?php if(session('agent')): ?>
                                <?php if($remainingReply->reviewerID == session('agent')->agentID): ?>
                                <div class="review-options-container" style=" font-size: 14px;">
                                    <div class="review-options">
                                        <i class="fas fa-bars" title="Setting"
                                            onclick="toggleReviewOptions('<?php echo e($remainingReply->reviewID); ?>')"></i>
                                        <div class="options-menu" id="options-menu-<?php echo e($remainingReply->reviewID); ?>">
                                            <div class="option"
                                                onclick="openEditCommentModal('<?php echo e($remainingReply->reviewID); ?>', '<?php echo e($remainingReply->comment); ?>')">
                                                <i class="fas fa-edit"></i> Edit
                                            </div>

                                            <div class="option"
                                                onclick="confirmDelete('<?php echo e($remainingReply->reviewID); ?>')">
                                                <i class="fas fa-trash"></i> Delete
                                                <form id="delete-form-<?php echo e($remainingReply->reviewID); ?>"
                                                    action="<?php echo e(route('delete_review')); ?>" method="POST"
                                                    style="display: none;">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="reviewID"
                                                        value="<?php echo e($remainingReply->reviewID); ?>">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endif; ?>

                                <div class="user-reply">
                                    <div class="avatar-review">
                                        <img class="user-reply-avatar" src="<?php echo e(asset('storage/'. $photoPath)); ?>"
                                            alt="User Avatar">
                                        <div class="info-box">
                                            <div class="info-box-content">
                                                <img class="info-box-avatar" src="<?php echo e(asset('storage/'. $photoPath)); ?>"
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
                                            <?php for($i = 0; $i < 5; $i++): ?> <?php if($i < $remainingReply->rating): ?>
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

                            <form id="review-form-<?php echo e($review->reviewID); ?>" method="POST"
                                action="<?php echo e(route('reply_review')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="rating" data-review-id="<?php echo e($review->reviewID); ?>" value="0">
                                <input type="hidden" name="ParentReviewID" value="<?php echo e($review->reviewID); ?>">
                                <input type="hidden" name="reviewerID"
                                    value="<?php echo e(session('agent') ? session('agent')->agentID : ''); ?>">
                                <input type="hidden" name="reviewItemID" value="<?php echo e($property->propertyID); ?>">
                                <div class="user-review-container">
                                    <div class="review-container">
                                        <div class="stars">
                                            <i class="fas fa-star star" data-rating="1"
                                                data-review-id="<?php echo e($review->reviewID); ?>"></i>
                                            <i class="fas fa-star star" data-rating="2"
                                                data-review-id="<?php echo e($review->reviewID); ?>"></i>
                                            <i class="fas fa-star star" data-rating="3"
                                                data-review-id="<?php echo e($review->reviewID); ?>"></i>
                                            <i class="fas fa-star star" data-rating="4"
                                                data-review-id="<?php echo e($review->reviewID); ?>"></i>
                                            <i class="fas fa-star star" data-rating="5"
                                                data-review-id="<?php echo e($review->reviewID); ?>"></i>
                                        </div>
                                        <div class="input-container">
                                            <img class="user-reply-avatar"
                                                src="<?php echo e(session('agent') && session('agent')->photo !== null ? asset('storage/' . session('agent')->photo) : asset('storage/users-avatar/agent.png')); ?>"
                                                alt="User Avatar">
                                            <textarea name="reply" rows="1" data-review-id="<?php echo e($review->reviewID); ?>"
                                                placeholder="Reply something here..." value=""
                                                oninput="autoResize(this)"></textarea>
                                            <i class="fas fa-paper-plane send-icon"
                                                onclick="replyReview('<?php echo e($review->reviewID); ?>','review-form-<?php echo e($review->reviewID); ?>')"></i>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php if($remainingRepliesCount > 0): ?>
                    <div class="view-more-btn-container">
                        <button class="view-more-btn"
                            onclick="toggleReplies('<?php echo e($review->reviewID); ?>', <?php echo e($remainingRepliesCount); ?>)">
                            <i id="icon-<?php echo e($review->reviewID); ?>" class="fas fa-chevron-down"></i>
                            <span id="text-<?php echo e($review->reviewID); ?>">Show <?php echo e($remainingRepliesCount); ?> more
                                replies</span>
                        </button>
                    </div>
                    <?php else: ?>

                    <form id="reply-form-<?php echo e($review->reviewID); ?>" method="POST" action="<?php echo e(route('reply_review')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="rating" data-review-id="<?php echo e($review->reviewID); ?>" value="0">
                        <input type="hidden" name="ParentReviewID" value="<?php echo e($review->reviewID); ?>">
                        <input type="hidden" name="reviewerID"
                            value="<?php echo e(session('agent') ? session('agent')->agentID : ''); ?>">
                        <input type="hidden" name="reviewItemID" value="<?php echo e($property->propertyID); ?>">
                        <div class="user-review-container">
                            <div class="review-container">
                                <div class="stars">
                                    <i class="fas fa-star star" data-rating="1"
                                        data-review-id="<?php echo e($review->reviewID); ?>"></i>
                                    <i class="fas fa-star star" data-rating="2"
                                        data-review-id="<?php echo e($review->reviewID); ?>"></i>
                                    <i class="fas fa-star star" data-rating="3"
                                        data-review-id="<?php echo e($review->reviewID); ?>"></i>
                                    <i class="fas fa-star star" data-rating="4"
                                        data-review-id="<?php echo e($review->reviewID); ?>"></i>
                                    <i class="fas fa-star star" data-rating="5"
                                        data-review-id="<?php echo e($review->reviewID); ?>"></i>
                                </div>
                                <div class="input-container">
                                    <img class="user-reply-avatar"
                                        src="<?php echo e(session('agent') && session('agent')->photo !== null ? asset('storage/' . session('agent')->photo) : asset('storage/users-avatar/agent.png')); ?>"
                                        alt="User Avatar">
                                    <textarea name="reply" rows="1" data-review-id="<?php echo e($review->reviewID); ?>"
                                        placeholder="Reply something here..." value=""
                                        oninput="autoResize(this)"></textarea>
                                    <i class="fas fa-paper-plane send-icon"
                                        onclick="replyReview('<?php echo e($review->reviewID); ?>','reply-form-<?php echo e($review->reviewID); ?>')"></i>
                                </div>
                            </div>
                        </div>

                    </form>
                    <?php endif; ?>
                    <div class="review-underline" style="  margin-bottom: 30px;"></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
    </div>


    <div class="modal" id="editCommentModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog custom-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Comment</h5>
                    <button type="button" id="editCommentCloseBtn" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form method="POST" action="<?php echo e(route('edit-review')); ?>" id="editCommentForm">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <input type="hidden" name="editReviewID" id="editReviewID">

                            <label for="editedComment">Current Comment:</label>
                            <textarea class="form-control" id="editedComment" name="editedComment" rows="3"
                                required></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get all tab headers and tab panels
            const tabHeaders = document.querySelectorAll('.property-amenities__tab-header-item');
            const tabPanels = document.querySelectorAll('.tab-pane');

            // Add click event listeners to each tab header
            tabHeaders.forEach((header, index) => {
                header.addEventListener('click', function () {
                    // Remove 'active' class from all tab headers and tab panels
                    tabHeaders.forEach(tabHeader => tabHeader.classList.remove('active'));
                    tabPanels.forEach(tabPanel => tabPanel.classList.remove('show', 'active'));

                    // Add 'active' class to the clicked tab header and corresponding tab panel
                    header.classList.add('active');
                    tabPanels[index].classList.add('show', 'active');
                });
            });
        });

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

    const stars = document.querySelectorAll('.star');

    stars.forEach(star => {
        star.addEventListener('mouseenter', () => {
            const rating = parseInt(star.getAttribute('data-rating'));
            const reviewID = star.getAttribute('data-review-id');
            updateStarColors(rating, reviewID);
        });

        star.addEventListener('mouseleave', () => {
            const reviewID = star.getAttribute('data-review-id');
            updateStarColors(selectedRatings[reviewID], reviewID);
        });

        star.addEventListener('click', () => {
            const rating = parseInt(star.getAttribute('data-rating'));
            const reviewID = star.getAttribute('data-review-id');
            selectedRatings[reviewID] = rating;
            updateStarColors(rating, reviewID);
            document.querySelector(`input[name="rating"][data-review-id="${reviewID}"]`).value = rating;
        });
    });

    function updateStarColors(rating, reviewID) {
        const stars = document.querySelectorAll(`.star[data-review-id="${reviewID}"]`);
        stars.forEach(star => {
            const starRating = parseInt(star.getAttribute('data-rating'));
            if (starRating <= rating) {
                star.style.color = '#00ada0'; // Selected color
            } else {
                star.style.color = '#ccc'; // Default color
            }
        });
    }

    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    function submitReview() {
        const reviewText = document.querySelector('textarea[name="comment"]').value;
        const selectedRating = getSelectedRating(0);

        if (reviewText.trim() === '') {
            alert('Please enter a comment.');
            return;
        }

        if (selectedRating === 0) {
            alert('Please select a rating.');
            return;
        }

        if (reviewText.length > 200) {
            alert('Your comment is too long. Please keep it under 200 characters.');
            return;
        }

        document.querySelector('input[name="add_rating"]').value = selectedRating;
        document.getElementById('review-form').submit();
    }

    function replyReview(reviewID, formID) {
        console.log(reviewID);
        const replyText = document.querySelector(`#${formID} textarea[name="reply"][data-review-id="${reviewID}"]`)
            .value;
        const selectedRating = getSelectedRating(reviewID);
        console.log(replyText);
        console.log(selectedRating);
        console.log(formID);



        if (replyText.trim() === '') {
            alert('Please enter a reply.');
            return;
        }

        if (selectedRating === 0) {
            alert('Please select a rating.');
            return;
        }

        if (replyText.length > 200) {
            alert('Your reply is too long. Please keep it under 200 characters.');
            return;
        }
        document.querySelector(`#${formID} input[name="rating"][data-review-id="${reviewID}"]`).value = selectedRating;
        selectedRatings[reviewID] = selectedRating;
        document.getElementById(formID).submit();
    }



    function getSelectedRating(reviewID) {
        return selectedRatings[reviewID] || 0;
    }

    function toggleReviewOptions(reviewID) {
        const optionsMenu = document.getElementById(`options-menu-${reviewID}`);
        optionsMenu.style.display = optionsMenu.style.display === 'block' ? 'none' : 'block';
    }

    function confirmDelete(reviewID) {
        if (confirm('Are you sure you want to delete this review?')) {
            document.getElementById(`delete-form-${reviewID}`).submit();
        }
    }

    function openEditCommentModal(reviewID, currentComment) {

        document.getElementById('editedComment').value = currentComment;
        document.getElementById('editReviewID').value = reviewID;
        $('#editCommentModal').modal('show');
    }

    document.getElementById('editCommentCloseBtn').addEventListener('click', function() {
        $('#editCommentModal').modal('hide');
    });
    </script>
     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBuCyWLDUnN2vQiE0fdBHoSmQhR9mbLvZ4&callback=initMap" async></script>
<script>

var showMapButton = document.getElementById('showMap');
var mapDiv = document.getElementById('map');

showMapButton.addEventListener('click', function(event) {
    event.preventDefault(); // Prevents the default link behavior (navigating to the 'map' route)
    const initialMarkers = <?php echo json_encode($initialMarkers); ?>;
    // Toggle the map div visibility
    if (mapDiv.style.display === 'block') {
        mapDiv.style.display = 'none'; // Hide the map
        showMapButton.innerText = 'Show map'; // Change button text to 'Show map'
    } else {
        mapDiv.style.display = 'block'; // Show the map
        showMapButton.innerText = 'Hide map'; // Change button text to 'Hide map'
        if(initialMarkers[0]== null){
        alert("Address Not Found!");
    }
    }
});


 let map, activeInfoWindow, markers = [];

/* ----------------------------- Initialize Map ----------------------------- */
function initMap() {
    const initialMarkers = <?php echo json_encode($initialMarkers); ?>;
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
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/propertyDetail.blade.php ENDPATH**/ ?>