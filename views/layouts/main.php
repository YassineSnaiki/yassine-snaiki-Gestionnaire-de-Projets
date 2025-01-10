<!DOCTYPE html>
<html lang="en">
<head>
    <script>window.history.forward(0);</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanban Board</title>
    
    <link href="./css/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.css">
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-xl font-bold text-gray-800">TaskFlow</a>
                    </div>
                    <!-- Navigation Links -->
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="/" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 border-b-2 border-indigo-500">
                            Projects
                        </a>
                    </div>
                </div>
                <!-- Right side -->
                <div class="flex items-center">
                    <?php if (isset($_SESSION['user']) || isset($_SESSION['admin'])): ?>
                        <?php if (isset($_SESSION['user'])): ?>
                            <span class="text-gray-700 mr-4"><?= $_SESSION['user']['firstname'] ?></span>
                        <?php endif; ?>
                        <a class="btn-logout inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="/login" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        {{content}}<br/>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-lg mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4">
            <div class="flex justify-between items-center">
                <div class="text-gray-500 text-sm">
                    2025 TaskFlow. All rights reserved.
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-500 hover:text-gray-700">
                        About
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-700">
                        Privacy
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-700">
                        Terms
                    </a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        document.querySelector('.btn-logout')?.addEventListener('click',()=>{
            location.replace('/logout');
            
        })
    </script>
</body>
</html>

