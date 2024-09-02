<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
        }
        .contact-section {
            padding: 50px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .contact-info-box {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin-bottom: 20px;
            text-align: center;
            transition: transform 0.3s;
        }
        .contact-info-box:hover {
            transform: translateY(-5px);
        }
        .contact-info-box i {
            font-size: 50px;
            color: #5a67d8;
            margin-bottom: 15px;
        }
        .contact-info-box h4 {
            margin-top: 10px;
            color: #333;
            font-weight: bold;
        }
        .contact-info-box p {
            color: #666;
            font-size: 16px;
        }
        .contact-form {
            width: 100%;
            max-width: 500px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #5a67d8;
            border: none;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
        }
        .btn-custom:hover {
            background-color: #4c51bf;
        }
        .form-control, .form-control:focus {
            border-color: #5a67d8;
            border-radius: 8px;
            box-shadow: none;
        }
        @media (min-width: 768px) {
            .contact-section {
                flex-direction: row;
                gap: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container contact-section">
        <!-- Email Us Section -->
        <div class="contact-info-box">
            <i class="fas fa-envelope"></i>
            <h4>Email Us</h4>
            <p>anita@glansa.in</p>
        </div>

        <!-- Call Us Section -->
        <div class="contact-info-box">
            <i class="fas fa-phone"></i>
            <h4>Call Us</h4>
            <p>+123 456 7890</p>
        </div>

        <!-- Contact Form Section -->
        <div class="contact-form">
            <h4>Contact Form</h4>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    @error('message')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-custom w-100 mt-3">Send Message</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
