<html>

<head>
    <meta charset="UTF-8">
    <title>Make Payment</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">

    <style>
    body {
        background: #f5f5f5
    }

    .rounded {
        border-radius: 1rem
    }

    .nav-pills .nav-link {
        color: #555
    }

    .nav-pills .nav-link.active {
        color: white
    }

    input[type="radio"] {
        margin-right: 5px
    }

    .bold {
        font-weight: bold
    }

    .property-details img {
        border-radius: 8px;
        width: 300px;
        height: 200px;

    }
    </style>
</head>

<body>
    <?php $__env->startSection('content'); ?>
    <div class="container">

        <a href="<?php echo e(route('applicationIndex')); ?>" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2 style="margin-bottom:0px;">Advance rental for Property <?php echo e($propertyRental->property->propertyName); ?> </h2>
<p> <i>Property Address : <?php echo e($propertyRental->property->propertyAddress); ?></i></p>
        <div class="row m-0">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 property-details d-flex justify-content-center">
                        <img src="<?php echo e(Storage::url( $propertyRental->property->propertyPhotos[0]->propertyPath)); ?>"
                            alt="Property Photo">
                           
                    </div>
                    <div class="col-12 p-3 property-details d-flex justify-content-center">
                        <div class="row m-0 bg-light">
                            <div class="col-md-4 col-6 ps-30 pe-0 my-4">
                                <i class="las la-building"></i> <?php echo e($propertyRental->property->propertyType); ?>


                            </div>
                            <div class="col-md-4 col-6  ps-30 my-4">
                                <i class="las la-crop-alt"></i> <?php echo e($propertyRental->property->squareFeet); ?> sqft

                            </div>
                            <div class="col-md-4 col-6 ps-30 my-4">
                                <i class="las la-brush"></i> <?php echo e($propertyRental->property->furnishingType); ?>


                            </div>
                            <div class="col-md-4 col-6 ps-30 my-4">
                                <i class="las la-brush"></i> <?php echo e($propertyRental->property->buildYear); ?> Year

                            </div>
                            <div class="col-md-4 col-6 ps-30 my-4">
                                <i class="las la-bed"></i> <?php echo e($propertyRental->property->bedroomNum); ?> Bedroom

                            </div>
                            <div class="col-md-4 col-6 ps-30 my-4">
                                <i class="las la-bath"></i> <?php echo e($propertyRental->property->bathroomNum); ?> Bathroom
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row m-0">
                    <div class="col-12 px-4">
                        <div class="paymentDetails">
                            <?php
                            $paymentAmount = $propertyRental->property->depositAmount +
                            $propertyRental->property->rentalAmount ;
                            ?>

                            <div class="d-flex align-items-end mt-4 mb-2">
                                <p class="h4 m-0"><span class="pe-1">Rental ID : </span><span
                                        class="pe-1"><?php echo e($propertyRental->propertyRentalID); ?></span></p>
                             
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <p class="textmuted">Advance Rental </p>
                                <p class="fs-14 fw-bold"> RM <?php echo e($propertyRental->property->rentalAmount); ?> </p>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <p class="textmuted">Security Deposit </p>
                                <p class="fs-14 fw-bold"> RM <?php echo e($propertyRental->property->depositAmount); ?>

                                </p>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <p class="textmuted">Agent Fee ( <?php echo e($propertyRental->property->agent->agentName); ?> )</p>
                                <p class="fs-14 fw-bold">Free</p>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <p class="textmuted fw-bold">Total</p>

                                <span class="h4"> RM <?php echo e(number_format($paymentAmount,2)); ?></span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Method Selection -->
        <div class="row">
            <div class="col-12">
                <div class="card ">
                    <div class="card-header">
                        <div class="bg-white shadow-sm pt-4 pl-2 pr-2 pb-2">
                            <!-- Credit card form tabs -->
                            <ul role="tablist" class="nav nav-pills rounded nav-fill mb-3">
                                <li class="nav-item"> <i class="fas fa-credit-card mr-2"></i> Credit Card
                                </li>
                            </ul>
                        </div> <!-- End -->
                        <!-- Credit card form content -->
                        <div class="tab-content">
                            <!-- credit card info-->
                            <div id="credit-card" class="tab-pane fade show active ">
                                <!-- Top-Up Amount -->
                                <div class="form-group">
                                    <form action="/tenant/session" method="POST">

                                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                        <input type="hidden" name="propertyRentalID"
                                            value="<?php echo e($propertyRental->propertyRentalID); ?>">
                                        <input type="hidden" name="propertyName"
                                            value="<?php echo e($propertyRental->property->propertyName); ?>">

                                        <input type="hidden" id="amount" value="<?php echo e($paymentAmount); ?>" name="amount">

                                        <label for="rentalStartDate">Choose your preffered rental effective
                                            date:</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="rentalStartDate"
                                                id="startNow" value="now" checked>
                                            <label class="form-check-label" for="startNow">
                                                Start On [ <?php echo e(now()->format('F j, Y')); ?> ] (Today)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="rentalStartDate"
                                                id="startNextMonth" value="next_month">
                                            <label for="startNextMonth">
                                                Start On [
                                                <?php echo e(now()->firstOfMonth()->addMonth()->format('F j, Y')); ?> ] (Next
                                                Month)
                                            </label>

                                        </div>

                                </div>
                            </div>
                            <div class="card-footer"> <button type="submit"
                                    class="subscribe btn btn-primary btn-block shadow-sm"> Continue Payment
                                </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>




    <?php $__env->stopSection(); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="  https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>

    <script>
    $(fu nctio $('[data-toggle="tooltip"]') lti
    })
    </script>


</body>

</html>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/tenant/paymentCreate.blade.php ENDPATH**/ ?>