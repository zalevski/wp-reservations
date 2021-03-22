<?php
wp_enqueue_script('reservation_editor_js', plugins_url('/js/editor.js', __FILE__), array( 'jquery'), '1.0.1');
wp_enqueue_style('reservation_editor_css', plugins_url('/css/editor.css', __FILE__), '1.0.1');
?>

<div class="wrap reservations-wrap">
    <h2 class="reservations-heading">Reservations</h2>
    <table class="widefat reservations-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Confirmed</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr class="reservation-new">
                <form method="post" action="?page=wp-reservations" id="reservation_add"></form>
                <td><input type="date" name="date" required form="reservation_add"></td>
                <td><input type="text" name="name" required form="reservation_add"></td>
                <td><input type="checkbox" name="confirmation" form="reservation_add"></td>
                <td><input type="submit" name="create" value="Create" form="reservation_add" class="reservations-button"></td>
            </tr>
            <?php
            $result = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC"); // could be sorted differently e.g. by reservation date
            foreach ($result as $reservation) :
            $checked = ($reservation->confirmation == 1) ? "checked" : ""; ?>
            <tr class="reservation-row">
                <?php $form_id = 'reservation_edit_' . $reservation->id; ?>
                <form method="post" action="?page=wp-reservations" id="<?php echo $form_id; ?>"></form>
                <td><input type="date" name="date" value="<?php echo $reservation->date; ?>" disabled required
                        form="<?php echo $form_id; ?>"></td>
                <td><input type="text" name="name" value="<?php echo $reservation->name; ?>" disabled required
                        form="<?php echo $form_id; ?>"></td>
                <td><input type="checkbox" name="confirmation" <?php echo $checked; ?> disabled
                        form="<?php echo $form_id; ?>"></td>
                <td>
                    <input type="hidden" name="id" value="<?php echo $reservation->id; ?>"
                        form="<?php echo $form_id; ?>">
                    <input type="button" name="edit" value="Edit" form="<?php echo $form_id; ?>" class="reservations-button">
                    <input type="submit" name="update" value="Update" form="<?php echo $form_id; ?>" class="reservations-button" hidden>
                    <input type="submit" name="remove" value="Remove" form="<?php echo $form_id; ?>" class="reservations-button">
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>