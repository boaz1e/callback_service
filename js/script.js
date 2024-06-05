document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("leadForm");

  if (form) {
    console.log("Form found and event listener added");
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      console.log("Form submitted");

      const formData = new FormData(event.target);

      fetch("controllers/leads.php", {
        // Update this line
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((text) => {
          console.log("Raw response:", text);
          let data;
          try {
            data = JSON.parse(text);
          } catch (error) {
            console.error("Error parsing JSON:", error);
            document.getElementById("error-message").textContent =
              "An error occurred while processing your request.";
            return;
          }

          const modal = document.getElementById("modal");
          const modalMessage = document.getElementById("modalMessage");
          const errorMessage = document.getElementById("error-message");

          if (data.status === "success") {
            errorMessage.textContent = "";
            modalMessage.textContent = `Thank you ${data.name}, we’ll contact you soon.`;
            modal.style.display = "block";
          } else if (data.status === "email_exists") {
            errorMessage.textContent =
              "Sorry, this email is already registered.";
          } else {
            errorMessage.textContent = "An error occurred. Please try again.";
          }
        })
        .catch((error) => console.error("Error:", error));
    });
  } else {
    console.error("Form not found");
  }
});

// Close modal
document.querySelector(".close").addEventListener("click", function () {
  document.getElementById("modal").style.display = "none";
});
