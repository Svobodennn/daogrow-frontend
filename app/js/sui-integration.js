// import { getWallets } from '@mysten/wallet-standard'; // Bu satırı silin veya yorum satırı yapın
import { SuiClient } from 'https://esm.sh/@mysten/sui.js/client';
import { getWallets } from "https://esm.sh/@mysten/wallet-standard"; // Bu satırı ekleyin
import { TransactionBlock } from 'https://esm.sh/@mysten/sui.js/transactions';

const connectButton = document.getElementById("connectWallet");
const mintButton = document.getElementById("mintButton");

let connectedWallet = null;
const PACKAGE_ID = "0x26d6bd700685057d2e4ece2c07e6f4947dec8bb7f1d0e55730a5d2d5acf29b0e";
const TREASURY_ID = "0xafe029a1232ddb44c73577dfd07c79d36d7246841b9153b3c15b6d4ca76deb11";
const CHAIN = "sui:testnet";

// Check if we're on the marketplace page and initialize accordingly
window.addEventListener("DOMContentLoaded", () => {
  // Check if we're on the marketplace page
  const isMarketplacePage = window.location.pathname.includes('marketplace.php');

  if (isMarketplacePage) {
    console.log("Marketplace page detected, initializing NFT marketplace...");
    // Make sure jQuery is loaded before initializing the marketplace
    if (typeof $ !== 'undefined') {
      initializeMarketplace();
    } else {
      console.error("jQuery is not loaded. Marketplace initialization failed.");
      // Try to load jQuery dynamically if needed
      const script = document.createElement('script');
      script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
      script.onload = function() {
        console.log("jQuery loaded dynamically, initializing marketplace...");
        initializeMarketplace();
      };
      document.head.appendChild(script);
    }
  }

  // LocalStorage'dan cüzdan bağlantı durumunu kontrol et
  const storedWalletInfo = localStorage.getItem('connectedWalletInfo');

  // Mevcut cüzdanları kontrol et
  const wallets = getWallets().get();
  console.log("Mevcut Cüzdanlar:", wallets);

  // İhtiyacınız olan cüzdanı burada filtreleyebilirsiniz.
  // Şimdilik ilk bulunan cüzdanı kullanacağız veya spesifik bir cüzdanı arayacağız.
  connectedWallet = wallets.find(
    (wallet) => wallet.name.includes( "Sui") || wallet.name.includes( "Suiet") || wallet.name.includes("Slush")
  ); // Örnek: Sui Wallet veya Suiet'i bulmaya çalış

  if (connectedWallet) {
    console.log("Desteklenen bir cüzdan bulundu:", connectedWallet.name);

    // LocalStorage'da kayıtlı cüzdan bilgisi varsa ve aynı cüzdan hala mevcutsa
    if (storedWalletInfo) {
      try {
        const walletInfo = JSON.parse(storedWalletInfo);
        console.log("LocalStorage'dan alınan cüzdan bilgisi:", walletInfo);

        // Cüzdan zaten bağlı mı kontrol et
        if (connectedWallet.accounts && connectedWallet.accounts.length > 0) {
          console.log("Cüzdan zaten bağlı, hesap bilgileri:", connectedWallet.accounts[0]);
          handleConnectionSuccess(connectedWallet.accounts[0]);

          // Cüzdan zaten bağlıysa, bakiyeyi otomatik olarak getir
          const accountAddress = connectedWallet.accounts[0].address;
          updateWalletButtonText(accountAddress);
        } else {
          // Cüzdan bulundu ama bağlı değil, bağlantı butonunu aktif et
          connectButton.disabled = false;
          // Bağlantı durumunu güncelle
          updateConnectionStatus(false);
        }
      } catch (error) {
        console.error("LocalStorage'dan cüzdan bilgisi okunamadı:", error);
        connectButton.disabled = false;
      }
    } else {
      // LocalStorage'da kayıtlı cüzdan bilgisi yoksa, bağlantı butonunu aktif et
      connectButton.disabled = false;
    }
  } else {
    console.log("Bağlantı durumu: Desteklenen bir cüzdan bulunamadı.");
    connectButton.disabled = true;
    // Bağlantı durumunu güncelle
    updateConnectionStatus(false);
  }

  // Cüzdan bağlantı olaylarını dinle
  getWallets().on("change", () => {
    // Cüzdan listesi değiştiğinde (yeni cüzdan yüklendiğinde vb.)
    const updatedWallets = getWallets().get();
    console.log("Cüzdan listesi güncellendi:", updatedWallets);
    connectedWallet = updatedWallets.find(
      (wallet) => wallet.name.includes( "Sui") || wallet.name.includes( "Suiet") || wallet.name.includes("Slush")
    );
    if (connectedWallet && !connectButton.disabled) {
      console.log("Bağlantı durumu: Bağlı Değil (Cüzdan bulundu)");
      connectButton.disabled = false;

      // Cüzdan zaten bağlı mı kontrol et
      if (connectedWallet.accounts && connectedWallet.accounts.length > 0) {
        console.log("Cüzdan zaten bağlı, hesap bilgileri:", connectedWallet.accounts[0]);
        handleConnectionSuccess(connectedWallet.accounts[0]);

        // Cüzdan zaten bağlıysa, bakiyeyi otomatik olarak getir
        const accountAddress = connectedWallet.accounts[0].address;
        updateWalletButtonText(accountAddress);
      }
    } else if (!connectedWallet) {
      console.log("Bağlantı durumu: Desteklenen bir cüzdan bulunamadı.");
      connectButton.disabled = true;
      handleDisconnection(); // Cüzdan kaybolursa bağlantıyı sıfırla
    }
  });
});

