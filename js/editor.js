jQuery( document ).ready( function( $ ) {
    $(document).on('click', 'input[name="edit"]', function() {
        $(this).hide()
        $(this).next().show()
        $(this).closest('.reservation-row').find('input').prop('disabled', false)
    })
    $(document).on('click', 'input[name="remove"]', function() {
        let name = $(this).closest('.reservation-row').find('input[name="name"]').val()
        return window.confirm('Remove the reservation for ' + name + '?');
    })
} );