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
        
        /* Tooltip styles */
        .tooltip-icon {
            color: #6c757d;
            cursor: help;
            margin-left: 5px;
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
                        <input type="text" id="search-filter" class="form-control" placeholder="NFT Ara..." oninput="filterNFTs()">
                    </div>
                    <div class="col-md-4">
                        <select id="category-filter" class="form-select" onchange="filterNFTs()">
                            <option value="all">Tüm Kategoriler</option>
                            <option value="vegetables">Sebzeler</option>
                            <option value="fruits">Meyveler</option>
                            <option value="herbs">Bitkiler</option>
                            <option value="flowers">Çiçekler</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="sort-select" class="form-select" onchange="filterNFTs()">
                            <option value="newest">En Yeni</option>
                            <option value="price-low">Fiyat (Düşükten Yükseğe)</option>
                            <option value="price-high">Fiyat (Yüksekten Düşüğe)</option>
                            <option value="popular">En Popüler</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- NFT Grid - Will be populated by JavaScript -->
            <div class="row g-4 nft-grid">
                <!-- NFT cards will be dynamically inserted here -->
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
    
    <!-- Dummy NFT Data -->
    <script>
        // Dummy NFT data in JSON format
        const nftData = [
            {
                id: 1,
                name: "Domates Reçetesi",
                creator: "Ahmet Çiftçi",
                price: 1.2,
                currency: "SUI",
                image: "https://placehold.co/600x400/4abe8a/ffffff?text=Domates+Reçetesi",
                category: "vegetables",
                likes: 124,
                views: 856,
                description: "Bu reçete, organik domates yetiştirme tekniklerini içerir. Sulama, gübreleme ve hastalık kontrolü hakkında detaylı bilgiler sunar."
            },
            {
                id: 2,
                name: "Elma Reçetesi",
                creator: "Mehmet Yılmaz",
                price: 2.5,
                currency: "SUI",
                image: "https://placehold.co/600x400/4abe8a/ffffff?text=Elma+Reçetesi",
                category: "fruits",
                likes: 98,
                views: 654,
                description: "Elma ağaçlarının bakımı ve verimli meyve üretimi için özel teknikler. Budama, ilaçlama ve hasat zamanlaması hakkında bilgiler içerir."
            },
            {
                id: 3,
                name: "Nane Reçetesi",
                creator: "Ayşe Demir",
                price: 0.8,
                currency: "SUI",
                image: "https://placehold.co/600x400/4abe8a/ffffff?text=Nane+Reçetesi",
                category: "herbs",
                likes: 156,
                views: 789,
                description: "Nane bitkisinin yetiştirilmesi ve bakımı için özel reçete. Toprak hazırlığı, sulama ve hasat teknikleri hakkında detaylı bilgiler."
            },
            {
                id: 4,
                name: "Gül Reçetesi",
                creator: "Zeynep Kaya",
                price: 3.7,
                currency: "SUI",
                image: "https://placehold.co/600x400/4abe8a/ffffff?text=Gül+Reçetesi",
                category: "flowers",
                likes: 210,
                views: 1023,
                description: "Gül yetiştirme ve bakımı için özel teknikler. Çiçeklenme dönemini uzatmak ve hastalıkları önlemek için ipuçları içerir."
            },
            {
                id: 5,
                name: "Salatalık Reçetesi",
                creator: "Ali Yıldız",
                price: 1.5,
                currency: "SUI",
                image: "https://placehold.co/600x400/4abe8a/ffffff?text=Salatalık+Reçetesi",
                category: "vegetables",
                likes: 87,
                views: 567,
                description: "Salatalık yetiştirme teknikleri ve verimli üretim için özel reçete. Sulama, gübreleme ve hastalık kontrolü hakkında bilgiler."
            },
            {
                id: 6,
                name: "Portakal Reçetesi",
                creator: "Fatma Şahin",
                price: 4.2,
                currency: "SUI",
                image: "https://placehold.co/600x400/4abe8a/ffffff?text=Portakal+Reçetesi",
                category: "fruits",
                likes: 178,
                views: 876,
                description: "Portakal ağaçlarının bakımı ve verimli meyve üretimi için özel teknikler. Budama, ilaçlama ve hasat zamanlaması hakkında bilgiler."
            },
            {
                id: 7,
                name: "Fesleğen Reçetesi",
                creator: "Mustafa Öztürk",
                price: 0.6,
                currency: "SUI",
                image: "https://placehold.co/600x400/4abe8a/ffffff?text=Fesleğen+Reçetesi",
                category: "herbs",
                likes: 92,
                views: 432,
                description: "Fesleğen bitkisinin yetiştirilmesi ve bakımı için özel reçete. Toprak hazırlığı, sulama ve hasat teknikleri hakkında detaylı bilgiler."
            },
            {
                id: 8,
                name: "Orkide Reçetesi",
                creator: "Selin Arslan",
                price: 5.8,
                currency: "SUI",
                image: "https://placehold.co/600x400/4abe8a/ffffff?text=Orkide+Reçetesi",
                category: "flowers",
                likes: 245,
                views: 1245,
                description: "Orkide yetiştirme ve bakımı için özel teknikler. Çiçeklenme dönemini uzatmak ve hastalıkları önlemek için ipuçları içerir."
            },
            {
                id: 9,
                name: "Biber Reçetesi",
                creator: "Hasan Kılıç",
                price: 1.8,
                currency: "SUI",
                image: "https://placehold.co/600x400/4abe8a/ffffff?text=Biber+Reçetesi",
                category: "vegetables",
                likes: 112,
                views: 678,
                description: "Biber yetiştirme teknikleri ve verimli üretim için özel reçete. Sulama, gübreleme ve hastalık kontrolü hakkında bilgiler."
            },
            {
                id: 10,
                name: "Muz Reçetesi",
                creator: "Deniz Yılmaz",
                price: 3.2,
                currency: "SUI",
                image: "https://placehold.co/600x400/4abe8a/ffffff?text=Muz+Reçetesi",
                category: "fruits",
                likes: 167,
                views: 890,
                description: "Muz yetiştirme teknikleri ve verimli üretim için özel reçete. Sulama, gübreleme ve hastalık kontrolü hakkında bilgiler."
            },
            {
                id: 11,
                name: "Kekik Reçetesi",
                creator: "Emine Demir",
                price: 0.9,
                currency: "SUI",
                image: "https://placehold.co/600x400/4abe8a/ffffff?text=Kekik+Reçetesi",
                category: "herbs",
                likes: 134,
                views: 567,
                description: "Kekik bitkisinin yetiştirilmesi ve bakımı için özel reçete. Toprak hazırlığı, sulama ve hasat teknikleri hakkında detaylı bilgiler."
            },
            {
                id: 12,
                name: "Papatya Reçetesi",
                creator: "Burak Kaya",
                price: 2.1,
                currency: "SUI",
                image: "https://placehold.co/600x400/4abe8a/ffffff?text=Papatya+Reçetesi",
                category: "flowers",
                likes: 156,
                views: 789,
                description: "Papatya yetiştirme ve bakımı için özel teknikler. Çiçeklenme dönemini uzatmak ve hastalıkları önlemek için ipuçları içerir."
            }
        ];

        // Function to render NFT cards
        function renderNFTCards(nfts) {
            const nftGrid = document.querySelector('.nft-grid');
            nftGrid.innerHTML = '';
            
            nfts.forEach(nft => {
                const card = document.createElement('div');
                card.className = 'col-md-4 col-lg-3 mb-4';
                card.innerHTML = `
                    <div class="nft-card">
                        <img src="${nft.image}" class="nft-image w-100" alt="${nft.name}">
                        <div class="nft-details">
                            <h3 class="nft-title">
                                ${nft.name}
                                <i class="bi bi-info-circle tooltip-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="${nft.description}"></i>
                            </h3>
                            <p class="nft-creator">Oluşturan: ${nft.creator}</p>
                            <p class="nft-price">${nft.price} ${nft.currency}</p>
                            <div class="nft-stats">
                                <span><i class="bi bi-heart"></i> ${nft.likes}</span>
                                <span><i class="bi bi-eye"></i> ${nft.views}</span>
                            </div>
                        </div>
                    </div>
                `;
                nftGrid.appendChild(card);
            });
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Function to filter NFTs
        function filterNFTs() {
            const searchTerm = document.getElementById('search-filter').value.toLowerCase();
            const category = document.getElementById('category-filter').value;
            const sortBy = document.getElementById('sort-select').value;
            
            let filteredNFTs = [...nftData];
            
            // Apply search filter
            if (searchTerm) {
                filteredNFTs = filteredNFTs.filter(nft => 
                    nft.name.toLowerCase().includes(searchTerm) || 
                    nft.creator.toLowerCase().includes(searchTerm)
                );
            }
            
            // Apply category filter
            if (category !== 'all') {
                filteredNFTs = filteredNFTs.filter(nft => nft.category === category);
            }
            
            // Apply sorting
            switch(sortBy) {
                case 'price-low':
                    filteredNFTs.sort((a, b) => a.price - b.price);
                    break;
                case 'price-high':
                    filteredNFTs.sort((a, b) => b.price - a.price);
                    break;
                case 'popular':
                    filteredNFTs.sort((a, b) => b.likes - a.likes);
                    break;
                case 'newest':
                default:
                    filteredNFTs.sort((a, b) => b.id - a.id);
                    break;
            }
            
            renderNFTCards(filteredNFTs);
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            renderNFTCards(nftData);
            
            // No need for event listeners as we're using oninput and onchange attributes
        });
    </script>
</body>
</html> 