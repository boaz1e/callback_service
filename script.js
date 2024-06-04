document
  .getElementById("leadForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    // Fetch form data
    const formData = new FormData(event.target);

    fetch("leads.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        const modal = document.getElementById("modal");
        const modalMessage = document.getElementById("modalMessage");
        const errorMessage = document.getElementById("error-message");

        if (data.status === "success") {
          errorMessage.textContent = "";
          modalMessage.textContent = `Thank you ${data.name}, we’ll contact you soon.`;
          modal.style.display = "block";
        } else if (data.status === "email_exists") {
          errorMessage.textContent = "Sorry, this email is already registered.";
        } else {
          errorMessage.textContent = "An error occurred. Please try again.";
        }
      })
      .catch((error) => console.error("Error:", error));
  });

// Close modal
document.querySelector(".close").addEventListener("click", function () {
  document.getElementById("modal").style.display = "none";
});
