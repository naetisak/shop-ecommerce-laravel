<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-blue-200 to-blue-400 h-screen flex justify-center items-center">
    <div class="container mx-auto p-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-semibold text-gray-800 mb-4">Payment Successful</h1>
            <div class="bg-green-100 text-green-900 px-4 py-2 rounded-md mb-4 flex items-center">
                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7"></path>
                </svg>
                <p><strong>Success!</strong> Your payment was successful.</p>
            </div>
            <p class="text-gray-700">Thank you for shopping with us. Your order has been successfully processed.</p>
            <a href="{{ route('landing-page') }}"
                class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md">Back
                to Home</a>
        </div>
    </div>
</body>

</html>
