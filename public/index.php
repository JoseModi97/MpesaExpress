<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M-PESA STK Push</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Lipa na M-PESA</h3>
                    </div>
                    <div class="card-body">
                        <form id="mpesa-form">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="e.g., 2547xxxxxxxx" required>
                            </div>
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" id="amount" class="form-control" placeholder="e.g., 100" required>
                            </div>
                            <div class="form-group">
                                <label for="reference">Reference</label>
                                <input type="text" name="reference" id="reference" class="form-control" placeholder="e.g., Order-123" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" name="description" id="description" class="form-control" placeholder="e.g., Payment for goods" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Pay Now</button>
                        </form>
                        <div id="response" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#mpesa-form').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: 'pay.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#response').html('<div class="alert alert-success">' + response + '</div>');
                    },
                    error: function(xhr, status, error) {
                        $('#response').html('<div class="alert alert-danger">' + xhr.responseText + '</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>
