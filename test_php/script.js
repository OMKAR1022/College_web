document.getElementById("userForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {
        name: formData.get("name"),
        birthdate: formData.get("birthdate"),
    };

    fetch("save_data.php", {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then(response => response.json())
    .then(responseData => {
        const messageContainer = document.getElementById("messageContainer");
        const errorContainer = document.getElementById("errorContainer");
        
        if (responseData.error) {
            errorContainer.textContent = "Error: " + responseData.error;
            messageContainer.textContent = ""; // Clear any previous success messages
        } else {
            errorContainer.textContent = ""; // Clear any previous error messages
            messageContainer.textContent = responseData.message;
            this.reset();
        }
    })
    .catch(error => {
        console.error(error);
    });
});