connectButton.addEventListener("click", async () => {
    if (!connectedWallet) {
        // Try to find a wallet again if it's null
        const wallets = getWallets().get();
        connectedWallet = wallets.find(
            (wallet) => wallet.name.includes("Sui") || wallet.name.includes("Suiet") || wallet.name.includes("Slush")
        );
    }

    if (!connectedWallet) {
        console.error("No wallet to connect to.");
        return;
    }

    try {
        console.log("Attempting to connect wallet...");
        const connectResult = await connectedWallet.features["standard:connect"].connect();
        console.log("Connect result:", connectResult);

        if (connectedWallet.accounts && connectedWallet.accounts.length > 0) {
            console.log("Connected accounts:", connectedWallet.accounts);
            handleConnectionSuccess(connectedWallet.accounts[0]);
            connectedWallet.features["standard:events"].on("disconnect", handleDisconnection);

            // Bağlantı başarılı olduğunda, cüzdan bilgilerini localStorage'a kaydet
            const walletInfo = {
                name: connectedWallet.name,
                version: connectedWallet.version,
                connected: true,
                accountAddress: connectedWallet.accounts[0].address
            };
            localStorage.setItem('connectedWalletInfo', JSON.stringify(walletInfo));

            // Cüzdan bakiyesini ve ayırma ikonunu güncelle
            const accountAddress = connectedWallet.accounts[0].address;
            await updateWalletButtonText(accountAddress);
        } else {
            console.error("No accounts found after connection");
        }
    } catch (error) {
        console.error("Wallet connection error:", error);
    }
});

async function mintNFT(formData) {
    if (!connectedWallet) {
        Swal.fire({
            title: 'Cüzdan Bağlantısı Gerekli',
            text: 'Reçete oluşturmak için önce cüzdanınızı bağlamalısınız.',
            icon: 'warning',
            confirmButtonText: 'Cüzdan Bağla',
            confirmButtonColor: '#6f42c1',
            showCancelButton: true,
            cancelButtonText: 'İptal',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                // Focus on the connect wallet button
                const connectButton = document.getElementById("connectWallet");
                if (connectButton) {
                    connectButton.focus();
                }
            }
        });
        return;
    }

    try {
        mintButton.disabled = true;

        // Get the current account
        const accounts = connectedWallet.accounts;
        console.log("Current accounts:", accounts);

        if (!accounts || accounts.length === 0) {
            Swal.fire({
                title: 'Cüzdan Bağlantısı Gerekli',
                text: 'Reçete oluşturmak için önce cüzdanınızı bağlamalısınız.',
                icon: 'warning',
                confirmButtonText: 'Cüzdan Bağla',
                confirmButtonColor: '#6f42c1',
                showCancelButton: true,
                cancelButtonText: 'İptal',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Focus on the connect wallet button
                    const connectButton = document.getElementById("connectWallet");
                    if (connectButton) {
                        connectButton.focus();
                    }
                }
            });
            return;
        }

        const currentAccount = accounts[0];
        console.log("Account details:", {
            address: currentAccount.address,
            chains: currentAccount.chains,
            features: currentAccount.features,
            label: currentAccount.label
        });

        // Log available features
        console.log("Available wallet features:", Object.keys(connectedWallet.features));

        const name = formData.basicInfo.name;
        const description = formData.basicInfo.description;
        const recipe = JSON.stringify(formData.phases);
        const price = BigInt(formData.basicInfo.price);

        // Get the minting fee from the treasury (you might want to make this dynamic)
        const MINTING_FEE = 100000; // 1 SUI = 1_000_000_000 MIST, this seems low for 1 SUI. Assuming 0.0001 SUI
                                      // If it should be 1 SUI, use BigInt(1_000_000_000)

        console.log("Minting with data:", {
            name,
            description,
            recipe,
            price: price.toString(),
            treasuryId: TREASURY_ID,
            mintingFee: MINTING_FEE
        });

        const tx = new TransactionBlock();

        // Split the payment coin from the sender's balance
        const [coin] = tx.splitCoins(tx.gas, [tx.pure(MINTING_FEE)]); // Ensure MINTING_FEE is passed correctly

        tx.moveCall({
            target: `${PACKAGE_ID}::daogrow::mint_with_fee`,
            arguments: [
                tx.pure(name),
                tx.pure(description),
                tx.pure(recipe),
                tx.pure(price),
                tx.object(TREASURY_ID),
                coin, // Pass the payment coin
            ],
        });

        console.log("Executing transaction...");

        // Try different feature names for transaction signing
        const signAndExecuteFeature =
            connectedWallet.features["sui:signAndExecuteTransactionBlock"] ||
            connectedWallet.features["standard:signAndExecuteTransactionBlock"] ||
            connectedWallet.features["signAndExecuteTransactionBlock"];

        if (!signAndExecuteFeature) {
            throw new Error("No transaction signing feature found in wallet");
        }

        const result = await signAndExecuteFeature.signAndExecuteTransactionBlock({
            transactionBlock: tx,
            chain: CHAIN,
            account: currentAccount,
            options: {
                showEffects: true,
                showEvents: true,
                showInput: true,
                showObjectChanges: true,
            }
        });

        console.log("Mint transaction result:", result);
        // Add success message after minting
        Swal.fire({
            title: 'Reçete Oluşturuldu!',
            text: 'NFT Reçeteniz başarıyla oluşturuldu.',
            icon: 'success',
            confirmButtonText: 'Tamam',
            confirmButtonColor: '#4abe8a'
        });

    } catch (error) {
        console.error("Minting error:", error);
        // Add error message
        Swal.fire({
            title: 'Minting Hatası',
            text: 'Reçete oluşturulurken bir hata oluştu: ' + error.message,
            icon: 'error',
            confirmButtonText: 'Tamam',
            confirmButtonColor: '#dc3545'
        });
    } finally {
        mintButton.disabled = false;
    }
};

