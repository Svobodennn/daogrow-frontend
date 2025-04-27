<?php
/**
 * Homepage
 */
$pageTitle = "dao.grow - Tarımsal Otomasyon Sistemi";
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <?php include 'partials/_head.php'; ?>
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
</head>
<body>
    <?php include 'partials/_navbar.php'; ?>

    <!-- Hero Slider Section -->
    <section class="hero-section">
        <div class="owl-carousel hero-slider">
            <div class="slider-item">
                <img src="./img/hidroponik-0.png" alt="dao.grow Slider 1" class="w-100 h-100 object-fit-cover">
                <div class="slider-overlay"></div>
                <div class="container">
                    <div class="slider-content">
                        <h1>Tarımın Geleceğine Hoşgeldiniz</h1>
                        <p>Blockchain teknolojisiyle güçlendirilmiş topraksız tarım sistemlerini keşfedin.</p>
                        <a href="marketplace" class="btn connect-wallet-btn btn-lg">Pazar Yerini Keşfet</a>
                    </div>
                </div>
            </div>
            <div class="slider-item">
                <img src="./img/dao-yonetimi-0.png" alt="dao.grow Slider 2" class="w-100 h-100 object-fit-cover">
                <div class="slider-overlay"></div>
                <div class="container">
                    <div class="slider-content">
                        <h1>Tarımsal NFT Pazar Yeri</h1>
                        <p>Tarım bilginizi NFT olarak paylaşın ve değer yaratın.</p>
                        <a href="create-recipe.php" class="btn connect-wallet-btn btn-lg">NFT Oluştur</a>
                    </div>
                </div>
            </div>
            <div class="slider-item">
                <img src="./img/grow-token-0.png" alt="dao.grow Slider 3" class="w-100 h-100 object-fit-cover" 
                     data-prompt="">
                <div class="slider-overlay"></div>
                <div class="container">
                    <div class="slider-content">
                        <h1>GROW Token Ekonomisi</h1>
                        <p>Sistem yönetiminde aktif rol alın ve ürün hasadından pay alın.</p>
                        <a href="#" class="btn connect-wallet-btn btn-lg">Daha Fazla Bilgi</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 1: Topraksız Tarımın Geleceğini NFT ile Şekillendirin -->
    <section class="feature-section section-nft">
        <div class="container">
            <h2 class="section-title">Topraksız Tarımın Geleceğini NFT ile Şekillendirin</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h3 class="feature-title">Kendi Zirai Reçetelerinizi Oluşturun</h3>
                        <p class="feature-text">Form tabanlı kolay arayüzle topraksız tarım reçetelerinizi dijitalleştirin ve benzersiz NFT'lere dönüştürün.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-gem"></i>
                        </div>
                        <h3 class="feature-title">Değer Yaratın</h3>
                        <p class="feature-text">Tarım bilginizi blockchain üzerinde kalıcı, güvenli ve değerli bir varlığa çevirin.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-globe"></i>
                        </div>
                        <h3 class="feature-title">Küresel Çiftçilere Katılın</h3>
                        <p class="feature-text">dao.grow ile yenilikçi tarım çözümlerini dünya çapında paylaşın.</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="create-recipe.php" class="btn connect-wallet-btn btn-lg">Şimdi NFT Oluştur</a>
            </div>
        </div>
    </section>

    <!-- Section 2: Tarım Bilgisini Ticaretle Buluşturun -->
    <section class="image-section section-marketplace">
        <div class="container">
            <h2 class="section-title">Tarım Bilgisini Ticaretle Buluşturun</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="image-container">
                        <img src="./img/al-sat.png" alt="NFT Pazar Yeri">
                        <div class="image-overlay">
                            <h3>NFT Pazar Yerinde Al-Sat</h3>
                            <p>Oluşturduğunuz zirai reçete NFT'lerini dao.grow'un entegre pazar yerinde kolayca alıp satın.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="image-container">
                        <img src="./img/kazanc-elde-edin.png" alt="Kazanç Elde Edin">
                        <div class="image-overlay">
                            <h3>Kazanç Elde Edin</h3>
                            <p>Tarım bilginizi paylaşarak pasif gelir yaratın veya değerli reçeteleri koleksiyonunuza ekleyin.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="image-container">
                        <img src="https://placehold.co/600x400/4abe8a/ffffff?text=Güvenli+ve+Şeffaf" alt="Güvenli ve Şeffaf" 
                             data-prompt="SUI blockchain network visualization with secure transaction icons, agricultural data flowing through the network">
                        <div class="image-overlay">
                            <h3>Güvenli ve Şeffaf</h3>
                            <p>SUI ağıyla desteklenen platformumuz, her işlemi hızlı, güvenli ve izlenebilir kılar.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="image-container">
                        <img src="https://placehold.co/600x400/4abe8a/ffffff?text=SUI+Ağı" alt="SUI Ağı" 
                             data-prompt="SUI blockchain logo with agricultural elements, showing the speed and low cost advantages of the network">
                        <div class="image-overlay">
                            <h3>SUI ile Güçlendirildi</h3>
                            <p>Hızlı işlemler ve düşük maliyetlerle tarım bilginizi güvenle paylaşın.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="marketplace.php" class="btn connect-wallet-btn btn-lg">Pazar Yerini Keşfet</a>
            </div>
        </div>
    </section>

    <!-- Section 3: Decentralized Tarım Devrimine Katılın -->
    <section class="feature-section section-dao">
        <div class="container">
            <h2 class="section-title">Decentralized Tarım Devrimine Katılın</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <h3 class="feature-title">Topluluk ile Yönetin</h3>
                        <p class="feature-text">dao.grow'un geleceğini topluluk olarak şekillendirin, önerilerde bulunun ve ödüller kazanın.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-tree"></i>
                        </div>
                        <h3 class="feature-title">Sürdürülebilir Tarımı Destekleyin</h3>
                        <p class="feature-text">Topraksız tarım çözümleriyle çevre dostu bir geleceğe katkıda bulunun.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h3 class="feature-title">Herkes İçin Erişim</h3>
                        <p class="feature-text">SUI cüzdanınızı bağlayarak anında platforma katılın, teknik bilgi gerekmez!</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <button class="btn connect-wallet-btn btn-lg" id="connectWalletCta">
                    <i class="bi bi-wallet2 me-2"></i>Cüzdanını Bağla
                </button>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="call-to-action">
        <div class="container">
            <h2 class="cta-title">dao.grow'a Katılın</h2>
            <p class="mb-4">Geleceğin tarım sisteminin bir parçası olmak ve kazanç sağlamak için hemen başlayın.</p>
            <button class="btn connect-wallet-btn btn-lg" id="connectWalletCta">
                <i class="bi bi-wallet2 me-2"></i>Cüzdanınızı Bağlayın
            </button>
        </div>
    </section>

    <?php include 'partials/_footer.php'; ?>

    <?php include 'partials/_scripts.php'; ?>
    
    <!-- Owl Carousel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function() {
            // Initialize Owl Carousel
            $(".hero-slider").owlCarousel({
                items: 1,
                loop: true,
                margin: 0,
                nav: true,
                dots: true,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                navText: [
                    '<i class="bi bi-chevron-left"></i>',
                    '<i class="bi bi-chevron-right"></i>'
                ],
                responsive: {
                    0: {
                        nav: false
                    },
                    768: {
                        nav: true
                    }
                }
            });
            
            // Fix for nav button display
            $(".hero-slider .owl-nav button").css({
                'display': 'flex'
            });
            
        });
    </script>
</body>
</html> 