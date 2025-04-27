<?php
/**
 * DAO Governance page
 */
$pageTitle = "DAO Yönetimi - dao.grow";
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <?php include 'partials/_head.php'; ?>
    <title><?php echo $pageTitle; ?></title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include 'partials/_navbar.php'; ?>

    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <h1 class="page-title">DAO Yönetimi</h1>
            <p class="page-subtitle">Tarımsal parametrelerimizi yönetmek için oylama sistemine katılın</p>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container flex-grow-1">
        <div class="row">
            <!-- Left Column - Wallet & Token Info -->
            <div class="col-lg-4">
                <!-- Wallet Info -->
                <div class="card wallet-card mb-4" id="wallet-info">
                    <div class="card-body">
                        <h5 class="card-title">Cüzdan Bilgisi</h5>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <div id="wallet-status">Cüzdan bağlı değil</div>
                                <div class="wallet-address" id="wallet-address"></div>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" id="copyAddress">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Token Info -->
                <div class="card mb-4" id="token-info-card" style="display: none;">
                    <div class="card-header bg-white">Token Bilgileri</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="token-label">GROW Bakiyesi</div>
                                <div class="token-amount" id="token-balance">0</div>
                            </div>
                            <div class="col-md-6">
                                <div class="token-label">Stake Edilmiş</div>
                                <div class="token-amount" id="staked-amount">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Stake/Unstake Form -->
                <div class="card mb-4" id="stake-card" style="display: none;">
                    <div class="card-header bg-white">Token Yönetimi</div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="stakeTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="stake-tab" data-bs-toggle="tab" data-bs-target="#stake" type="button" role="tab" aria-controls="stake" aria-selected="true">Stake Et</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="unstake-tab" data-bs-toggle="tab" data-bs-target="#unstake" type="button" role="tab" aria-controls="unstake" aria-selected="false">Unstake Et</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="stakeTabContent">
                            <div class="tab-pane fade show active" id="stake" role="tabpanel" aria-labelledby="stake-tab">
                                <p class="text-muted small">DAO'da oy kullanmak için tokenlarınızı stake etmeniz gerekir.</p>
                                <form id="stake-form">
                                    <div class="mb-3">
                                        <label for="stake-amount" class="form-label">Miktar</label>
                                        <input type="number" class="form-control" id="stake-amount" step="0.1" min="0" placeholder="0.0">
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn connect-wallet-btn" id="stake-btn">Stake Et</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="unstake" role="tabpanel" aria-labelledby="unstake-tab">
                                <p class="text-muted small">Stake edilmiş tokenlarınızı çekebilirsiniz.</p>
                                <form id="unstake-form">
                                    <div class="mb-3">
                                        <label for="unstake-amount" class="form-label">Miktar</label>
                                        <input type="number" class="form-control" id="unstake-amount" step="0.1" min="0" placeholder="0.0">
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn connect-wallet-btn" id="unstake-btn">Unstake Et</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Transaction History -->
                <div class="card" id="tx-history-card" style="display: none;">
                    <div class="card-header bg-white">İşlem Geçmişi</div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="tx-history">
                            <!-- Transactions will be added dynamically -->
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Proposals & Governance -->
            <div class="col-lg-8">
                <!-- Active Proposals -->
                <div class="card mb-4" id="proposals-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span>Aktif Teklifler</span>
                        <button class="btn btn-sm connect-wallet-btn" data-bs-toggle="modal" data-bs-target="#newProposalModal" id="new-proposal-btn" style="display: none;">
                            <i class="bi bi-plus-lg me-1"></i> Yeni Teklif
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="proposals-container">
                            <div class="text-center py-4" id="proposals-loading">
                                <p>Cüzdanınızı bağlayarak teklifleri görüntüleyebilirsiniz.</p>
                            </div>
                            <!-- Proposals will be dynamically added here -->
                        </div>
                    </div>
                </div>
                
                <!-- Info Card -->
                <div class="card">
                    <div class="card-header bg-white">dao.grow Hakkında</div>
                    <div class="card-body">
                        <h5>GROW Token</h5>
                        <p>GROW Token, tarımsal sensör verilerimizi ve sistemimizi yönetmek için kullanılan bir yönetişim tokenidir.</p>
                        <p>Toplam Arz: 1,000,000 GROW</p>
                        <h5 class="mt-4">DAO Katılımı</h5>
                        <p>Tokenlarınızı stake ederek aşağıdaki kararlar hakkında oy kullanabilirsiniz:</p>
                        <ul>
                            <li>Sensör verilerinin güncellenmesi</li>
                            <li>Sisteme yeni sensörlerin eklenmesi</li>
                            <li>Otomatik sulama sistemi koşulları</li>
                            <li>Tarımsal parametrelerin optimizasyonu</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- New Proposal Modal -->
    <div class="modal fade" id="newProposalModal" tabindex="-1" aria-labelledby="newProposalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newProposalModalLabel">Yeni Teklif Oluştur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="proposal-form">
                        <div class="mb-3">
                            <label for="proposal-title" class="form-label">Teklif Başlığı</label>
                            <input type="text" class="form-control" id="proposal-title" required>
                        </div>
                        <div class="mb-3">
                            <label for="proposal-description" class="form-label">Teklif Açıklaması</label>
                            <textarea class="form-control" id="proposal-description" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="proposal-duration" class="form-label">Oylama Süresi (epoch)</label>
                            <input type="number" class="form-control" id="proposal-duration" min="1" value="10">
                            <small class="text-muted">1 epoch yaklaşık 24 saattir.</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn connect-wallet-btn" id="submit-proposal-btn">Teklif Oluştur</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'partials/_footer.php'; ?>
    
    <?php include 'partials/_scripts.php'; ?>
    
    <!-- SUI.js -->
    <script type="module">
        import { 
            connectWallet, 
            disconnectWallet,
            isWalletConnected,
            getWalletAddress,
            getTokenBalance,
            getStakedAmount,
            stakeTokens,
            unstakeTokens,
            createProposal,
            voteOnProposal,
            setContractAddresses
        } from './js/sui-integration.js';
        
        // Set contract addresses (to be updated after deployment)
        const PACKAGE_ID = 'YOUR_PACKAGE_ID'; // Update after deployment
        const STAKE_REGISTRY_ID = 'YOUR_STAKE_REGISTRY_ID'; // Update after deployment
        const DAO_ID = 'YOUR_DAO_ID'; // Update after deployment
        const GROW_TYPE = 'YOUR_GROW_TYPE'; // Update after deployment (changed from AMT_TYPE)
        
        setContractAddresses(PACKAGE_ID, STAKE_REGISTRY_ID, DAO_ID, GROW_TYPE);
        
        // Sample proposals data - to be replaced with real data from contract
        const sampleProposals = [
            {
                id: 1,
                title: "EC Değerlerinin Güncellenmesi",
                description: "EC değerlerinin 1.8 mS/cm'den 2.0 mS/cm'ye yükseltilmesini öneriyorum.",
                creator: "0x1234...abcd",
                createdAt: "2023-09-10T12:00:00Z",
                endTime: "2023-09-17T12:00:00Z",
                yesVotes: 35000,
                noVotes: 15000,
                status: "active"
            },
            {
                id: 2,
                title: "Yeni Sıcaklık Sensörü Eklenmesi",
                description: "Seranın kuzey bölgesine yeni bir sıcaklık sensörü eklenmesini öneriyorum.",
                creator: "0x5678...efgh",
                createdAt: "2023-09-08T14:30:00Z",
                endTime: "2023-09-15T14:30:00Z",
                yesVotes: 42000,
                noVotes: 8000,
                status: "active"
            }
        ];
        
        // DOM elements
        const connectWalletBtn = document.getElementById('connectWallet');
        const walletAddress = document.getElementById('wallet-address');
        const walletStatus = document.getElementById('wallet-status');
        const copyAddressBtn = document.getElementById('copyAddress');
        const tokenBalanceElem = document.getElementById('token-balance');
        const stakedAmountElem = document.getElementById('staked-amount');
        const tokenInfoCard = document.getElementById('token-info-card');
        const stakeCard = document.getElementById('stake-card');
        const txHistoryCard = document.getElementById('tx-history-card');
        const txHistoryList = document.getElementById('tx-history');
        const newProposalBtn = document.getElementById('new-proposal-btn');
        const proposalsContainer = document.getElementById('proposals-container');
        const proposalsLoading = document.getElementById('proposals-loading');
        
        // Wallet connect/disconnect
        connectWalletBtn.addEventListener('click', async () => {
            if (isWalletConnected()) {
                // Disconnect wallet
                await disconnectWallet();
                updateWalletUI(false);
            } else {
                // Connect wallet
                connectWalletBtn.innerHTML = '<span class="spinner-border spinner-border-sm loading-spinner" role="status" aria-hidden="true"></span> Bağlanıyor...';
                connectWalletBtn.disabled = true;
                
                const result = await connectWallet();
                
                if (result.success) {
                    updateWalletUI(true, result.address);
                    loadTokenData();
                    loadProposals();
                } else {
                    alert('Cüzdan bağlantısı başarısız: ' + result.error);
                    connectWalletBtn.innerHTML = 'SUI Cüzdanı Bağla';
                    connectWalletBtn.disabled = false;
                }
            }
        });
        
        // Copy wallet address
        copyAddressBtn.addEventListener('click', () => {
            const address = walletAddress.innerText;
            navigator.clipboard.writeText(address).then(() => {
                alert('Adres panoya kopyalandı!');
            });
        });
        
        // Stake form submit
        document.getElementById('stake-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const amount = parseFloat(document.getElementById('stake-amount').value);
            if (!amount || amount <= 0) {
                alert('Lütfen geçerli bir miktar girin.');
                return;
            }
            
            const stakeBtn = document.getElementById('stake-btn');
            stakeBtn.innerHTML = '<span class="spinner-border spinner-border-sm loading-spinner" role="status" aria-hidden="true"></span> İşleniyor...';
            stakeBtn.disabled = true;
            
            const result = await stakeTokens(amount);
            
            if (result.success) {
                alert('Tokenlarınız başarıyla stake edildi!');
                document.getElementById('stake-amount').value = '';
                
                // Add to transaction history
                addTransaction('Stake', amount + ' GROW', result.txId);
                
                // Reload token data
                await loadTokenData();
            } else {
                alert('Stake işlemi başarısız: ' + result.error);
            }
            
            stakeBtn.innerHTML = 'Stake Et';
            stakeBtn.disabled = false;
        });
        
        // Unstake form submit
        document.getElementById('unstake-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const amount = parseFloat(document.getElementById('unstake-amount').value);
            if (!amount || amount <= 0) {
                alert('Lütfen geçerli bir miktar girin.');
                return;
            }
            
            const unstakeBtn = document.getElementById('unstake-btn');
            unstakeBtn.innerHTML = '<span class="spinner-border spinner-border-sm loading-spinner" role="status" aria-hidden="true"></span> İşleniyor...';
            unstakeBtn.disabled = true;
            
            const result = await unstakeTokens(amount);
            
            if (result.success) {
                alert('Tokenlarınız başarıyla unstake edildi!');
                document.getElementById('unstake-amount').value = '';
                
                // Add to transaction history
                addTransaction('Unstake', amount + ' GROW', result.txId);
                
                // Reload token data
                await loadTokenData();
            } else {
                alert('Unstake işlemi başarısız: ' + result.error);
            }
            
            unstakeBtn.innerHTML = 'Unstake Et';
            unstakeBtn.disabled = false;
        });
        
        // Submit proposal
        document.getElementById('submit-proposal-btn').addEventListener('click', async () => {
            const title = document.getElementById('proposal-title').value;
            const description = document.getElementById('proposal-description').value;
            const duration = parseInt(document.getElementById('proposal-duration').value);
            
            if (!title || !description || !duration) {
                alert('Lütfen tüm alanları doldurun.');
                return;
            }
            
            const submitBtn = document.getElementById('submit-proposal-btn');
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm loading-spinner" role="status" aria-hidden="true"></span> Oluşturuluyor...';
            submitBtn.disabled = true;
            
            // Combine title and description for the proposal
            const fullDescription = `${title}\n\n${description}`;
            
            const result = await createProposal(fullDescription, duration);
            
            if (result.success) {
                alert('Teklif başarıyla oluşturuldu!');
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('newProposalModal')).hide();
                
                // Reset form
                document.getElementById('proposal-form').reset();
                
                // Add to transaction history
                addTransaction('Teklif Oluşturma', title, result.txId);
                
                // Reload proposals
                loadProposals();
            } else {
                alert('Teklif oluşturma başarısız: ' + result.error);
            }
            
            submitBtn.innerHTML = 'Teklif Oluştur';
            submitBtn.disabled = false;
        });
        
        // Function to update the wallet UI
        function updateWalletUI(connected, address = '') {
            if (connected) {
                connectWalletBtn.innerHTML = 'Cüzdan Çıkış';
                connectWalletBtn.classList.remove('connect-wallet-btn');
                connectWalletBtn.classList.add('btn-outline-danger');
                
                walletStatus.innerHTML = '<span class="badge bg-success me-2">Bağlı</span> SUI Cüzdan';
                walletAddress.innerText = formatAddress(address);
                
                // Show token info and stake cards
                tokenInfoCard.style.display = 'block';
                stakeCard.style.display = 'block';
                txHistoryCard.style.display = 'block';
                newProposalBtn.style.display = 'block';
            } else {
                connectWalletBtn.innerHTML = 'SUI Cüzdanı Bağla';
                connectWalletBtn.classList.remove('btn-outline-danger');
                connectWalletBtn.classList.add('connect-wallet-btn');
                connectWalletBtn.disabled = false;
                
                walletStatus.innerHTML = 'Cüzdan bağlı değil';
                walletAddress.innerText = '';
                
                // Hide token info and stake cards
                tokenInfoCard.style.display = 'none';
                stakeCard.style.display = 'none';
                txHistoryCard.style.display = 'none';
                newProposalBtn.style.display = 'none';
                
                // Update proposals UI
                proposalsLoading.innerHTML = 'Cüzdanınızı bağlayarak teklifleri görüntüleyebilirsiniz.';
            }
        }
        
        // Function to format address
        function formatAddress(address) {
            if (!address) return '';
            return address.substring(0, 6) + '...' + address.substring(address.length - 4);
        }
        
        // Function to load token data
        async function loadTokenData() {
            // Get token balance
            const balanceResult = await getTokenBalance();
            if (balanceResult.success) {
                tokenBalanceElem.innerText = balanceResult.balance.toFixed(2);
            } else {
                tokenBalanceElem.innerText = '0';
            }
            
            // Get staked amount
            const stakedResult = await getStakedAmount();
            if (stakedResult.success) {
                stakedAmountElem.innerText = stakedResult.stakedAmount.toFixed(2);
            } else {
                stakedAmountElem.innerText = '0';
            }
        }
        
        // Function to load proposals
        async function loadProposals() {
            // Clear existing proposals
            proposalsContainer.innerHTML = '';
            proposalsLoading.innerHTML = '<span class="spinner-border spinner-border-sm loading-spinner" role="status" aria-hidden="true"></span> Teklifler yükleniyor...';
            
            // In a real application, we would fetch proposals from the blockchain here
            // For now, we'll use the sample data
            setTimeout(() => {
                proposalsLoading.style.display = 'none';
                
                if (sampleProposals.length === 0) {
                    proposalsContainer.innerHTML = '<p class="text-center py-4">Henüz aktif teklif bulunmamaktadır.</p>';
                    return;
                }
                
                sampleProposals.forEach(proposal => {
                    const totalVotes = proposal.yesVotes + proposal.noVotes;
                    const yesPercentage = totalVotes > 0 ? Math.round((proposal.yesVotes / totalVotes) * 100) : 0;
                    const noPercentage = totalVotes > 0 ? Math.round((proposal.noVotes / totalVotes) * 100) : 0;
                    
                    const endDate = new Date(proposal.endTime);
                    const now = new Date();
                    const daysLeft = Math.ceil((endDate - now) / (1000 * 60 * 60 * 24));
                    
                    const proposalElement = document.createElement('div');
                    proposalElement.className = 'card proposal-card mb-3';
                    proposalElement.innerHTML = `
                        <div class="card-body">
                            <h5 class="proposal-title">${proposal.title}</h5>
                            <div class="proposal-meta">
                                <span class="me-3"><i class="bi bi-person me-1"></i> ${formatAddress(proposal.creator)}</span>
                                <span><i class="bi bi-clock me-1"></i> ${daysLeft} gün kaldı</span>
                            </div>
                            <p>${proposal.description}</p>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: ${yesPercentage}%" aria-valuenow="${yesPercentage}" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-danger" role="progressbar" style="width: ${noPercentage}%" aria-valuenow="${noPercentage}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="vote-counts mt-1 mb-3">
                                <span>Evet: ${(proposal.yesVotes / 1000).toFixed(1)}k (%${yesPercentage})</span>
                                <span>Hayır: ${(proposal.noVotes / 1000).toFixed(1)}k (%${noPercentage})</span>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-sm btn-outline-success me-2 vote-btn vote-btn-yes" data-proposal-id="${proposal.id}" data-vote="yes">
                                    <i class="bi bi-check-lg me-1"></i>Evet Oyu
                                </button>
                                <button class="btn btn-sm btn-outline-danger vote-btn vote-btn-no" data-proposal-id="${proposal.id}" data-vote="no">
                                    <i class="bi bi-x-lg me-1"></i>Hayır Oyu
                                </button>
                            </div>
                        </div>
                    `;
                    
                    proposalsContainer.appendChild(proposalElement);
                });
                
                // Add event listeners to vote buttons
                document.querySelectorAll('.vote-btn').forEach(button => {
                    button.addEventListener('click', async (e) => {
                        const proposalId = e.target.getAttribute('data-proposal-id');
                        const voteType = e.target.getAttribute('data-vote') === 'yes';
                        
                        e.target.innerHTML = '<span class="spinner-border spinner-border-sm loading-spinner" role="status" aria-hidden="true"></span>';
                        e.target.disabled = true;
                        
                        const result = await voteOnProposal(parseInt(proposalId), voteType);
                        
                        if (result.success) {
                            alert('Oyunuz başarıyla kaydedildi!');
                            
                            // Add to transaction history
                            addTransaction('Oylama', `Teklif #${proposalId} - ${voteType ? 'Evet' : 'Hayır'}`, result.txId);
                            
                            // Reload proposals
                            loadProposals();
                        } else {
                            alert('Oylama başarısız: ' + result.error);
                            e.target.innerHTML = voteType ? 'Evet Oyu' : 'Hayır Oyu';
                            e.target.disabled = false;
                        }
                    });
                });
            }, 1000);
        }
        
        // Function to add a transaction to the history
        function addTransaction(type, description, txHash) {
            const txItem = document.createElement('li');
            txItem.className = 'transaction-item list-group-item';
            
            const now = new Date();
            const timeStr = now.toLocaleTimeString();
            
            txItem.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="transaction-type">${type}</div>
                        <div class="text-muted small">${description}</div>
                    </div>
                    <div class="text-end">
                        <div class="transaction-hash">
                            <a href="https://explorer.sui.io/transaction/${txHash}" target="_blank" class="text-truncate d-inline-block" style="max-width: 100px;">
                                ${formatAddress(txHash)}
                            </a>
                        </div>
                        <div class="transaction-time">${timeStr}</div>
                    </div>
                </div>
            `;
            
            txHistoryList.prepend(txItem);
            
            // Limit to 5 transactions
            if (txHistoryList.children.length > 5) {
                txHistoryList.removeChild(txHistoryList.lastChild);
            }
        }
        
        // Check if wallet is already connected on page load
        window.addEventListener('DOMContentLoaded', async () => {
            if (isWalletConnected()) {
                const address = getWalletAddress();
                updateWalletUI(true, address);
                await loadTokenData();
                loadProposals();
            }
        });
    </script>
</body>
</html>