document.addEventListener("DOMContentLoaded", () => {
  let cart = JSON.parse(localStorage.getItem("biobladi_cart")) || [];
  const addButtons = document.querySelectorAll(".btn");
  addButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      const itemDiv = event.target.closest(".item");
      let productName = itemDiv.querySelectorAll(".Row1  strong")[0].innerText;
      productName = productName.replace("-", "");
      let productPriceText =
        itemDiv.querySelectorAll(".Row1  strong")[1].innerText;
      let productPrice = parseFloat(productPriceText.replace("dt", "").trim());
      let productImage = itemDiv.querySelector("img").src;
      const product = {
        name: productName,
        price: productPrice,
        image: productImage,
        quantity: 1,
      };
      const existingProductIndex = cart.findIndex(
        (item) => item.name === product.name
      );

      if (existingProductIndex !== -1) {
        cart[existingProductIndex].quantity += 1;
      } else {
        cart.push(product);
      }
      localStorage.setItem("biobladi_cart", JSON.stringify(cart));
      const originalText = button.innerHTML;
      button.innerHTML = "<b>✓ Ajouté</b>";
      button.style.backgroundColor = "#14532d";
      button.style.color = "white";
      setTimeout(() => {
        button.innerHTML = originalText;
        button.style.backgroundColor = "";
        button.style.color = "";
      }, 1500);
    });
  });
});
