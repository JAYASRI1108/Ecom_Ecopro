document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".quantity-input").forEach((input) => {
    input.addEventListener("change", () => {
      if (Number(input.value) < 0) {
        input.value = "0";
      }
    });
  });
});