function handleConnectionSuccess(account) {
    console.log("Handling connection success with account:", account);

    // Get the account address using the proper method
    const accountAddress = account.address;
    console.log("Account address:", accountAddress);

    if (!accountAddress) {
        console.error("No account address found");
        return;
    }

    connectButton.disabled = true;

    // Only try to show mint section if it exists (on create-recipe page)
    const mintSection = document.getElementById("mint-section");
    if (mintSection) {
        mintSection.style.display = "block";
    }

    console.log("Connection success handled");

    // Update the wallet button text to show connection status and balance
    updateWalletButtonText(accountAddress);

    // Bağlantı durumunu güncelle
    updateConnectionStatus(true);
}

// Function to get and display wallet balance
async function updateWalletButtonText(accountAddress) {
    try {
        // Get the wallet balance
        const balance = await getWalletBalance(accountAddress);

        // Update the wallet button text
        const walletButtonText = document.getElementById("walletButtonText");
        if (walletButtonText) {
            // Show just the balance with a connected icon
            walletButtonText.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> Bakiye: ${balance} SUI`;

            // Add a class to style the connected wallet button
            const connectButton = document.getElementById("connectWallet");
            if (connectButton) {
                connectButton.classList.add("wallet-connected");
            }

            // Create or update the disconnect button outside the connect button
            let disconnectButton = document.getElementById("disconnectWallet");
            if (!disconnectButton) {
                disconnectButton = document.createElement("button");
                disconnectButton.id = "disconnectWallet";
                disconnectButton.className = "btn btn-sm btn-danger ms-2";
                disconnectButton.title = "Cüzdanı Ayır";
                disconnectButton.innerHTML = '<i class="bi bi-plug-fill"></i>';

                // Add event listener to the disconnect button
                disconnectButton.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent the click from bubbling up
                    handleDisconnection();
                });

                // Insert the disconnect button after the connect button
                connectButton.parentNode.insertBefore(disconnectButton, connectButton.nextSibling);
            } else {
                // If the button already exists, make sure it's visible
                disconnectButton.style.display = "inline-block";
            }
        }
    } catch (error) {
        console.error("Error getting wallet balance:", error);

        // Still show connection status even if balance fetch fails
        const walletButtonText = document.getElementById("walletButtonText");
        if (walletButtonText) {
            walletButtonText.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> Bağlı`;

            // Create or update the disconnect button outside the connect button
            let disconnectButton = document.getElementById("disconnectWallet");
            if (!disconnectButton) {
                disconnectButton = document.createElement("button");
                disconnectButton.id = "disconnectWallet";
                disconnectButton.className = "btn btn-sm btn-danger ms-2";
                disconnectButton.title = "Cüzdanı Ayır";
                disconnectButton.innerHTML = '<i class="bi bi-plug-fill"></i>';

                // Add event listener to the disconnect button
                disconnectButton.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent the click from bubbling up
                    handleDisconnection();
                });

                // Insert the disconnect button after the connect button
                const connectButton = document.getElementById("connectWallet");
                if (connectButton) {
                    connectButton.parentNode.insertBefore(disconnectButton, connectButton.nextSibling);
                }
            } else {
                // If the button already exists, make sure it's visible
                disconnectButton.style.display = "inline-block";
            }
        }
    }
}

