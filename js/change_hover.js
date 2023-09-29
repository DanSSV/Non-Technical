const buttons = document.querySelectorAll(".custom-btn");

// Add event listeners for the "mouseover" and "mouseout" events
buttons.forEach((button) => {
  button.addEventListener("mouseover", () => {
    const icon = button.querySelector("i");
    if (icon) {
      icon.style.color = "red";
    }
  });

  button.addEventListener("mouseout", () => {
    const icon = button.querySelector("i");
    if (icon) {
      icon.style.color = "#000000"; // Replace with the original icon color
    }
  });
});
