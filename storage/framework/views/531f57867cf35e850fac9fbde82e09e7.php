<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Wallet Withdrawal</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    <style>
    .withdraw {

        max-width: 90%;
    }
    </style>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2 withdraw">

        <a href="<?php echo e(route('agentWallet')); ?>" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2>Withdrawal</h2>

        <!-- Display Wallet Balance -->
        <p>Your Wallet Balance: RM <?php echo e($agentBalance); ?></p>
        <form method="POST" action="<?php echo e(route('withdraw')); ?>">
            <?php echo csrf_field(); ?>
            <!-- Withdrawal Amount -->
            <div class="form-group">
                <label>Withdrawal Amount</label>
                <div class="input-group">
                    <select class="custom-select" id="withdrawAmountSelect" name="withdrawAmountSelect"
                        onchange="updateCustomAmount()">
                        <option value="50">RM50</option>
                        <option value="100">RM100</option>
                        <option value="200">RM200</option>
                        <option value="500">RM500</option>
                        <option value="1000">RM1000</option>
                        <option value="5000">RM5000</option>
                    </select>

                    <!-- Add the checkbox for custom amount -->
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="customAmountCheckbox" onclick="toggleCustomAmount()">
                        </div>
                    </div>

                    <!-- Custom Amount Input Field -->
                    <input type="text" class="form-control" id="customWithdrawAmount" name="customWithdrawAmount"
                        placeholder="Enter Custom Amount" disabled>
                </div>
            </div>

            <script>
            function toggleCustomAmount() {
                // Enable or disable the custom amount input field based on checkbox status
                var customAmountInput = document.getElementById('customWithdrawAmount');
                var customAmountCheckbox = document.getElementById('customAmountCheckbox');
                customAmountInput.disabled = !customAmountCheckbox.checked;

                // If checkbox is checked, clear the predefined select value
                if (customAmountCheckbox.checked) {
                    document.getElementById('withdrawAmountSelect').value = '';
                }
            }

            function updateCustomAmount() {
                var withdrawAmountSelect = document.getElementById('withdrawAmountSelect');
                var customWithdrawAmountInput = document.getElementById('customWithdrawAmount');

                // Check if the selected value is 'custom'
                if (withdrawAmountSelect.value === 'custom') {
                    // Enable the customWithdrawAmount input field
                    customWithdrawAmountInput.disabled = false;
                } else {
                    // Disable the customWithdrawAmount input field
                    customWithdrawAmountInput.disabled = true;
                }
            }
            </script>


            <!-- Bank Selection -->
            <div class="form-group">
                <label for="bank">Select Bank</label>
                <select class="custom-select" id="bank" name="bank">
                    <option value="ambank">AmBank</option>
                    <option value="maybank">Maybank</option>
                    <option value="cimb">CIMB Bank</option>
                    <option value="publicbank">Public Bank</option>
                    <option value="rhb">RHB Bank</option>
                    <option value="hongleong">Hong Leong Bank</option>
                    <option value="standardchartered">Standard Chartered Bank</option>
                    <option value="ocbc">OCBC Bank</option>
                    <option value="uob">United Overseas Bank (UOB)</option>
                    <option value="hsbc">HSBC Bank</option>
                    <option value="bankislam">Bank Islam</option>
                    <option value="affinbank">Affin Bank</option>
                </select>


            </div>

            <!-- Bank Account Number -->
            <div class="form-group">
                <label for="accountNumber">Bank Account Number</label>
                <input type="text" class="form-control" id="accountNumber" name="accountNumber"
                    oninput="checkAccountNumberValidity()">
                    <small id="accountNumberError" class="text-danger"></small>
            </div>

            <!-- Withdraw Button -->
            <button type="submit" class="btn btn-primary" id="withdrawButton" disabled>Withdraw</button>

            <p class="text-muted"> Note: After clicking on the button, your withdrawal will be processed by RentSpace.
                It may takes 1-3 working days to receive the amount of money to credited to your bank. </p>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    document.getElementById('accountNumber').addEventListener('input', function() {
        checkAccountNumberValidity(); // Corrected function name
    });

    function checkAccountNumberValidity() {
        // Retrieve the entered account number
        var accountNumber = document.getElementById('accountNumber').value;

        // Your validation logic here
        var isValid = validateAccountNumber(accountNumber);

        // Enable or disable the withdraw button based on validity
        document.getElementById('withdrawButton').disabled = !isValid;
    }

    function validateAccountNumber(accountNumber) {
        var accountNumberInput = document.getElementById('accountNumber');
        var accountNumberError = document.getElementById('accountNumberError');
        var accountNumber = accountNumberInput.value.trim();

        // Clear previous error message
        accountNumberError.textContent = '';

        // Check if the account number is numeric
        if (!/^\d+$/.test(accountNumber)) {
            accountNumberError.textContent = 'Please enter a valid numeric account number.';
            accountNumberInput.classList.add('is-invalid');
            return false;
        }

        // Additional checks for a valid bank account number (you may customize this part)
        if (accountNumber.length < 10 || accountNumber.length > 20) {
            accountNumberError.textContent =
                'Please enter a valid bank account number (between 10 and 20 digits).';
            accountNumberInput.classList.add('is-invalid');
            return false;
        }

        // Clear validation error
        accountNumberInput.classList.remove('is-invalid');
        return true;
    }
</script>


    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/walletWithdraw.blade.php ENDPATH**/ ?>