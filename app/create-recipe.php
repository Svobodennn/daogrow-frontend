<?php
/**
 * Create NFT Recipe Page
 */
$pageTitle = "Yeni Reçete Oluştur - dao.grow";
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <?php include 'partials/_head.php'; ?>
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Custom CSS for create recipe page -->
    <style>
        .recipe-form-card {
            border-radius: 15px;
            background: var(--card-bg);
            box-shadow: var(--card-shadow);
            padding: 30px;
        }
        
        .parameter-row {
            background: var(--section-bg);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        
        .parameter-row .form-control {
            background: var(--card-bg);
        }
        
        .step-card {
            background: var(--section-bg);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            background: var(--primary-purple);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }
        
        /* New styles for range input containers */
        .d-flex.align-items-center.gap-2 {
            background: var(--card-bg);
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .d-flex.align-items-center.gap-2:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-color: rgba(0, 0, 0, 0.15);
        }
        
        .d-flex.align-items-center.gap-2 span {
            font-weight: 500;
            min-width: 30px;
            text-align: center;
        }
        
        .form-range {
            height: 1.5rem;
            padding: 0;
            background-color: transparent;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        
        .form-range::-webkit-slider-thumb {
            width: 16px;
            height: 16px;
            margin-top: -7px;
            background-color: var(--primary-purple);
            border: 0;
            border-radius: 50%;
            -webkit-appearance: none;
            appearance: none;
        }
        
        .form-range::-moz-range-thumb {
            width: 16px;
            height: 16px;
            background-color: var(--primary-purple);
            border: 0;
            border-radius: 50%;
        }
        
        .form-range::-webkit-slider-runnable-track {
            width: 100%;
            height: 2px;
            color: transparent;
            cursor: pointer;
            background-color: #e9ecef;
            border-color: transparent;
            border-radius: 1rem;
        }
        
        .form-range::-moz-range-track {
            width: 100%;
            height: 2px;
            color: transparent;
            cursor: pointer;
            background-color: #e9ecef;
            border-color: transparent;
            border-radius: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'partials/_navbar.php'; ?>

    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <h1 class="page-title">Yeni NFT Reçetesi Oluştur</h1>
            <p class="page-subtitle">Özel tarım reçetenizi NFT olarak yayınlayın</p>
        </div>
    </header>

    <!-- Create Recipe Form -->
    <section class="create-recipe-section py-5">
        <div class="container">
            <div class="recipe-form-card">
                <form id="createRecipeForm">
                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h3 class="h4 mb-3">Temel Bilgiler</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Reçete Adı</label>
                                <input type="text" class="form-control" required id="name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fiyat (SUI)</label>
                                <input type="number" class="form-control" required min="0" id="price">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Reçete Açıklaması</label>
                                <textarea class="form-control" rows="3" placeholder="Reçetenizi kısaca açıklayın..." required id="description"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Growing Steps -->
                    <div class="mb-4">
                        <h3 class="h4 mb-3">Yetiştirme Fazları</h3>
                        <div id="stepsContainer">
                            <div class="step-card">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="step-number">1</div>
                                    <h4 class="h5 mb-0">Faz 1</h4>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">
                                            <i class="bi bi-hourglass-split me-1"></i> Gün
                                            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="Bu fazın kaç gün süreceğini belirleyen değer"></i>
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="range" min="1" max="90" value="30" class="form-range" id="days-step-1" oninput="document.getElementById('daysVal-step-1').textContent = this.value">
                                            <span id="daysVal-step-1">30</span>
                                            <span>gün</span>
                                            <input type="number" class="form-control ms-2" style="width: 80px;" min="1" max="90" value="30" id="daysInput-step-1" oninput="document.getElementById('days-step-1').value = this.value; document.getElementById('daysVal-step-1').textContent = this.value">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            <i class="bi bi-thermometer-half me-1"></i> Ortam Sıcaklığı (°C)
                                            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="Ortam sıcaklığı için ideal aralık: 20-28°C"></i>
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="range" min="15" max="35" value="20" class="form-range temp-min" id="tempMin-step-1" oninput="document.getElementById('tempMinVal-step-1').textContent = this.value">
                                            <span id="tempMinVal-step-1">20</span>
                                            <span>-</span>
                                            <input type="range" min="15" max="35" value="28" class="form-range temp-max" id="tempMax-step-1" oninput="document.getElementById('tempMaxVal-step-1').textContent = this.value">
                                            <span id="tempMaxVal-step-1">28</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            <i class="bi bi-droplet me-1"></i> Nem (%)
                                            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="Nem için ideal aralık: 50-70%"></i>
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="range" min="0" max="100" value="40" class="form-range nem-min" id="nemMin-step-1" oninput="document.getElementById('nemMinVal-step-1').textContent = this.value">
                                            <span id="nemMinVal-step-1">40</span>
                                            <span>-</span>
                                            <input type="range" min="0" max="100" value="60" class="form-range nem-max" id="nemMax-step-1" oninput="document.getElementById('nemMaxVal-step-1').textContent = this.value">
                                            <span id="nemMaxVal-step-1">60</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            <i class="bi bi-cloud me-1"></i> CO2 (ppm)
                                            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="CO2 için ideal aralık: 800-1200 ppm"></i>
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="range" min="0" max="2000" value="800" class="form-range co2-min" id="co2Min-step-1" oninput="document.getElementById('co2MinVal-step-1').textContent = this.value">
                                            <span id="co2MinVal-step-1">800</span>
                                            <span>-</span>
                                            <input type="range" min="0" max="2000" value="1200" class="form-range co2-max" id="co2Max-step-1" oninput="document.getElementById('co2MaxVal-step-1').textContent = this.value">
                                            <span id="co2MaxVal-step-1">1200</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            <i class="bi bi-beaker me-1"></i> pH Değeri
                                            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="pH için ideal aralık: 5.8-6.5"></i>
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="range" min="0" max="14" value="5.8" step="0.1" class="form-range ph-min" id="phMin-step-1" oninput="document.getElementById('phMinVal-step-1').textContent = this.value">
                                            <span id="phMinVal-step-1">5.8</span>
                                            <span>-</span>
                                            <input type="range" min="0" max="14" value="6.5" step="0.1" class="form-range ph-max" id="phMax-step-1" oninput="document.getElementById('phMaxVal-step-1').textContent = this.value">
                                            <span id="phMaxVal-step-1">6.5</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            <i class="bi bi-plug me-1"></i> EC Değeri
                                            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="EC için ideal aralık: 1.5-2.5 mS/cm"></i>
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="range" min="0" max="5" value="1.5" step="0.1" class="form-range ec-min" id="ecMin-step-1" oninput="document.getElementById('ecMinVal-step-1').textContent = this.value">
                                            <span id="ecMinVal-step-1">1.5</span>
                                            <span>-</span>
                                            <input type="range" min="0" max="5" value="2.5" step="0.1" class="form-range ec-max" id="ecMax-step-1" oninput="document.getElementById('ecMaxVal-step-1').textContent = this.value">
                                            <span id="ecMaxVal-step-1">2.5</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            <i class="bi bi-brightness-high me-1"></i> Işık Süresi (Saat)
                                            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="Işık süresi için ideal aralık: 12-18 saat"></i>
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="range" min="0" max="24" value="12" class="form-range light-min" id="lightMin-step-1" oninput="document.getElementById('lightMinVal-step-1').textContent = this.value">
                                            <span id="lightMinVal-step-1">12</span>
                                            <span>-</span>
                                            <input type="range" min="0" max="24" value="18" class="form-range light-max" id="lightMax-step-1" oninput="document.getElementById('lightMaxVal-step-1').textContent = this.value">
                                            <span id="lightMaxVal-step-1">18</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary" id="addStepBtn">
                            <i class="bi bi-plus-circle me-2"></i>Yeni Faz Ekle
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end">
                        <button type="submit" id="mintButton" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Reçeteyi Oluştur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php include 'partials/_footer.php'; ?>
    <?php include 'partials/_scripts.php'; ?>

    <script>
        $(document).ready(function() {
            // Add new step
            let stepCount = 1;
            $("#addStepBtn").click(function() {
                stepCount++;
                const stepId = `step-${stepCount}`;
                const newStep = `
                    <div class="step-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number">${stepCount}</div>
                            <h4 class="h5 mb-0">Faz ${stepCount}</h4>
                            <button type="button" class="btn btn-sm btn-danger ms-auto remove-step">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-hourglass-split me-1"></i> Gün
                                    <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="Bu fazın kaç gün süreceğini belirleyen değer"></i>
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="range" min="1" max="90" value="30" class="form-range" id="days-${stepId}" oninput="document.getElementById('daysVal-${stepId}').textContent = this.value">
                                    <span id="daysVal-${stepId}">30</span>
                                    <span>gün</span>
                                    <input type="number" class="form-control ms-2" style="width: 80px;" min="1" max="90" value="30" id="daysInput-${stepId}" oninput="document.getElementById('days-${stepId}').value = this.value; document.getElementById('daysVal-${stepId}').textContent = this.value">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-thermometer-half me-1"></i> Ortam Sıcaklığı (°C)
                                    <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="Ortam sıcaklığı için ideal aralık: 20-28°C"></i>
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="range" min="15" max="35" value="20" class="form-range temp-min" id="tempMin-${stepId}" oninput="document.getElementById('tempMinVal-${stepId}').textContent = this.value">
                                    <span id="tempMinVal-${stepId}">20</span>
                                    <span>-</span>
                                    <input type="range" min="15" max="35" value="28" class="form-range temp-max" id="tempMax-${stepId}" oninput="document.getElementById('tempMaxVal-${stepId}').textContent = this.value">
                                    <span id="tempMaxVal-${stepId}">28</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-droplet me-1"></i> Nem (%)
                                    <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="Nem için ideal aralık: 50-70%"></i>
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="range" min="0" max="100" value="40" class="form-range nem-min" id="nemMin-${stepId}" oninput="document.getElementById('nemMinVal-${stepId}').textContent = this.value">
                                    <span id="nemMinVal-${stepId}">40</span>
                                    <span>-</span>
                                    <input type="range" min="0" max="100" value="60" class="form-range nem-max" id="nemMax-${stepId}" oninput="document.getElementById('nemMaxVal-${stepId}').textContent = this.value">
                                    <span id="nemMaxVal-${stepId}">60</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-cloud me-1"></i> CO2 (ppm)
                                    <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="CO2 için ideal aralık: 800-1200 ppm"></i>
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="range" min="0" max="2000" value="800" class="form-range co2-min" id="co2Min-${stepId}" oninput="document.getElementById('co2MinVal-${stepId}').textContent = this.value">
                                    <span id="co2MinVal-${stepId}">800</span>
                                    <span>-</span>
                                    <input type="range" min="0" max="2000" value="1200" class="form-range co2-max" id="co2Max-${stepId}" oninput="document.getElementById('co2MaxVal-${stepId}').textContent = this.value">
                                    <span id="co2MaxVal-${stepId}">1200</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-beaker me-1"></i> pH Değeri
                                    <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="pH için ideal aralık: 5.8-6.5"></i>
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="range" min="0" max="14" value="5.8" step="0.1" class="form-range ph-min" id="phMin-${stepId}" oninput="document.getElementById('phMinVal-${stepId}').textContent = this.value">
                                    <span id="phMinVal-${stepId}">5.8</span>
                                    <span>-</span>
                                    <input type="range" min="0" max="14" value="6.5" step="0.1" class="form-range ph-max" id="phMax-${stepId}" oninput="document.getElementById('phMaxVal-${stepId}').textContent = this.value">
                                    <span id="phMaxVal-${stepId}">6.5</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-plug me-1"></i> EC Değeri
                                    <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="EC için ideal aralık: 1.5-2.5 mS/cm"></i>
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="range" min="0" max="5" value="1.5" step="0.1" class="form-range ec-min" id="ecMin-${stepId}" oninput="document.getElementById('ecMinVal-${stepId}').textContent = this.value">
                                    <span id="ecMinVal-${stepId}">1.5</span>
                                    <span>-</span>
                                    <input type="range" min="0" max="5" value="2.5" step="0.1" class="form-range ec-max" id="ecMax-${stepId}" oninput="document.getElementById('ecMaxVal-${stepId}').textContent = this.value">
                                    <span id="ecMaxVal-${stepId}">2.5</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-brightness-high me-1"></i> Işık Süresi (Saat)
                                    <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="Işık süresi için ideal aralık: 12-18 saat"></i>
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="range" min="0" max="24" value="12" class="form-range light-min" id="lightMin-${stepId}" oninput="document.getElementById('lightMinVal-${stepId}').textContent = this.value">
                                    <span id="lightMinVal-${stepId}">12</span>
                                    <span>-</span>
                                    <input type="range" min="0" max="24" value="18" class="form-range light-max" id="lightMax-${stepId}" oninput="document.getElementById('lightMaxVal-${stepId}').textContent = this.value">
                                    <span id="lightMaxVal-${stepId}">18</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $("#stepsContainer").append(newStep);
                
                // Initialize event handlers for the new step
                initializeStepHandlers(stepId);
            });

            // Function to initialize event handlers for a step
            function initializeStepHandlers(stepId) {
                // Days
                $(`#days-${stepId}`).on('input', function() {
                    $(`#daysInput-${stepId}`).val(this.value);
                });
                
                $(`#daysInput-${stepId}`).on('input', function() {
                    let val = parseInt(this.value);
                    if (val < 1) val = 1;
                    if (val > 90) val = 90;
                    this.value = val;
                    $(`#days-${stepId}`).val(val);
                    $(`#daysVal-${stepId}`).text(val);
                });
                
                // Temperature
                $(`#tempMin-${stepId}`).on('input', function() {
                    let min = parseInt(this.value);
                    let max = parseInt($(`#tempMax-${stepId}`).val());
                    if (min > max) {
                        $(`#tempMax-${stepId}`).val(min);
                        $(`#tempMaxVal-${stepId}`).text(min);
                    }
                });
                $(`#tempMax-${stepId}`).on('input', function() {
                    let max = parseInt(this.value);
                    let min = parseInt($(`#tempMin-${stepId}`).val());
                    if (max < min) {
                        $(`#tempMin-${stepId}`).val(max);
                        $(`#tempMinVal-${stepId}`).text(max);
                    }
                });

                // Humidity
                $(`#nemMin-${stepId}`).on('input', function() {
                    let min = parseInt(this.value);
                    let max = parseInt($(`#nemMax-${stepId}`).val());
                    if (min > max) {
                        $(`#nemMax-${stepId}`).val(min);
                        $(`#nemMaxVal-${stepId}`).text(min);
                    }
                });
                $(`#nemMax-${stepId}`).on('input', function() {
                    let max = parseInt(this.value);
                    let min = parseInt($(`#nemMin-${stepId}`).val());
                    if (max < min) {
                        $(`#nemMin-${stepId}`).val(max);
                        $(`#nemMinVal-${stepId}`).text(max);
                    }
                });

                // CO2
                $(`#co2Min-${stepId}`).on('input', function() {
                    let min = parseInt(this.value);
                    let max = parseInt($(`#co2Max-${stepId}`).val());
                    if (min > max) {
                        $(`#co2Max-${stepId}`).val(min);
                        $(`#co2MaxVal-${stepId}`).text(min);
                    }
                });
                $(`#co2Max-${stepId}`).on('input', function() {
                    let max = parseInt(this.value);
                    let min = parseInt($(`#co2Min-${stepId}`).val());
                    if (max < min) {
                        $(`#co2Min-${stepId}`).val(max);
                        $(`#co2MinVal-${stepId}`).text(max);
                    }
                });

                // pH
                $(`#phMin-${stepId}`).on('input', function() {
                    let min = parseFloat(this.value);
                    let max = parseFloat($(`#phMax-${stepId}`).val());
                    if (min > max) {
                        $(`#phMax-${stepId}`).val(min);
                        $(`#phMaxVal-${stepId}`).text(min);
                    }
                });
                $(`#phMax-${stepId}`).on('input', function() {
                    let max = parseFloat(this.value);
                    let min = parseFloat($(`#phMin-${stepId}`).val());
                    if (max < min) {
                        $(`#phMin-${stepId}`).val(max);
                        $(`#phMinVal-${stepId}`).text(max);
                    }
                });

                // EC
                $(`#ecMin-${stepId}`).on('input', function() {
                    let min = parseFloat(this.value);
                    let max = parseFloat($(`#ecMax-${stepId}`).val());
                    if (min > max) {
                        $(`#ecMax-${stepId}`).val(min);
                        $(`#ecMaxVal-${stepId}`).text(min);
                    }
                });
                $(`#ecMax-${stepId}`).on('input', function() {
                    let max = parseFloat(this.value);
                    let min = parseFloat($(`#ecMin-${stepId}`).val());
                    if (max < min) {
                        $(`#ecMin-${stepId}`).val(max);
                        $(`#ecMinVal-${stepId}`).text(max);
                    }
                });

                // Light Duration
                $(`#lightMin-${stepId}`).on('input', function() {
                    let min = parseInt(this.value);
                    let max = parseInt($(`#lightMax-${stepId}`).val());
                    if (min > max) {
                        $(`#lightMax-${stepId}`).val(min);
                        $(`#lightMaxVal-${stepId}`).text(min);
                    }
                });
                $(`#lightMax-${stepId}`).on('input', function() {
                    let max = parseInt(this.value);
                    let min = parseInt($(`#lightMin-${stepId}`).val());
                    if (max < min) {
                        $(`#lightMin-${stepId}`).val(max);
                        $(`#lightMinVal-${stepId}`).text(max);
                    }
                });
            }

            // Initialize event handlers for the first step
            initializeStepHandlers('step-1');

            // Remove step
            $(document).on('click', '.remove-step', function() {
                $(this).closest('.step-card').remove();
                // Update step numbers
                $('.step-card').each(function(index) {
                    $(this).find('.step-number').text(index + 1);
                    $(this).find('h4').text('Faz ' + (index + 1));
                });
                stepCount--;
            });

            
            // Real-time validation for min/max fields
            function validateMinMax(rangeContainer) {
                const minInput = rangeContainer.find('input').eq(0);
                const maxInput = rangeContainer.find('input').eq(1);
                const minVal = parseFloat(minInput.val());
                const maxVal = parseFloat(maxInput.val());
                
                if (isNaN(minVal) || isNaN(maxVal) || minVal > maxVal || minVal < 0 || maxVal < 0) {
                    minInput.addClass("is-invalid");
                    maxInput.addClass("is-invalid");
                } else {
                    minInput.removeClass("is-invalid");
                    maxInput.removeClass("is-invalid");
                }
            }

            // Attach real-time validation to all current and future range inputs
            function attachRealtimeValidation() {
                $("#stepsContainer").on('input', '.d-flex.align-items-center.gap-2 input', function() {
                    const rangeContainer = $(this).closest('.d-flex.align-items-center.gap-2');
                    validateMinMax(rangeContainer);
                });
            }
            attachRealtimeValidation();

            // Enable Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>  