console.log("ok  formsubmition??");

document.addEventListener("DOMContentLoaded", function() {
    // Check if there is a flash message with the "error" class
    var errorMessage = document.querySelector('.alert-danger');
    
    if (errorMessage) {
        // If error message is found, find the form section and scroll to it
        var form = document.getElementById("form");
        
        if (form) {
            form.scrollIntoView({ behavior: 'smooth' });
        }
    }
});
