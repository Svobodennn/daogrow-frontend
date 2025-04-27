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
            confirmButtonText: 'Tamam',
            confirmButtonColor: '#6f42c1',
            showCancelButton: false,
        }).then((result) => {
            if (result.isConfirmed) {
                // Focus on the connect wallet button
                const connectButton = document.getElementById("connectWallet");
                
                if (connectButton) {
                  
                       // Force scroll to top using multiple methods
                       // Method 1: Direct window scroll
                       window.scrollTo(0, 0);
                       
                       // Method 2: Scroll the document body
                       document.body.scrollTop = 0;
                       
                       // Method 3: Scroll the document element
                       document.documentElement.scrollTop = 0;
                       
                       // Method 4: Use jQuery if available
                       if (typeof $ !== 'undefined') {
                           $('html, body').animate({ scrollTop: 0 }, 0);
                       }
                       
                       // Method 5: Force scroll with a more aggressive approach
                       setTimeout(() => {
                           window.scrollTo(0, 0);
                           document.body.scrollTop = 0;
                           document.documentElement.scrollTop = 0;
                           
                           // Then click the button after ensuring scroll
                           connectButton.click();
                       }, 200);
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
                confirmButtonText: 'Tamam',
                confirmButtonColor: '#6f42c1',
                showCancelButton: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Focus on the connect wallet button
                    const connectButton = document.getElementById("connectWallet");
                    if (connectButton) {
                      
                       // Force scroll to top using multiple methods
                       // Method 1: Direct window scroll
                       window.scrollTo(0, 0);
                       
                       // Method 2: Scroll the document body
                       document.body.scrollTop = 0;
                       
                       // Method 3: Scroll the document element
                       document.documentElement.scrollTop = 0;
                       
                       // Method 4: Use jQuery if available
                       if (typeof $ !== 'undefined') {
                           $('html, body').animate({ scrollTop: 0 }, 0);
                       }
                       
                       // Method 5: Force scroll with a more aggressive approach
                       setTimeout(() => {
                           window.scrollTo(0, 0);
                           document.body.scrollTop = 0;
                           document.documentElement.scrollTop = 0;
                           
                           // Then click the button after ensuring scroll
                           connectButton.click();
                       }, 200);
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
            const recipeNameInput = document.getElementById('name');
            const recipePriceInput = document.getElementById('price');
            const recipeDescriptionInput = document.getElementById('description');
            
            console.log("Basic info inputs:", {
                name: recipeNameInput,
                price: recipePriceInput,
                description: recipeDescriptionInput
            });

            if (!recipeNameInput || !recipeNameInput.value.trim()) {
                console.log("Name validation failed");
                valid = false;
                if (recipeNameInput) recipeNameInput.classList.add('is-invalid');
                if (!firstInvalid) firstInvalid = recipeNameInput;
            }
            
            if (!recipePriceInput) {
                console.log("Price input not found");
                valid = false;
                if (!firstInvalid) firstInvalid = document.querySelector('label[for="price"]');
            } else {
                const priceValue = parseFloat(recipePriceInput.value);
                console.log("Price value:", priceValue);
                
                if (isNaN(priceValue) || priceValue < 0) {
                    console.log("Price validation failed");
                    valid = false;
                    recipePriceInput.classList.add('is-invalid');
                    if (!firstInvalid) firstInvalid = recipePriceInput;
                }
            }
            
            if (!recipeDescriptionInput || !recipeDescriptionInput.value.trim()) {
                console.log("Description validation failed");
                valid = false;
                if (recipeDescriptionInput) recipeDescriptionInput.classList.add('is-invalid');
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
                    // Check for range inputs instead of number inputs
                    const rangeInputs = pairContainer.querySelectorAll('input[type="range"]');
                    console.log("Found range inputs:", rangeInputs.length);
                    
                    if (rangeInputs.length === 2) { // Ensure it's a min/max pair
                        const minInput = rangeInputs[0];
                        const maxInput = rangeInputs[1];
                        const minVal = parseFloat(minInput.value);
                        const maxVal = parseFloat(maxInput.value);
                        
                        console.log("Min value:", minVal, "Max value:", maxVal);
                        
                        if (isNaN(minVal) || isNaN(maxVal) || minVal < 0 || maxVal < 0 || minVal > maxVal) {
                            console.log("Validation failed for range inputs");
                            valid = false;
                            minInput.classList.add("is-invalid");
                            maxInput.classList.add("is-invalid");
                            if (!firstInvalid) firstInvalid = minInput;
                        }
                    }
                });

                // Validate Growth Duration (single number input)
                const growthDurationInput = stepCard.querySelector('input[id^="daysInput-step-"]');
                console.log("Growth duration input:", growthDurationInput);
                
                if (growthDurationInput) {
                     const durationVal = parseFloat(growthDurationInput.value);
                     console.log("Duration value:", durationVal);
                     
                     if (isNaN(durationVal) || durationVal <= 0) { // Duration should be positive
                         console.log("Validation failed for growth duration");
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
                    name: recipeNameInput ? recipeNameInput.value.trim() : '',
                    price: recipePriceInput ? recipePriceInput.value : '0', // Keep as string initially, convert to BigInt later
                    description: recipeDescriptionInput ? recipeDescriptionInput.value.trim() : ''
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
                const growthDurationInput = stepCard.querySelector('input[id^="daysInput-step-"]');
                phaseData.parameters.growthDuration = growthDurationInput ? parseFloat(growthDurationInput.value) : NaN;

                formData.phases.push(phaseData);
            });

            console.log("Form Data Collected:", formData);

            // Call the mintNFT function
            await mintNFT(formData);

        }); // End of form submit listener
    } // End of if(createRecipeForm)
}); // End of DOMContentLoaded

