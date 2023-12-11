@extends('layouts.header')
<link rel="stylesheet" href="{{ asset('/storage/css/ViewAgent.css') }}" media="screen">
<link rel="stylesheet" href="{{ asset('/storage/css/AgentMenu.css') }}" media="screen">
<link rel="stylesheet"
    href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

@section('content')

<div class="agent-detail-container">

    <div class="agent-detail-top" style="background-image: url('{{ asset('storage/images/aBackground.png') }}');">
        <div class="agent-detail-info">
            <div class="agent-top-info">
                <div class="oval-image-container">
                    @if (!empty($agent->photo))
                    <img class="oval-image" src="{{ asset('storage/'. $agent->photo) }}" alt="Agent Photo">
                    @else
                    <img class="oval-image" src="{{ asset('storage/users-avatar/agent.png') }}" alt="Default Image">
                    @endif
                </div>
                <div class="agent-details">
                    <p>{{$agent->agentName}} #{{ $agent->licenseNum ?: '-' }}</p>
                    <p>
                        @for ($i = 1; $i <= 5; $i++) @if ($i <=number_format($averageRating)) <i
                            class="fa fa-star gold-star"></i>
                            @else
                            <i class="fa fa-star gray-star"></i>
                            @endif
                            @endfor
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show text-center mx-auto" style="max-width: 500px;">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @elseif(session('success'))
    <div class="alert alert-success alert-dismissible fade show text-center mx-auto" style="max-width: 500px;">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @elseif(session('message'))
    <div class="alert alert-primary alert-dismissible fade show text-center mx-auto" style="max-width: 500px;">
        {{ session('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif


    <div class="detail-main-container">

        <div class="agent-detail-main">
            <div class="agent-name">About {{$agent->agentName}}</div>
            <a href="{{ route('start.chat', ['userId' => $agent->agentID]) }}" class="btn btn-primary"><i
                    class="fa fa-comment"></i> Chat Me</a>
        </div>

        <nav class="agent-list-menu">
            <ul>
                <li><a href="#overview" data-section="overview">{{$agent->agentName}}'s Overview</a></li>
                <li><a href="#community-reviews" data-section="community-reviews">Community Reviews</a></li>
                <li><a href="#rent-properties" data-section="rent-properties">Rent for Properties</a></li>
            </ul>
        </nav>

        <div class="property-find-container">
            <div class="agent-property">
                <div id="overview-content" class="agent-list-section active-section" data-section="overview">
                    <div class="agent-list-section-content">
                        <p>Hi, I'm {{$agent->agentName}}, your dedicated rental properties agent in RentSpace. My role
                            is to make your renting experience seamless and stress-free.
                            Whether you're searching for the perfect rental property or entrusting me with the
                            management of your investment,
                            I'm here to ensure your needs are met with professionalism and expertise.</p>

                        <p>I bring a wealth of expertise in the dynamic field of rental properties. Whether you're in
                            search of the perfect home, I am here to guide you every
                            step of the way. My goal is to simplify the renting process and ensure a positive experience
                            for both tenants and property owners.</p>
                        <br />
                        <p>The areas I serve are as follows:</p>
                    </div>

                    <div class="agent-list-section-content-2">
                        <p class="latest-rent-property">
                            <i class="fas fa-scroll"></i> Latest for Rent Poster
                        </p>
                        @php
                        $latestproperties = $properties->take(3);
                        @endphp

                        @foreach ($latestproperties as $property)
                        <div class="latest-property-container"
                            onclick="navigateToPropertyDetails('{{ route('properties.show', $property->propertyID) }}')">
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
                            <div class="latest-property">
                                <i class="fas fa-bookmark wishlist-star" title="Add to Wishlist"
                                    id="wishlistStar{{ $property->propertyID }}"
                                    style="color: {{ $wishlistData ? '#FFD700' : 'grey' }}"></i>
                                <form id="wishlistForm{{ $property->propertyID }}"
                                    action="{{ route('ToggleWishList') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="propertyID" id="propertyID"
                                        value="{{ $property->propertyID }}">
                                        <input type="hidden" name="tenantID" id="tenantID" value="{{ session('tenant') ? session('tenant')->tenantID : '' }}">

                                </form>

                                <div class="latest-property-image">
                                    <img src="{{ asset('storage/'. $property->propertyPhotoPath) }}">
                                    <div class="image-overlay">
                                        <p class="photo-count"><i class="fas fa-image"></i> {{$property->photos_count}}
                                            Photos</p>

                                    </div>
                                </div>
                                @php
                                $state =
                                app('App\Http\Controllers\AgentController')->getPropertyState($property->propertyID);
                                @endphp

                                <div class="latest-property-details">
                                    <p class="latest-property-name">{{$property->propertyName}}</p>
                                    <p class="latest-property-price">For Rent:
                                        <span>RM{{$property->rentalAmount}}</span>
                                    </p>
                                    <div class="latest-property-filter">
                                        <p><i class="las la-city"></i>{{$property->propertyType}}</p>
                                        <p><i class="las la-tools"></i>{{$property->furnishingType}}</p>
                                        <p><i class="lab la-buffer"></i>{{$property->squareFeet}} SQ.FT</p>
                                        <p><i class="las la-flag"></i>{{$state}}</p>
                                        <p><i class="las la-bed"></i>{{$property->bedroomNum}} bedroom</p>
                                        <p><i class="las la-bath"></i>{{$property->bathroomNum}} bathrooms</p>
                                    </div>
                                </div>
                            </div>
                            <p class="latest-property-description">{{$property->propertyDesc}}</p>
                            <div class="latest-agent-details">
                                @if ($agent->photo !== null)
                                <img src="{{ asset('storage/' . $agent->photo) }}" alt="Agent Avatar">
                                @else
                                <img src="{{ asset('storage/users-avatar/agent.png') }}" alt="Agent Avatar">
                                @endif

                                <div class="latest-agent-info">
                                    <p class="latest-agent-name">{{ $agent->agentName }}</p>
                                    <p class="latest-update-time">Updated at
                                        {{ \Carbon\Carbon::parse($property->updated_at)->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>

                <div id="community-reviews-content" class="agent-list-section" data-section="community-reviews"
                    style="display:none;">

                    <div class="agent-list-section-content">
                        <p class="overall-rating-agent">
                            Overall Rating: {{ number_format($averageRating) *2 }}/10
                        </p>

                        @if ($reviews->isEmpty())
            <h3 style="padding:15px;">Add Reviews</h3>
            <form id="review-form" method="POST" action="{{ route('add_review') }}">
                @csrf
                <input type="hidden" name="add_rating" value="0">
                <input type="hidden" name="reviewerID"
                    value="{{ session('tenant') ? session('tenant')->tenantID : '' }}">
                <input type="hidden" name="reviewItemID" value="{{ $agent->agentID }}">
                <div class="user-review-container">
                    <div class="review-container">
                        <div class="stars" style="font-size: 22px;">
                            <i class="fas fa-star star" data-rating="1" data-review-id="0"></i>
                            <i class="fas fa-star star" data-rating="2" data-review-id="0"></i>
                            <i class="fas fa-star star" data-rating="3" data-review-id="0"></i>
                            <i class="fas fa-star star" data-rating="4" data-review-id="0"></i>
                            <i class="fas fa-star star" data-rating="5" data-review-id="0"></i>
                        </div>
                        <div class="input-container">
                            <img class="user-review-avatar"
                                src="{{ session('tenant') && session('tenant')->photo !== null ? asset('storage/' . session('tenant')->photo) : asset('storage/users-avatar/landlord.png') }}"
                                style="height:40px; width:40px;" alt="User Avatar">
                            <textarea name="comment" rows="1" placeholder="Write your comment here..."
                                oninput="autoResize(this)"></textarea>
                            <i class="fas fa-paper-plane send-icon" onclick="submitReview()"></i>
                        </div>
                    </div>
                </div>
            </form>
            @else
            <ul>
                @foreach ($reviews as $review)
                @php
                $photoPath = $review->agent && $review->agent->photo
                ? $review->agent->photo
                : ($review->agent ? '/users-avatar/agent.png' : ($review->tenant && $review->tenant->photo ?
                $review->tenant->photo : '/users-avatar/landlord.png'));
                @endphp

                <div class="users-review">
                    @if(session('tenant'))
                    @if ($review->reviewerID == session('tenant')->tenantID)
                    <div class="review-options-container">
                        <div class="review-options">
                            <i class="fas fa-bars" title="Setting"
                                onclick="toggleReviewOptions('{{ $review->reviewID }}')"></i>
                            <div class="options-menu" id="options-menu-{{ $review->reviewID }}">
                                <div class="option"
                                    onclick="openEditCommentModal('{{ $review->reviewID }}', '{{ $review->comment }}')">
                                    <i class="fas fa-edit"></i> Edit
                                </div>

                                <div class="option" onclick="confirmDelete('{{ $review->reviewID }}')">
                                    <i class="fas fa-trash"></i> Delete
                                    <form id="delete-form-{{ $review->reviewID }}" action="{{ route('delete_review') }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="reviewID" value="{{ $review->reviewID }}">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    <div class="user-review">

                        <div class="avatar-review">
                            <img class="user-review-avatar" src="{{ asset('storage/'. $photoPath) }}" alt="User Avatar">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <img class="info-box-avatar" src="{{ asset('storage/'. $photoPath) }}"
                                        alt="User Avatar">
                                    <span class="info-box-name">
                                        @if ($review->agent)
                                        {{ $review->agent->agentName }}
                                        @elseif ($review->tenant)
                                        {{ $review->tenant->tenantName }}
                                        @endif
                                    </span>
                                </div>
                                <a href="{{ url('/chatify/' . $review->reviewerID) }}" class="btn btn-primary">Chat With
                                    Me</a>
                            </div>
                        </div>

                        <div class="user-review-info">
                            <div class="user-review-details">
                                <p class="user-name">
                                    @if ($review->agent)
                                    {{ $review->agent->agentName }}
                                    @elseif ($review->tenant)
                                    {{ $review->tenant->tenantName }}
                                    @endif
                                </p>

                                <p class="user-review-date">
                                    {{ \Carbon\Carbon::parse($review->reviewDate)->diffForHumans() }}
                                    reviewed
                                </p>
                            </div>

                            <div class="user-review-stars">
                                @for ($i = 0; $i < 5; $i++) @if ($i < $review->rating)
                                    <i class="fas fa-star" style="color: #00ada0;"></i>
                                    @else
                                    <i class="fas fa-star" style="color: #ccc;"></i>
                                    @endif
                                    @endfor
                            </div>
                        </div>
                    </div>

                    <div class="user-review-comment">
                        <p>{{ $review->comment }}</p>
                    </div>

                    <div class="users-reply-container">
                        @php
                        $reviewReplies = $replies->where('ParentReviewID', $review->reviewID)->take(3);
                        $remainingRepliesCount = $replies->where('ParentReviewID', $review->reviewID)->count() - 3;
                        @endphp
                        <div class="review-underline" style="margin-left:60px;"></div>

                        @foreach ($reviewReplies as $reply)
                        @php
                        $photoPath = $review->agent && $review->agent->photo
                        ? $reply->agent->photo
                        : ($reply->agent ? '/users-avatar/agent.png' : ($reply->tenant && $review->tenant->photo ?
                        $reply->tenant->photo : '/users-avatar/landlord.png'));
                        @endphp
                        <div class="users-reply">
                            @if(session('tenant'))
                            @if ($reply->reviewerID == session('tenant')->tenantID)
                            <div class="review-options-container" style=" font-size: 14px;">
                                <div class="review-options">
                                    <i class="fas fa-bars" title="Setting"
                                        onclick="toggleReviewOptions('{{ $reply->reviewID }}')"></i>
                                    <div class="options-menu" id="options-menu-{{ $reply->reviewID }}">
                                        <div class="option"
                                            onclick="openEditCommentModal('{{ $reply->reviewID }}', '{{ $reply->comment }}')">
                                            <i class="fas fa-edit"></i> Edit
                                        </div>

                                        <div class="option" onclick="confirmDelete('{{ $reply->reviewID }}')">
                                            <i class="fas fa-trash"></i> Delete
                                            <form id="delete-form-{{ $reply->reviewID }}"
                                                action="{{ route('delete_review') }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                <input type="hidden" name="reviewID" value="{{ $reply->reviewID }}">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif

                            <div class="user-reply">

                                <div class="avatar-review">
                                    <img class="user-reply-avatar" src="{{ asset('storage/'. $photoPath) }}"
                                        alt="User Avatar">
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <img class="info-box-avatar" src="{{ asset('storage/'. $photoPath) }}"
                                                alt="User Avatar">
                                            <span class="info-box-name">
                                                @if ($reply->agent)
                                                {{ $reply->agent->agentName }}
                                                @elseif ($reply->tenant)
                                                {{ $reply->tenant->tenantName }}
                                                @endif
                                            </span>
                                        </div>
                                        <a href="{{ url('/chatify/' . $reply->reviewerID) }}"
                                            class="btn btn-primary">Chat With Me</a>
                                    </div>
                                </div>
                                <div class="user-reply-info">
                                    <div class="user-reply-details">
                                        <p class="user-reply-name">
                                            @if ($reply->agent)
                                            {{ $reply->agent->agentName }}
                                            @elseif ($reply->tenant)
                                            {{ $reply->tenant->tenantName }}
                                            @endif
                                        </p>

                                        <p class="user-reply-date">
                                            {{ \Carbon\Carbon::parse($reply->reviewDate)->diffForHumans() }}
                                            reviewed
                                        </p>
                                    </div>

                                    <div class="user-reply-stars">
                                        @for ($i = 0; $i < 5; $i++) @if ($i < $reply->rating)
                                            <i class="fas fa-star" style="color: #00ada0;"></i>
                                            @else
                                            <i class="fas fa-star" style="color: #ccc;"></i>
                                            @endif
                                            @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="user-reply-comment">
                                <p>{{ $reply->comment }}</p>
                            </div>
                        </div>
                        @endforeach



                        <div id="replies-container-{{ $review->reviewID }}" class="replies-container">
                            @foreach ($replies->where('ParentReviewID', $review->reviewID)->skip(3) as $remainingReply)
                            @php
                            $photoPath = $remainingReply->agent && $remainingReply->agent->photo
                            ? $remainingReply->agent->photo
                            : ($remainingReply->agent ? '/users-avatar/agent.png' : ($remainingReply->tenant &&
                            $review->tenant->photo ? $remainingReply->tenant->photo : '/users-avatar/landlord.png'));
                            @endphp
                            <div class="users-reply">
                                @if(session('tenant'))
                                @if ($remainingReply->reviewerID == session('tenant')->tenantID)
                                <div class="review-options-container" style=" font-size: 14px;">
                                    <div class="review-options">
                                        <i class="fas fa-bars" title="Setting"
                                            onclick="toggleReviewOptions('{{ $remainingReply->reviewID }}')"></i>
                                        <div class="options-menu" id="options-menu-{{ $remainingReply->reviewID }}">
                                            <div class="option"
                                                onclick="openEditCommentModal('{{ $remainingReply->reviewID }}', '{{ $remainingReply->comment }}')">
                                                <i class="fas fa-edit"></i> Edit
                                            </div>

                                            <div class="option"
                                                onclick="confirmDelete('{{ $remainingReply->reviewID }}')">
                                                <i class="fas fa-trash"></i> Delete
                                                <form id="delete-form-{{ $remainingReply->reviewID }}"
                                                    action="{{ route('delete_review') }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    <input type="hidden" name="reviewID"
                                                        value="{{ $remainingReply->reviewID }}">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endif

                                <div class="user-reply">
                                    <div class="avatar-review">
                                        <img class="user-reply-avatar" src="{{ asset('storage/'. $photoPath) }}"
                                            alt="User Avatar">
                                        <div class="info-box">
                                            <div class="info-box-content">
                                                <img class="info-box-avatar" src="{{ asset('storage/'. $photoPath) }}"
                                                    alt="User Avatar">
                                                <span class="info-box-name">
                                                    @if ($remainingReply->agent)
                                                    {{ $remainingReply->agent->agentName }}
                                                    @elseif ($remainingReply->tenant)
                                                    {{ $remainingReply->tenant->tenantName }}
                                                    @endif
                                                </span>
                                            </div>
                                            <a href="{{ url('/chatify/' . $remainingReply->reviewerID) }}"
                                                class="btn btn-primary">Chat With Me</a>
                                        </div>
                                    </div>
                                    <div class="user-reply-info">
                                        <div class="user-reply-details">
                                            <p class="user-reply-name">
                                                @if ($remainingReply->agent)
                                                {{ $remainingReply->agent->agentName }}
                                                @elseif ($remainingReply->tenant)
                                                {{ $remainingReply->tenant->tenantName }}

                                                @endif
                                            </p>
                                            <p class="user-reply-date">
                                                {{ \Carbon\Carbon::parse($remainingReply->reviewDate)->diffForHumans() }}
                                                reviewed
                                            </p>
                                        </div>
                                        <div class="user-reply-stars">
                                            @for ($i = 0; $i < 5; $i++) @if ($i < $remainingReply->rating)
                                                <i class="fas fa-star" style="color: #00ada0;"></i>
                                                @else
                                                <i class="fas fa-star" style="color: #ccc;"></i>
                                                @endif
                                                @endfor
                                        </div>
                                    </div>
                                </div>
                                <div class="user-reply-comment">
                                    <p>{{ $remainingReply->comment }}</p>
                                </div>
                            </div>
                            @endforeach
                            <form id="review-form-{{ $review->reviewID }}" method="POST"
                                action="{{ route('reply_review') }}">
                                @csrf
                                <input type="hidden" name="rating" data-review-id="{{ $review->reviewID }}" value="0">
                                <input type="hidden" name="ParentReviewID" value="{{ $review->reviewID }}">
                                <input type="hidden" name="reviewerID"
                                    value="{{ session('tenant') ? session('tenant')->tenantID : '' }}">
                                <input type="hidden" name="reviewItemID" value="{{$agent->agentID }}">
                                <div class="user-review-container">
                                    <div class="review-container">
                                        <div class="stars">
                                            <i class="fas fa-star star" data-rating="1"
                                                data-review-id="{{ $review->reviewID }}"></i>
                                            <i class="fas fa-star star" data-rating="2"
                                                data-review-id="{{ $review->reviewID }}"></i>
                                            <i class="fas fa-star star" data-rating="3"
                                                data-review-id="{{ $review->reviewID }}"></i>
                                            <i class="fas fa-star star" data-rating="4"
                                                data-review-id="{{ $review->reviewID }}"></i>
                                            <i class="fas fa-star star" data-rating="5"
                                                data-review-id="{{ $review->reviewID }}"></i>
                                        </div>
                                        <div class="input-container">
                                            <img class="user-reply-avatar"
                                                src="{{ session('tenant') && session('tenant')->photo !== null ? asset('storage/' . session('tenant')->photo) : asset('storage/users-avatar/landlord.png') }}"
                                                alt="User Avatar">
                                            <textarea name="reply" rows="1" data-review-id="{{ $review->reviewID }}"
                                                placeholder="Reply something here..." value=""
                                                oninput="autoResize(this)"></textarea>
                                            <i class="fas fa-paper-plane send-icon"
                                                onclick="replyReview('{{ $review->reviewID }}','review-form-{{ $review->reviewID }}')"></i>

                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if ($remainingRepliesCount > 0)
                    <div class="view-more-btn-container">
                        <button class="view-more-btn"
                            onclick="toggleReplies('{{ $review->reviewID }}', {{ $remainingRepliesCount }})">
                            <i id="icon-{{ $review->reviewID }}" class="fas fa-chevron-down"></i>
                            <span id="text-{{ $review->reviewID }}">Show {{ $remainingRepliesCount }} more
                                replies</span>
                        </button>
                    </div>
                    @else

                    <form id="reply-form-{{ $review->reviewID }}" method="POST" action="{{ route('reply_review') }}">
                        @csrf
                        <input type="hidden" name="rating" data-review-id="{{ $review->reviewID }}" value="0">
                        <input type="hidden" name="ParentReviewID" value="{{ $review->reviewID }}">
                        <input type="hidden" name="reviewerID"
                            value="{{ session('tenant') ? session('tenant')->tenantID : '' }}">
                        <input type="hidden" name="reviewItemID" value="{{$agent->agentID }}">
                        <div class="user-review-container">
                            <div class="review-container">
                                <div class="stars">
                                    <i class="fas fa-star star" data-rating="1"
                                        data-review-id="{{ $review->reviewID }}"></i>
                                    <i class="fas fa-star star" data-rating="2"
                                        data-review-id="{{ $review->reviewID }}"></i>
                                    <i class="fas fa-star star" data-rating="3"
                                        data-review-id="{{ $review->reviewID }}"></i>
                                    <i class="fas fa-star star" data-rating="4"
                                        data-review-id="{{ $review->reviewID }}"></i>
                                    <i class="fas fa-star star" data-rating="5"
                                        data-review-id="{{ $review->reviewID }}"></i>
                                </div>
                                <div class="input-container">
                                    <img class="user-reply-avatar"
                                        src="{{ session('tenant') && session('tenant')->photo !== null ? asset('storage/' . session('tenant')->photo) : asset('storage/users-avatar/landlord.png') }}"
                                        alt="User Avatar">
                                    <textarea name="reply" rows="1" data-review-id="{{ $review->reviewID }}"
                                        placeholder="Reply something here..." value=""
                                        oninput="autoResize(this)"></textarea>
                                    <i class="fas fa-paper-plane send-icon"
                                        onclick="replyReview('{{ $review->reviewID }}','reply-form-{{ $review->reviewID }}')"></i>

                                </div>

                            </div>
                        </div>

                    </form>
                    @endif
                    <div class="review-underline" style="  margin-bottom: 30px;"></div>
                </div>
                @endforeach
                <h3 style="padding:15px;">Add Reviews</h3>
                <form id="review-form" method="POST" action="{{ route('add_review') }}">
                    @csrf
                    <input type="hidden" name="reviewerID"
                        value="{{ session('tenant') ? session('tenant')->tenantID : '' }}">
                    <input type="hidden" name="reviewItemID" value="{{$agent->agentID }}">
                    <input type="hidden" name="add_rating" value="0">
                    <div class="user-review-container">
                        <div class="review-container">
                            <div class="stars" style="font-size: 22px;">
                                <i class="fas fa-star star" data-rating="1" data-review-id="0"></i>
                                <i class="fas fa-star star" data-rating="2" data-review-id="0"></i>
                                <i class="fas fa-star star" data-rating="3" data-review-id="0"></i>
                                <i class="fas fa-star star" data-rating="4" data-review-id="0"></i>
                                <i class="fas fa-star star" data-rating="5" data-review-id="0"></i>
                            </div>

                            <div class="input-container">
                                <img class="user-review-avatar"
                                    src="{{ session('tenant') && session('tenant')->photo !== null ? asset('storage/' . session('tenant')->photo) : asset('storage/users-avatar/landlord.png') }}"
                                    style="height:40px; width:40px;" alt="User Avatar">
                                <textarea name="comment" rows="1" placeholder="Write your comment here..."
                                    oninput="autoResize(this)"></textarea>
                                <i class="fas fa-paper-plane send-icon" onclick="submitReview()"></i>
                            </div>
                        </div>
                    </div>
                </form>
            </ul>
            @endif
                    </div>
                </div>

                <div id="rent-properties-content" class="agent-list-section" data-section="rent-properties"
                    style="display:none;">
                    <p class="latest-rent-property">
                        <i class="fas fa-scroll"></i>Total {{ $properties->count() }} Rent Properties Poster
                    </p>

                    @foreach ($properties as $property)
                    <div class="latest-property-container"
                        onclick="navigateToPropertyDetails('{{ route('properties.show', $property->propertyID) }}')">
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
                        <div class="latest-property">
                            <i class="fas fa-bookmark wishlist-star" title="Add to Wishlist"
                                id="wishlistStar{{ $property->propertyID }}"
                                style="color: {{ $wishlistData ? '#FFD700' : 'grey' }}"></i>
                            <form id="wishlistForm{{ $property->propertyID }}" action="{{ route('ToggleWishList') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="propertyID" id="propertyID"
                                    value="{{ $property->propertyID }}">
                                    <input type="hidden" name="tenantID" id="tenantID" value="{{ session('tenant') ? session('tenant')->tenantID : '' }}">

                            </form>

                            <div class="latest-property-image">
                                <img src="{{ asset('storage/'. $property->propertyPhotoPath) }}">
                                <div class="image-overlay">
                                    <p class="photo-count"><i class="fas fa-image"></i> {{$property->photos_count}}
                                        Photos</p>

                                </div>
                            </div>

                            @php
                            $state =
                            app('App\Http\Controllers\AgentController')->getPropertyState($property->propertyID);
                            @endphp

                            <div class="latest-property-details">
                                <p class="latest-property-name">{{$property->propertyName}}</p>
                                <p class="latest-property-price">For Rent: <span>RM{{$property->rentalAmount}}</span>
                                </p>
                                <div class="latest-property-filter">

                                    <p><i class="las la-city"></i>{{$property->propertyType}}</p>
                                    <p><i class="las la-tools"></i>{{$property->furnishingType}}</p>
                                    <p><i class="lab la-buffer"></i>{{$property->squareFeet}} SQ.FT</p>
                                    <p><i class="las la-flag"></i>{{$state}}</p>
                                    <p><i class="las la-bed"></i>{{$property->bedroomNum}} bedroom</p>
                                    <p><i class="las la-bath"></i>{{$property->bathroomNum}} bathrooms</p>

                                </div>
                            </div>
                        </div>
                        <p class="latest-property-description">{{$property->propertyDesc}}</p>
                        <div class="latest-agent-details">
                            @if ($agent->photo !== null)
                                <img src="{{ asset('storage/' . $agent->photo) }}" alt="Agent Avatar">
                                @else
                                <img src="{{ asset('storage/users-avatar/agent.png') }}" alt="Agent Avatar">
                                @endif
                            <div class="latest-agent-info">
                                <p class="latest-agent-name">{{ $agent->agentName }}</p>
                                <p class="latest-update-time">Updated at
                                    {{ \Carbon\Carbon::parse($property->updated_at)->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
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
                    <p> <i class="fa fa-envelope"></i> {{$agent->agentEmail}}</p>
                    <p> <i class=" fa fa-phone"></i> {{$agent->agentPhone}}</p>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal" id="editCommentModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog custom-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Comment</h5>
                <button type="button" id="editCommentCloseBtn" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="POST" action="{{ route('edit-review') }}" id="editCommentForm">
                    @csrf
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


            const targetContentSection = document.querySelector(`#${targetSectionId}-content`);
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

    @if(!session('tenant'))
            alert('Please log in first.');
            return;
        @endif
        
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
    const replyText = document.querySelector(`#${formID} textarea[name="reply"][data-review-id="${reviewID}"]`).value;
    const selectedRating = getSelectedRating(reviewID);
    console.log(replyText);
    console.log(selectedRating);
    console.log(formID);

    @if(!session('tenant'))
            alert('Please log in first.');
            return;
        @endif

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
@endsection