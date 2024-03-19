document.addEventListener('DOMContentLoaded', function() {
    // Get delete buttons
    const deleteButtons = document.querySelectorAll('.deleteButton');


    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
          
            event.preventDefault();
            
            //  confirmation popup
            const isConfirmed = confirm('Êtes-vous sûr de vouloir faire cette action ?');

            //  proceed with  action
            if (isConfirmed) {
               
                window.location.href = button.getAttribute('href');
            }
        });
    });
});