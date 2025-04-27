<?php
/**
 * Common navbar for all pages
 */
// Define which page is active
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-flower1 me-2"></i>dao.grow</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" <?php echo $currentPage == 'index.php' ? 'aria-current="page"' : ''; ?> href="index.php">Ana Sayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'marketplace.php' ? 'active' : ''; ?>" <?php echo $currentPage == 'marketplace.php' ? 'aria-current="page"' : ''; ?> href="marketplace.php">Pazar Yeri</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'create-recipe.php' ? 'active' : ''; ?>" <?php echo $currentPage == 'create-recipe.php' ? 'aria-current="page"' : ''; ?> href="create-recipe.php">NFT Oluştur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'about.php' ? 'active' : ''; ?>" <?php echo $currentPage == 'about.php' ? 'aria-current="page"' : ''; ?> href="about.php">Hakkında</a>
                </li>
            </ul>
            <div class="theme-switch-wrapper">
                <i class="bi bi-moon-stars theme-switch-icon"></i>
                <label class="theme-switch">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider"></span>
                </label>
            </div>
            <button class="btn connect-wallet-btn" id="connectWallet">
                <i class="bi bi-wallet2 me-2"></i><span id="walletButtonText">Cüzdan Bağla</span>
            </button>
        </div>
    </div>
</nav>

<style>
    /* Styling for connected wallet button */
    .connect-wallet-btn.wallet-connected {
        background-color: #4CAF50;
        color: white;
        border-color: #4CAF50;
        font-weight: bold;
    }
    
    .connect-wallet-btn.wallet-connected:hover {
        background-color: #45a049;
        border-color: #45a049;
    }
    
    /* Make the wallet button text more visible */
    #walletButtonText {
        font-weight: 500;
    }
    
    /* Style for the connected icon */
    .connect-wallet-btn.wallet-connected i.bi-check-circle-fill {
        color: white;
    }
</style>