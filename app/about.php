<?php
/**
 * About Page
 */
$pageTitle = "Hakkında - dao.grow";
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <?php include 'partials/_head.php'; ?>
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Additional CSS for about page -->
    <style>
        :root {
            --primary-green: #22C55E;
            --primary-blue: #1E3A8A;
            --accent-green: #10B981;
            --dark-bg: #0F172A;
            --light-text: #F8FAFC;
            --card-bg: rgba(255, 255, 255, 0.05);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        
        .dark-theme {
            background-color: var(--dark-bg);
            color: var(--light-text);
        }
        
        .dark-theme .card {
            background-color: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .hero-section {
            position: relative;
            height: 50vh;
            min-height: 500px;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        
        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            filter: brightness(0.7);
            z-index: -1;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.8) 0%, rgba(16, 185, 129, 0.6) 100%);
            z-index: -1;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            padding: 2rem;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 400;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 2.5rem;
            width: 80px;
            height: 4px;
            background: var(--primary-green);
        }
        
        .content-section {
            padding: 6rem 0;
        }
        
        .content-section:nth-child(even) {
            background-color: rgba(16, 185, 129, 0.05);
        }
        
        .dark-theme .content-section:nth-child(even) {
            background-color: rgba(16, 185, 129, 0.1);
        }
        
        .feature-card {
            height: 100%;
            padding: 2rem;
            border-radius: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .dark-theme .feature-card:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: var(--primary-green);
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .feature-text {
            font-size: 1rem;
            line-height: 1.6;
            opacity: 0.8;
        }
        
        .image-container {
            position: relative;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .dark-theme .image-container {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .image-container img {
            width: 100%;
            height: auto;
            transition: transform 0.5s ease;
        }
        
        .image-container:hover img {
            transform: scale(1.05);
        }
        
        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 2rem;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            color: white;
        }
        
        .image-overlay h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .image-overlay p {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .cta-section {
            padding: 6rem 0;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-green) 100%);
            color: white;
        }
        
        .cta-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .cta-text {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .wallet-icons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .wallet-icon {
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .wallet-icon:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
        }
        
        .wallet-icon i {
            font-size: 1.5rem;
            color: white;
        }
        
        .stat-card {
            text-align: center;
            padding: 2rem;
            border-radius: 1rem;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .dark-theme .stat-card {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-green);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1.25rem;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.25rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .cta-title {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'partials/_navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
                <h1 class="hero-title">dao.grow: Web3 ile Topraksız Tarımı Yeniden Tanımlıyoruz</h1>
                <p class="hero-subtitle">Tarımsal bilgiyi NFT'lere dönüştürerek, sürdürülebilir tarımın geleceğini şekillendiriyoruz.</p>
                <a href="marketplace.php" class="btn connect-wallet-btn btn-lg">
                    Pazar Yerini Keşfet
                </a>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="content-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right" data-aos-duration="1000">
                    <h2 class="section-title">Misyon & Vizyon</h2>
                    <p class="lead mb-4">dao.grow, topraksız tarımı demokratikleştirmeyi ve tarımsal bilgiyi değerli NFT'lere dönüştürmeyi amaçlıyor.</p>
                    <p>Amacımız, tarım bilgisini blockchain üzerinde güvenli ve değerli bir varlığa dönüştürerek, çiftçilerin bilgilerini paylaşmalarını ve bu bilgilerden gelir elde etmelerini sağlamaktır.</p>
                    <p>Vizyonumuz, sürdürülebilir tarım uygulamalarını teşvik ederek ve tarımsal bilgiyi küresel olarak erişilebilir kılarak, gelecek nesiller için daha sağlıklı bir dünya yaratmaktır.</p>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000">
                    <div class="image-container">
                        <img src="/img/tarim-gelecegi.png" alt="Mission and Vision">
                        <div class="image-overlay">
                            <h3>Tarımın Geleceğini Şekillendiriyoruz</h3>
                            <p>Blockchain teknolojisi ile tarımsal bilgiyi değerli varlıklara dönüştürüyoruz.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Soilless Agriculture Section -->
<section class="content-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left" data-aos-duration="1000">
                <h2 class="section-title">Neden Topraksız Tarım?</h2>
                <p class="lead mb-4">Topraksız tarım, sürdürülebilir geleceği şekillendiriyor: Daha az kaynakla daha çok üretin, her yerde tarım yapın!</p>
                <p>Topraksız tarım, geleneksel yöntemlere göre devrim niteliğinde avantajlar sunar:</p>
                <ul class="mb-4">
                    <li>Su kullanımında %95'e varan tasarruf</li>
                    <li>%50'ye kadar daha yüksek ürün verimliliği</li>
                    <li>Toprak erozyonu ve arazi bağımlılığı olmadan üretim</li>
                    <li>Otomasyon ve IoT ile hassas tarım imkanı</li>
                    <li>Kimyasal gübre ve pestisit kullanımında %70'e varan azalma</li>
                    <li>Şehir çatı katlarından çöllere kadar her alanda tarım</li>
                </ul>
                <p>Toprakız ve dao.grow, SUI ağı üzerinde NFT tabanlı zirai reçeteler ve marketplace ile topraksız tarımı küresel çapta erişilebilir kılıyor.</p>
            </div>
            <div class="col-lg-6 order-lg-1" data-aos="fade-right" data-aos-duration="1000">
                <div class="image-container">
                    <img src="/img/neden-topraksiz.png" alt="Soilless Agriculture">
                    <div class="image-overlay">
                        <h3>Sürdürülebilir ve Akıllı Tarım</h3>
                        <p>Topraksız tarım ile kaynakları koruyarak yüksek kaliteli ürünler elde edin.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stats Row -->
        <div class="row mt-5 g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Su Tasarrufu</div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                <div class="stat-card">
                    <div class="stat-number">50%</div>
                    <div class="stat-label">Daha Fazla Verim</div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                <div class="stat-card">
                    <div class="stat-number">70%</div>
                    <div class="stat-label">Daha Az Kimyasal</div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Digitizing Prescriptions Section -->
    <section class="content-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right" data-aos-duration="1000">
                    <h2 class="section-title">Reçeteleri Dijitalleştirme</h2>
                    <p class="lead mb-4">Form tabanlı kolay arayüzle topraksız tarım reçetelerinizi dijitalleştirin ve benzersiz NFT'lere dönüştürün.</p>
                    <p>dao.grow platformunda, tarımsal reçetelerinizi kolayca NFT'lere dönüştürebilirsiniz:</p>
                    <ul class="mb-4">
                        <li>Kullanıcı dostu form arayüzü</li>
                        <li>Detaylı tarım parametreleri (sıcaklık, nem, CO2, pH, besin değerleri, ışıklandırma)</li>
                        <li>Blockchain üzerinde güvenli ve kalıcı kayıt</li>
                        <li>Benzersiz ve doğrulanabilir NFT'ler</li>
                    </ul>
                    <p>Reçeteleriniz, blockchain üzerinde güvenli bir şekilde saklanır ve pazar yerinde alınıp satılabilir.</p>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000">
                    <div class="image-container">
                        <img src="/img/tarim-nft.png" alt="NFT Creation">
                        <div class="image-overlay">
                            <h3>Tarımsal Bilgiyi NFT'ye Dönüştürün</h3>
                            <p>Reçetelerinizi blockchain üzerinde güvenli ve değerli varlıklara dönüştürün.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Revenue Model Section -->
    <section class="content-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left" data-aos-duration="1000">
                    <h2 class="section-title">Gelir Modeli</h2>
                    <p class="lead mb-4">NFT'lerinizi pazar yerinde alıp satarak pasif gelir elde edin ve DAO yönetim modeline katılın.</p>
                    <p>dao.grow platformunda, tarımsal reçetelerinizi NFT olarak satarak gelir elde edebilirsiniz:</p>
                    <ul class="mb-4">
                        <li>Pazar yerinde NFT'lerinizi satın</li>
                        <li>Telif hakkı gelirleri elde edin</li>
                        <li>Topluluk yönetimine katılın</li>
                        <li>Platform kararlarında söz sahibi olun</li>
                    </ul>
                    <p>DAO yönetim modeli sayesinde, platformun geleceğini şekillendirmede aktif rol alabilirsiniz.</p>
                </div>
                <div class="col-lg-6 order-lg-1" data-aos="fade-right" data-aos-duration="1000">
                    <div class="image-container">
                        <img src="/img/pazaryeri.png" alt="Marketplace">
                        <div class="image-overlay">
                            <h3>Pazar Yerinde Al-Sat</h3>
                            <p>NFT'lerinizi satarak gelir elde edin ve topluluk yönetimine katılın.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Powered by SUI Section -->
    <section class="content-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right" data-aos-duration="1000">
                    <h2 class="section-title">SUI ile Güçlendirildi</h2>
                    <p class="lead mb-4">SUI blockchain'in hızlı, düşük maliyetli ve çevre dostu özelliklerinden yararlanın.</p>
                    <p>dao.grow, SUI blockchain üzerinde çalışarak aşağıdaki avantajları sunar:</p>
                    <ul class="mb-4">
                        <li>Saniyede binlerce işlem kapasitesi</li>
                        <li>Düşük işlem ücretleri</li>
                        <li>Güvenli ve şeffaf işlemler</li>
                        <li>Gelişmiş kullanıcı deneyimi</li>
                    </ul>
                    <p>SUI blockchain sayesinde, tarımsal reçetelerinizi hızlı ve güvenli bir şekilde NFT'lere dönüştürebilirsiniz.</p>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000">
                    <div class="image-container">
                        <img src="/img/suiiiii.png" alt="SUI Blockchain">
                        <div class="image-overlay">
                            <h3>SUI Blockchain</h3>
                            <p>Hızlı, düşük maliyetli ve çevre dostu blockchain teknolojisi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="fade-up" data-aos-duration="1000">
                    <h2 class="cta-title">dao.grow'a Katılın</h2>
                    <p class="cta-text">Topraksız tarım devriminin bir parçası olun, tarımsal bilginizi NFT'lere dönüştürün ve gelir elde edin.</p>
                    <button class="btn connect-wallet-btn btn-lg" id="connectWalletCta">
                        <i class="bi bi-wallet2 me-2"></i>Cüzdanını Bağla
                    </button>
                    
                    <div class="wallet-icons">
                        <div class="wallet-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="wallet-icon">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                        <div class="wallet-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'partials/_footer.php'; ?>

    <?php include 'partials/_scripts.php'; ?>
    
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            once: true,
            offset: 100
        });
        
        // Connect wallet functionality
        $(document).ready(function() {
            $("#connectWalletCta").click(function() {
                alert("Cüzdan bağlantı fonksiyonu burada gerçekleştirilecek.");
            });
        });
    </script>
</body>
</html> 