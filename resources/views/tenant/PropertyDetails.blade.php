@extends('layouts.header')
<link rel="stylesheet" href="{{ asset('/storage/css/Review.css') }}" media="screen">
@section('content')

<form id="review-form" method="POST" action="{{ route('add_review') }}">
        @csrf
        <input type="hidden" name="rating" value="0">
        <div class="review-container">
            <div class="stars">
                <i class="fas fa-star star" data-rating="1"></i>
                <i class="fas fa-star star" data-rating="2"></i>
                <i class="fas fa-star star" data-rating="3"></i>
                <i class="fas fa-star star" data-rating="4"></i>
                <i class="fas fa-star star" data-rating="5"></i>
            </div>
            <div class="input-container">
                <textarea name="comment" rows="1" placeholder="Write your comment" oninput="autoResize(this)"></textarea>
                <i class="fas fa-paper-plane send-icon" onclick="submitReview()"></i>
            </div>
        </div>
    </form>


    <script>
    let selectedRating = 0;

    const stars = document.querySelectorAll('.star');
    stars.forEach(star => {
        star.addEventListener('mouseenter', () => {
            const rating = parseInt(star.getAttribute('data-rating'));
            updateStarColors(rating);
        });

        star.addEventListener('mouseleave', () => {
            updateStarColors(selectedRating);
        });

        star.addEventListener('click', () => {
    selectedRating = parseInt(star.getAttribute('data-rating'));
    console.log('Clicked star rating:', selectedRating); 
    updateStarColors(selectedRating);
    document.querySelector('input[name="rating"]').value = selectedRating;
});

    });
    function updateStarColors(rating) {
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

        if (reviewText.trim() === '') {
            alert('Please enter a comment.');
            return;
        }

       

        if (reviewText.length > 200) {
            alert('Your comment is too long. Please keep it under 200 characters.');
            return;
        }

        document.getElementById('review-form').submit();
    }

</script>

@endsection