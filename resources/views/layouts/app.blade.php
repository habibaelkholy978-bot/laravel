<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Laravel App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <nav class="mb-4">
            <a href="{{ route('users.index') }}" class="btn btn-primary me-2">Users</a>
            <a href="{{ route('categories.index') }}" class="btn btn-primary me-2">Categories</a>
            <a href="{{ route('products.index') }}" class="btn btn-primary me-2">Products</a>
            <a href="{{ route('orders.index') }}" class="btn btn-primary">Orders</a>
        </nav>
        <hr>
    
    </div>
</body>
</html>