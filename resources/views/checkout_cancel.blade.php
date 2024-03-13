<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-red-200 to-red-400 h-screen flex justify-center items-center">
    <div class="container mx-auto p-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-semibold text-gray-800 mb-4">Payment Cancelled</h1>
            <div class="bg-red-100 text-red-900 px-4 py-2 rounded-md mb-4 flex items-center">
                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <p><strong>Cancelled!</strong> Your payment was cancelled.</p>
            </div>
            <p class="text-gray-700">Your payment was cancelled. If you need any assistance, please contact our support team.</p>
            <a href="{{ route('landing-page') }}"
                class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md">Back
                to Home</a>
        </div>
    </div>
</body>

</html>