// Function to get wallet balance
async function getWalletBalance(accountAddress) {
    try {
        // Use the Sui RPC to get the balance
        const response = await fetch('https://fullnode.testnet.sui.io/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                jsonrpc: '2.0',
                id: 1,
                method: 'suix_getBalance',
                params: [accountAddress, '0x2::sui::SUI']
            }),
        });

        const data = await response.json();

        if (data.result && data.result.totalBalance) {
            // Convert from MIST to SUI (1 SUI = 1_000_000_000 MIST)
            const balanceInSui = parseFloat(data.result.totalBalance) / 1_000_000_000;
            // Update localStorage with the balance info as well (optional)
             const storedWalletInfo = localStorage.getItem('connectedWalletInfo');
             if (storedWalletInfo) {
                 try {
                     const walletInfo = JSON.parse(storedWalletInfo);
                     walletInfo.balance = balanceInSui.toFixed(4);
                     localStorage.setItem('connectedWalletInfo', JSON.stringify(walletInfo));
                 } catch (error) {
                     console.error("Could not update balance in localStorage:", error);
                 }
             }
            return balanceInSui.toFixed(4);
        } else {
            console.error("Error parsing balance response:", data);
            return "0.0000";
        }
    } catch (error) {
        console.error("Error fetching wallet balance:", error);
        return "0.0000";
    }
}

function handleDisconnection() {
  connectedWallet = null; // Bağlantı kesildiğinde cüzdan referansını temizle
  console.log("Cüzdan bağlantısı kesildi.");

  // Reset the wallet button text
  const walletButtonText = document.getElementById("walletButtonText");
  if (walletButtonText) {
    walletButtonText.innerHTML = `<i class="bi bi-wallet2 me-1"></i> Cüzdan Bağla`;
  }

  // Remove the connected wallet styling
  const connectButton = document.getElementById("connectWallet");
  if (connectButton) {
    connectButton.classList.remove("wallet-connected");
    connectButton.disabled = false;

    // Ensure only one icon is present and text is correct
    const icon = connectButton.querySelector(".bi-wallet2");
    if (!icon) { // If the icon is missing, reset the button's innerHTML
      connectButton.innerHTML = `<i class="bi bi-wallet2 me-1"></i> Cüzdan Bağla`;
    } else { // If icon exists, ensure the text is correct
        const buttonText = connectButton.textContent.trim();
        if (buttonText !== "Cüzdan Bağla") {
             connectButton.innerHTML = `<i class="bi bi-wallet2 me-1"></i> Cüzdan Bağla`;
        }
    }
  }

  // Hide the disconnect button
  const disconnectButton = document.getElementById("disconnectWallet");
  if (disconnectButton) {
    disconnectButton.style.display = "none";
  }

  // Bağlantı durumunu güncelle
  updateConnectionStatus(false);
}

