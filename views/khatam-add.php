<?php
$none = wp_create_nonce('khatam_add_nonce');

?>

<h1>Quran Khatam</h1>
<form method="post" action="<?= esc_url(admin_url('admin-post.php'))?>" id="khatam-add-form">
    <input type="hidden" name="khatam-add-nonce" value="<?php echo $none ?>" />
    <input type="hidden" name="action" value="khatam-save-response">

    Start Date:
    <input type="text"
           name="start_date"
           id="start-date"
           class="datepicker" /><br/>
    End Date:
    <input type="text"
           name="end_date"
           id="end-date"
           class="datepicker"/><br/>

    Meeting Time:
    <input
            type="text"
            name="meeting_date"
            id="meeting-date"
            class="datepicker"
            placeholder="02/01/2000"/>

    <input
            type="text"
            placeholder="00:00 am"
            style="width: 80px"/><br/>

    Meeting link: <input
            type="text"
            name="meeting_link"
            id="meeting-link"
            style="width: 200px"
            placeholder="https://zoom.com/some-meeting"/s>

    <div>
        <input type="submit" value="Save" class="button button-primary">
    </div>
</form>