<!DOCTYPE html>
<html>

<head>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    header {
        text-align: center;
        padding: 20px;
    }

    header img {
        display: block;
        margin: 0 auto;
        max-width: 200px;
    }

    h1 {
        font-size: 24px;
        margin-top: 20px;
    }

    main {
        background-color: #fff;
        padding: 20px;
        text-align: center;
    }

    p {
        font-size: 16px;
        margin-bottom: 20px;
    }

    a {
        display: inline-block;
        text-decoration: none;
        font-weight: bold;
    }

    footer {
        padding: 20px;
        text-align: center;
        color: #979595;
    }

    .line {
        border-top: 1px solid #ccc;
        margin: 20px 0;
    }
    </style>
</head>

<body>
    <header>
        <h1>[RentSpace]<?php echo $header; ?></h1>
        <p class="reset-description"> <?php echo $desc; ?> </p>
    </header>

    <div class="line"></div>
    <main>
        <p>Dear User,</p>
        <p> <?php echo $emailContent; ?></p>

        <p> <?php echo $note; ?></p>
    </main>
    <footer>
        <div class="line"></div>
        <h4>RentSpace Technical Support Team</h4>
        <p>If you need any assistance using the RentSpace, please email us or contact +60 14 616-6273</p>
        <img src="<?php echo e($message->embed($imagePath)); ?>" alt="image">
    </footer>
</body>

</html><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/emailContent.blade.php ENDPATH**/ ?>