// Bağlantı durumunu güncelleme fonksiyonu
function updateConnectionStatus(isConnected) {
    if (isConnected) {
        // Bağlantı durumunu localStorage'a kaydet
        if (connectedWallet && connectedWallet.accounts && connectedWallet.accounts.length > 0) {
            const walletInfo = {
                name: connectedWallet.name,
                version: connectedWallet.version,
                connected: true,
                accountAddress: connectedWallet.accounts[0].address
                // Balance is saved in getWalletBalance now
            };
            localStorage.setItem('connectedWalletInfo', JSON.stringify(walletInfo));
        }
    } else {
        // Bağlantı kesildiğinde localStorage'dan bilgiyi sil
        localStorage.removeItem('connectedWalletInfo');
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // Form submission with validation for create-recipe page
    const createRecipeForm = document.getElementById("createRecipeForm");
    if (createRecipeForm) {
        createRecipeForm.addEventListener("submit", async function(e) {
            e.preventDefault(); // Prevent default form submission

            let valid = true;
            let firstInvalid = null;

            // Clear previous validation states
            $('.is-invalid').removeClass('is-invalid');

            // --- Basic Info Validation ---
            const recipeNameInput = document.getElementById('recipeName');
            const recipePriceInput = document.getElementById('recipePrice');
            const recipeDescriptionInput = document.getElementById('recipeDescription');

            if (!recipeNameInput.value.trim()) {
                valid = false;
                recipeNameInput.classList.add('is-invalid');
                if (!firstInvalid) firstInvalid = recipeNameInput;
            }
            const priceValue = parseFloat(recipePriceInput.value);
            if (isNaN(priceValue) || priceValue < 0) {
                 valid = false;
                 recipePriceInput.classList.add('is-invalid');
                 if (!firstInvalid) firstInvalid = recipePriceInput;
            }
            if (!recipeDescriptionInput.value.trim()) {
                valid = false;
                recipeDescriptionInput.classList.add('is-invalid');
                if (!firstInvalid) firstInvalid = recipeDescriptionInput;
            }

            // --- Phase Validation ---
            const stepCards = document.querySelectorAll("#stepsContainer .step-card");
            if (stepCards.length === 0) {
                valid = false;
                // Optionally, highlight the "Add Step" button or show a message
                alert("En az bir adım eklemelisiniz.");
                if (!firstInvalid) {
                     const addStepButton = document.getElementById("addStep");
                     if (addStepButton) firstInvalid = addStepButton;
                }
            }

            stepCards.forEach((stepCard) => {
                // Validate Range Inputs (Min/Max pairs)
                stepCard.querySelectorAll('.d-flex.align-items-center.gap-2').forEach(function(pairContainer) {
                    const inputs = pairContainer.querySelectorAll('input[type="number"]');
                    if (inputs.length === 2) { // Ensure it's a min/max pair
                        const minInput = inputs[0];
                        const maxInput = inputs[1];
                        const minVal = parseFloat(minInput.value);
                        const maxVal = parseFloat(maxInput.value);

                        if (isNaN(minVal) || isNaN(maxVal) || minVal < 0 || maxVal < 0 || minVal > maxVal) {
                            valid = false;
                            minInput.classList.add("is-invalid");
                            maxInput.classList.add("is-invalid");
                            if (!firstInvalid) firstInvalid = minInput;
                        }
                    }
                });

                // Validate Growth Duration (single number input)
                const growthDurationInput = stepCard.querySelector('input[id^="growthDuration"]');
                if (growthDurationInput) {
                     const durationVal = parseFloat(growthDurationInput.value);
                     if (isNaN(durationVal) || durationVal <= 0) { // Duration should be positive
                         valid = false;
                         growthDurationInput.classList.add("is-invalid");
                         if (!firstInvalid) firstInvalid = growthDurationInput;
                     }
                }
            });

            if (!valid) {
                if (firstInvalid) {
                    firstInvalid.focus();
                    // Scroll to the first invalid element if needed
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                // Use SweetAlert for a nicer message
                 Swal.fire({
                    title: 'Form Hatası',
                    text: "Lütfen tüm alanları doğru bir şekilde doldurun. 'Min' değerleri 'Maks' değerlerinden büyük olamaz ve tüm sayısal değerler pozitif olmalıdır.",
                    icon: 'error',
                    confirmButtonText: 'Tamam',
                    confirmButtonColor: '#dc3545'
                });
                return; // Stop submission
            }

            // Collect form data if valid
            const formData = {
                basicInfo: {
                    name: recipeNameInput.value.trim(),
                    price: recipePriceInput.value, // Keep as string initially, convert to BigInt later
                    description: recipeDescriptionInput.value.trim()
                },
                phases: []
            };

            // Collect data from each step
            stepCards.forEach((stepCard, index) => {
                const phaseNumber = index + 1;
                const phaseData = {
                    phaseNumber: phaseNumber,
                    parameters: {}
                };

                // Helper function to get min/max values
                const getMinMax = (paramClassPrefix) => {
                    const minInput = stepCard.querySelector(`.${paramClassPrefix}-min`);
                    const maxInput = stepCard.querySelector(`.${paramClassPrefix}-max`);
                    return {
                        min: minInput ? parseFloat(minInput.value) : NaN,
                        max: maxInput ? parseFloat(maxInput.value) : NaN
                    };
                };

                phaseData.parameters.temperature = getMinMax('temp');
                phaseData.parameters.humidity = getMinMax('nem');
                phaseData.parameters.co2 = getMinMax('co2');
                phaseData.parameters.ph = getMinMax('ph');
                phaseData.parameters.ec = getMinMax('ec');
                phaseData.parameters.lightDuration = getMinMax('light');

                // Get growth duration data
                const growthDurationInput = stepCard.querySelector('input[id^="growthDuration"]');
                phaseData.parameters.growthDuration = growthDurationInput ? parseFloat(growthDurationInput.value) : NaN;

                formData.phases.push(phaseData);
            });

            console.log("Form Data Collected:", formData);

            // Call the mintNFT function
            await mintNFT(formData);

        }); // End of form submit listener
    } // End of if(createRecipeForm)
}); // End of DOMContentLoaded


// Function to fetch all NFTs from the contract (Placeholder - needs actual implementation)
async function listNFTs() {
    console.log("Attempting to fetch NFTs from contract...");
    try {
        // Create a SuiClient instance
        const client = new SuiClient({ url: 'https://fullnode.testnet.sui.io/' });
        
        // Create a transaction block to call the view function
        const tx = new TransactionBlock();
        tx.moveCall({
            target: `${PACKAGE_ID}::daogrow::get_all_nfts`,
            arguments: [], // No arguments needed for getting all NFTs
        });
        
        // Use devInspectTransactionBlock to call the view function
        const result = await client.devInspectTransactionBlock({
            sender: '0x0000000000000000000000000000000000000000000000000000000000000000', // Dummy sender for view calls
            transactionBlock: tx,
        });

        if (!result.results || result.error) {
            console.error("Error calling view function:", result.error || "No results");
            return [];
        }

        // Parse the results from the view function
        const rawReturnData = result.results?.[0]?.returnValues?.[0]?.[0];
        if (!rawReturnData) {
            console.log("View function returned no data or unexpected format.");
            return [];
        }

        // Process the NFT data
        const parsedNfts = rawReturnData.map(nftRawData => {
            return {
                id: nftRawData.id,
                title: nftRawData.name,
                creator: nftRawData.creator_address,
                price: parseFloat(nftRawData.price) / 1_000_000_000, // Convert MIST to SUI
                category: nftRawData.category || "default",
                views: nftRawData.views || 0,
                likes: nftRawData.likes || 0,
                image: nftRawData.image || `https://placehold.co/400x300/4abe8a/ffffff?text=${encodeURIComponent(nftRawData.name || "NFT")}`,
                nftId: nftRawData.id,
                description: nftRawData.description,
                recipe: nftRawData.recipe
            };
        });
        
        console.log("Processed NFT data from view function:", parsedNfts);
        return parsedNfts;
    } catch (error) {
        console.error("Error fetching NFTs from blockchain:", error);
        // Return empty array on error to prevent breaking the UI
        return [];
    }
}


// Function to buy an NFT from the marketplace
async function buyNFT(nftObjectId, nftPrice) { // Pass price for confirmation and payment
    if (!connectedWallet) {
        Swal.fire({
            title: 'Cüzdan Bağlantısı Gerekli',
            text: 'NFT satın almak için önce cüzdanınızı bağlamalısınız.',
            icon: 'warning',
            confirmButtonText: 'Cüzdan Bağla',
            confirmButtonColor: '#6f42c1',
            showCancelButton: true,
            cancelButtonText: 'İptal',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                const connectButton = document.getElementById("connectWallet");
                if (connectButton) {
                    connectButton.focus();
                    // Optionally scroll to the button
                    connectButton.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        return;
    }

    const accounts = connectedWallet.accounts;
    if (!accounts || accounts.length === 0) {
         Swal.fire({
            title: 'Cüzdan Bağlantısı Gerekli',
            text: 'NFT satın almak için önce cüzdanınızı bağlamalısınız.',
            icon: 'warning',
            confirmButtonText: 'Cüzdan Bağla',
            confirmButtonColor: '#6f42c1'
            // No cancel button needed here as it's a prerequisite
        });
        return;
    }
    const currentAccount = accounts[0];

    // Convert price from SUI string/number to MIST BigInt
    const priceInMist = BigInt(Math.round(parseFloat(nftPrice) * 1_000_000_000));

    // Confirmation Dialog
    const confirmation = await Swal.fire({
        title: 'Satın Almayı Onayla',
        text: `Bu NFT'yi ${nftPrice} SUI karşılığında satın almak istediğinizden emin misiniz?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Evet, Satın Al',
        cancelButtonText: 'İptal',
        confirmButtonColor: '#4abe8a',
        cancelButtonColor: '#6c757d'
    });

    if (!confirmation.isConfirmed) {
        return; // User cancelled
    }


    try {
        console.log("Account details:", {
            address: currentAccount.address,
            chains: currentAccount.chains,
            features: currentAccount.features,
            label: currentAccount.label
        });

        // Show loading indicator
        Swal.fire({
            title: 'İşlem Yapılıyor',
            text: 'NFT satın alma işlemi başlatılıyor...',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Create a transaction block to buy the NFT
        const tx = new TransactionBlock();

         // Split the exact coin amount needed for the purchase
         // tx.gas is the default SUI payment object for gas.
         // We need to provide the coin object for the NFT price.
         const [paymentCoin] = tx.splitCoins(tx.gas, [tx.pure(priceInMist)]);

        // Call the buy function in your contract
        // Ensure the arguments match your contract's function signature
        tx.moveCall({
            target: `${PACKAGE_ID}::daogrow::buy_nft`, // Adjust module/function name if needed
            arguments: [
                tx.object(TREASURY_ID), // Assuming treasury object is needed
                tx.object(nftObjectId), // The NFT Object ID
                paymentCoin          // The coin object for payment
            ],
            // typeArguments: [...] // Add if your function uses generics
        });

        console.log("Executing buy transaction for NFT:", nftObjectId, "with price:", priceInMist.toString());

        // Try different feature names for transaction signing
        const signAndExecuteFeature =
            connectedWallet.features["sui:signAndExecuteTransactionBlock"] ||
            connectedWallet.features["standard:signAndExecuteTransactionBlock"] ||
            connectedWallet.features["signAndExecuteTransactionBlock"];

        if (!signAndExecuteFeature) {
            throw new Error("No transaction signing feature found in wallet");
        }

        const result = await signAndExecuteFeature.signAndExecuteTransactionBlock({
            transactionBlock: tx,
            chain: CHAIN,
            account: currentAccount,
            options: { // Request effects to confirm success
                showEffects: true,
                showEvents: true,
                // showInput: true,
                // showObjectChanges: true,
            }
        });

        console.log("Buy transaction result:", result);

        // Check for successful execution status in effects
        if (result.effects?.status?.status !== 'success') {
             throw new Error(`Transaction failed: ${result.effects?.status?.error || 'Unknown error'}`);
        }


        // Show success message
        Swal.fire({
            title: 'Satın Alma Başarılı',
            text: 'NFT başarıyla satın alındı!',
            icon: 'success',
            confirmButtonText: 'Tamam',
            confirmButtonColor: '#4abe8a'
        }).then(() => {
            // Refresh the marketplace to show updated ownership/availability
             // Optional: Re-fetch NFTs instead of full reload for smoother UX
             console.log("Refreshing marketplace...");
             initializeMarketplace(); // Re-initialize to fetch and display updated data
            // location.reload(); // Alternative: Full page reload
        });
    } catch (error) {
        console.error("Buying error:", error);

        // Show error message
        Swal.fire({
            title: 'Satın Alma Hatası',
            text: 'NFT satın alırken bir hata oluştu: ' + error.message,
            icon: 'error',
            confirmButtonText: 'Tamam',
            confirmButtonColor: '#dc3545'
        });
    }
}


// Initialize the marketplace functionality
async function initializeMarketplace() {
  console.log("Initializing marketplace...");
  const nftGrid = $('.row.g-4.nft-grid'); // Target the specific grid
  const paginationContainer = $('#pagination-container');
  const categoryFilter = $('#category-filter');
  const sortSelect = $('#sort-select');
  const searchFilter = $('#search-filter');
  const filterButton = $('#filter-button');
  const itemsPerPage = 8; // Items per page

  let allNftData = []; // Store all fetched NFTs
  let filteredData = []; // Store currently filtered/sorted NFTs
  let currentPage = 1;

  // Show loading indicator
  nftGrid.html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Yükleniyor...</span></div><p class="mt-3">NFT\'ler yükleniyor...</p></div>');
  paginationContainer.empty(); // Clear previous pagination

  try {
    allNftData = await listNFTs(); // Fetch NFTs using the blockchain function
    console.log("Fetched NFTs for marketplace:", allNftData);

    if (!Array.isArray(allNftData)) {
        console.error("listNFTs did not return an array:", allNftData);
        allNftData = []; // Ensure it's an array
    }

    filteredData = [...allNftData]; // Start with all data

    // --- Event Listeners for Filtering and Sorting ---
    categoryFilter.off('change').on('change', function() {
        applyFiltersAndSort();
        renderPage(1); // Go back to page 1 after filtering
    });

    sortSelect.off('change').on('change', function() {
        applyFiltersAndSort();
        renderPage(1); // Go back to page 1 after sorting
    });
    
    // Add search functionality
    searchFilter.off('input').on('input', function() {
        applyFiltersAndSort();
        renderPage(1); // Go back to page 1 after searching
    });
    
    // Add filter button functionality
    filterButton.off('click').on('click', function() {
        applyFiltersAndSort();
        renderPage(1); // Go back to page 1 after filtering
    });

    // --- Initial Render ---
     if (filteredData.length === 0) {
      nftGrid.html('<div class="col-12 text-center py-5"><i class="bi bi-emoji-frown fs-1 text-muted"></i><p class="mt-3 lead">Pazaryerinde henüz NFT bulunmuyor.</p><a href="create-recipe.php" class="btn btn-primary">İlk Reçeteni Oluştur</a></div>');
      paginationContainer.empty();
    } else {
        applyFiltersAndSort(); // Apply default sort/filter if any
        renderPage(currentPage); // Render the first page initially
    }

  } catch (error) {
      console.error("Error initializing marketplace:", error);
      nftGrid.html('<div class="col-12 text-center py-5"><i class="bi bi-exclamation-triangle-fill fs-1 text-danger"></i><p class="mt-3 lead text-danger">NFT\'ler yüklenirken bir hata oluştu.</p><p>Lütfen daha sonra tekrar deneyin.</p></div>');
      paginationContainer.empty();
  }

  function applyFiltersAndSort() {
      const selectedCategory = categoryFilter.val();
      const selectedSort = sortSelect.val();
      const searchTerm = searchFilter.val().toLowerCase();

      // Apply Category Filter
      if (selectedCategory === 'all') {
          filteredData = [...allNftData];
      } else {
          filteredData = allNftData.filter(item => item.category === selectedCategory);
      }
      
      // Apply Search Filter
      if (searchTerm) {
          filteredData = filteredData.filter(item => 
              item.title.toLowerCase().includes(searchTerm) || 
              item.description.toLowerCase().includes(searchTerm) ||
              item.creator.toLowerCase().includes(searchTerm)
          );
      }

      // Apply Sorting
      switch (selectedSort) {
          case 'price-asc':
              filteredData.sort((a, b) => a.price - b.price);
              break;
          case 'price-desc':
              filteredData.sort((a, b) => b.price - a.price);
              break;
          case 'name-asc': // Sort by title (name) A-Z
              filteredData.sort((a, b) => a.title.localeCompare(b.title));
              break;
          case 'name-desc': // Sort by title (name) Z-A
               filteredData.sort((a, b) => b.title.localeCompare(a.title));
              break;
          // Add more cases if needed (e.g., date created - requires blockchain data)
          case 'popular': // Example: sort by views (descending)
          default:
              // Default sort (e.g., by views or keep fetched order)
              filteredData.sort((a, b) => (b.views || 0) - (a.views || 0)); // Fallback to 0 if views undefined
              break;
      }
      console.log("Filtered and sorted data:", filteredData);
  }


  function renderPage(page) {
      currentPage = page;
      nftGrid.empty(); // Clear previous items

      const totalItems = filteredData.length;
      const totalPages = Math.ceil(totalItems / itemsPerPage);

      if (totalItems === 0) {
          // Show message based on whether filtering is active
          const selectedCategory = categoryFilter.val();
          if (selectedCategory !== 'all') {
               nftGrid.html(`<div class="col-12 text-center py-5"><i class="bi bi-search fs-1 text-muted"></i><p class="mt-3 lead">'${selectedCategory}' kategorisinde eşleşen NFT bulunamadı.</p></div>`);
          } else {
              nftGrid.html('<div class="col-12 text-center py-5"><i class="bi bi-emoji-frown fs-1 text-muted"></i><p class="mt-3 lead">Pazaryerinde henüz NFT bulunmuyor.</p><a href="create-recipe.php" class="btn btn-primary">İlk Reçeteni Oluştur</a></div>');
          }
          renderPagination(totalPages); // Still render pagination (likely disabled)
          return;
      }


      const start = (currentPage - 1) * itemsPerPage;
      const end = start + itemsPerPage;
      const itemsToDisplay = filteredData.slice(start, end);

      itemsToDisplay.forEach(item => {
           // Use default image if item.image is missing or invalid
          const imageUrl = item.image && item.image.startsWith('http') ? item.image : `https://placehold.co/400x300/cccccc/ffffff?text=Reçete`;
          const nftCard = `
              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                  <div class="card nft-card h-100 shadow-sm">
                      <img src="${imageUrl}" class="card-img-top nft-image" alt="${item.title}">
                      <div class="card-body d-flex flex-column">
                          <h5 class="card-title text-truncate" title="${item.title}">${item.title}</h5>
                          <p class="card-text text-muted mb-2 text-truncate" title="Oluşturan: ${item.creator}">
                            <small>Oluşturan: ${item.creator.substring(0, 6)}...${item.creator.substring(item.creator.length - 4)}</small>
                          </p>
                           <p class="card-text text-truncate mb-3" title="${item.description}">
                              ${item.description || 'Açıklama yok.'}
                          </p>
                          <div class="mt-auto">
                              <div class="d-flex justify-content-between align-items-center mb-2">
                                  <span class="fw-bold text-primary fs-5">${item.price.toFixed(2)} SUI</span>
                                  <span class="text-muted"><i class="bi bi-eye-fill me-1"></i>${item.views}</span>
                              </div>
                              <button class="btn btn-success w-100 btn-buy-nft" data-nft-id="${item.nftId}" data-nft-price="${item.price}">
                                <i class="bi bi-cart-plus-fill me-1"></i> Satın Al
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          `;
          nftGrid.append(nftCard);
      });

      // Add event listeners to the new "Buy" buttons
      $('.btn-buy-nft').off('click').on('click', function() {
          const nftId = $(this).data('nft-id');
          const nftPrice = $(this).data('nft-price');
          console.log(`Buy button clicked for NFT ID: ${nftId}, Price: ${nftPrice}`);
          buyNFT(nftId, nftPrice); // Call the buy function
      });


      renderPagination(totalPages);
  }

  function renderPagination(totalPages) {
      paginationContainer.empty();
      if (totalPages <= 1) return; // No pagination needed for 0 or 1 page

      const ul = $('<ul class="pagination justify-content-center"></ul>');

      // Previous Button
      ul.append(`
          <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
              <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
              </a>
          </li>
      `);

      // Page Number Buttons (simplified for example)
      // You might want a more complex logic for many pages (e.g., showing first/last and ellipsis)
      const maxPagesToShow = 5;
      let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
      let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

      // Adjust startPage if endPage reaches the limit first
       startPage = Math.max(1, endPage - maxPagesToShow + 1);


      if (startPage > 1) {
          ul.append(`<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`);
          if (startPage > 2) {
             ul.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
          }
      }


      for (let i = startPage; i <= endPage; i++) {
          ul.append(`
              <li class="page-item ${i === currentPage ? 'active' : ''}">
                  <a class="page-link" href="#" data-page="${i}">${i}</a>
              </li>
          `);
      }

       if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                ul.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            }
            ul.append(`<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`);
        }

      // Next Button
      ul.append(`
          <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
              <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
              </a>
          </li>
      `);

      paginationContainer.append(ul);

      // Add click listener for pagination links
      paginationContainer.find('.page-link').on('click', function(e) {
          e.preventDefault();
          const page = $(this).data('page');
          if (page && page !== currentPage) {
              renderPage(page);
               // Scroll to the top of the grid after changing page
                $('html, body').animate({
                    scrollTop: nftGrid.offset().top - 80 // Adjust offset as needed (e.g., for fixed navbar)
                }, 300);
          }
      });
  }
} // End of initializeMarketplace