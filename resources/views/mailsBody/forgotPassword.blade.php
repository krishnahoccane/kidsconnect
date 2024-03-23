<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Forgot Password</h4>
                    </div>
                    <div class="card-body">
                        <p class="lead">Your Forgot Password is:</p>
                        <h2 class="text-center font-weight-bold text-primary">{{ $password }}</h2>
                    </div>
                    <div class="card-footer bg-light">
                        <p class="text-muted mb-0">This OTP is valid for a single use and should not be shared with anyone. If you didn't request this OTP, please ignore this email.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
