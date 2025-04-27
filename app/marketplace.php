<?php
/**
 * NFT Marketplace Page
 */
$pageTitle = "Pazar Yeri - dao.grow";
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <?php include 'partials/_head.php'; ?>
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Additional CSS for marketplace -->
    <style>
        .marketplace-filters {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .nft-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .nft-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .nft-image {
            height: 200px;
            object-fit: cover;
        }
        
        .nft-details {
            padding: 15px;
        }
        
        .nft-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .nft-creator {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .nft-price {
            font-size: 1.1rem;
            font-weight: 600;
            color: #4abe8a;
        }
        
        .nft-stats {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 10px;
        }
        
        .pagination {
            margin-top: 30px;
        }
        
        .pagination .page-link {
            color: #4abe8a;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #4abe8a;
            border-color: #4abe8a;
        }
    </style>
</head>
<body>
    <?php include 'partials/_navbar.php'; ?>

    <!-- Marketplace Header -->
    <section class="page-header bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 fw-bold">NFT Pazar Yeri</h1>
                    <p class="lead">Tarımsal reçeteleri keşfedin, satın alın veya kendi NFT'lerinizi oluşturun.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="create-recipe.php" class="btn connect-wallet-btn">
                        <i class="bi bi-plus-circle me-2"></i>Yeni NFT Oluştur
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Marketplace Content -->
    <section class="marketplace-content py-5">
        <div class="container">
            <!-- Filters -->
            <div class="marketplace-filters">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" id="search-filter" class="form-control" placeholder="NFT Ara...">
                    </div>
                    <div class="col-md-3">
                        <select id="category-filter" class="form-select">
                            <option value="all">Tüm Kategoriler</option>
                            <option value="vegetables">Sebzeler</option>
                            <option value="fruits">Meyveler</option>
                            <option value="herbs">Bitkiler</option>
                            <option value="flowers">Çiçekler</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="sort-select" class="form-select">
                            <option value="newest">En Yeni</option>
                            <option value="price-low">Fiyat (Düşükten Yükseğe)</option>
                            <option value="price-high">Fiyat (Yüksekten Düşüğe)</option>
                            <option value="popular">En Popüler</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button id="filter-button" class="btn connect-wallet-btn w-100">Filtrele</button>
                    </div>
                </div>
            </div>

            <!-- NFT Grid - Will be populated by JavaScript -->
            <div class="row g-4 nft-grid">
                <!-- NFT cards will be dynamically inserted here -->
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">NFT'ler yükleniyor...</p>
                </div>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation" class="mt-5">
                <div id="pagination-container">
                    <ul class="pagination justify-content-center">
                        <!-- Pagination will be dynamically inserted here -->
                    </ul>
                </div>
            </nav>
        </div>
    </section>

    <?php include 'partials/_footer.php'; ?>

    <?php include 'partials/_scripts.php'; ?>
    
    <!-- Make sure jQuery is loaded -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</body>
</html> 