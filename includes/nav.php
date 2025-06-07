<nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <i class="fas fa-birthday-cake me-2"></i>Sweety Cake
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./contact.php">Contact</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center ms-3 gap-3">
                    <a href="./cart.php" class="nav-link p-0" title="Cart"><i class="fas fa-shopping-cart fa-lg"></i></a>
                    <a href="./wishlist.php" class="nav-link p-0" title="Wishlist"><i class="fas fa-heart fa-lg"></i></a>
                    <div class="dropdown">
                        <a href="#" class="nav-link p-0 border-0" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="User" style="box-shadow:none;outline:none;border:none;">
                            <i class="fas fa-user fa-lg"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>