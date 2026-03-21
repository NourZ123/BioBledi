let cart = JSON.parse(localStorage.getItem("biobladi_cart")) || [];
const FRAIS_LIVRAISON = 3;
const container = document.getElementById("cart-items-container");
const subtotalLeft = document.getElementById("cart-subtotal-left");
const subtotalRight = document.getElementById("cart-subtotal-right");
const finalTotal = document.getElementById("cart-final-total");
const headerCount = document.getElementById("header-article-count");

function renderCart() {
  container.innerHTML = "";
  let subtotal = 0;
  let totalItems = 0;

  if (cart.length === 0) {
    container.innerHTML =
      '<p style="text-align:center; padding: 20px;">Votre panier est vide.</p>';
    updateTotals(0, 0);
    return;
  }

  cart.forEach((item, index) => {
    const itemTotal = item.price * item.quantity;
    subtotal += itemTotal;
    totalItems += item.quantity;

    const productHTML = `
          <div class="row2">
            <div class="produits">
              <img src="${item.image}" alt="${item.name}" class="img_panier" />
              <div class="info">
                <p>${item.name}</p>
                <p class="price">${item.price.toFixed(2)} DT</p>
              </div>
              <div class="quantité">
                <button onclick="changeQuantity(${index}, -1)">-</button>
                <span class="Q">${item.quantity}</span>
                <button onclick="changeQuantity(${index}, 1)">+</button>
              </div>
            </div>
            <div class="Total">
              <div class="price">${itemTotal.toFixed(2)} DT</div>
              <div style="margin-right: 20px; cursor: pointer;" onclick="removeItem(${index})">
                <img src="image/bin-svgrepo-com.svg" alt="bin" />
                <span class="Retirer">Retirer</span>
              </div>
            </div>
          </div>
        `;
    container.insertAdjacentHTML("beforeend", productHTML);
  });

  updateTotals(subtotal, totalItems);
}

function updateTotals(subtotal, totalItems) {
  subtotalLeft.innerText = `${subtotal.toFixed(2)} DT`;
  subtotalRight.innerText = `${subtotal.toFixed(2)} DT`;

  const totalToPay = subtotal > 0 ? subtotal + FRAIS_LIVRAISON : 0;
  finalTotal.innerText = `${totalToPay.toFixed(2)} DT`;

  if (headerCount) {
    headerCount.innerHTML = `<strong> ${totalItems} article(s)</strong>`;
  }
}

function changeQuantity(index, change) {
  if (cart[index].quantity + change > 0) {
    cart[index].quantity += change;
    saveAndRender();
  }
}

function removeItem(index) {
  cart.splice(index, 1);
  saveAndRender();
}

function saveAndRender() {
  localStorage.setItem("biobladi_cart", JSON.stringify(cart));
  renderCart();
}
renderCart();
