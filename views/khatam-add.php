<?php
$khatam = $khatam ?? new StdClass;
?>

<style type="text/css">
    ::placeholder {
        color: #aaa;
    }
</style>

<h1>Quran Khatam - Add a new Khatam</h1>
<form method="post" action="<?= esc_url(admin_url('admin-post.php'))?>" id="khatam-add-form">
    <input type="hidden" name="khatam-save-response" value="<?php echo $nonce ?>" />
    <input type="hidden" name="action" value="khatam-save-response">
    <input type="hidden" name="id" value="<?=$khatam->id ?? ''?>" />

    <table style="margin-top: 40px;">
        <tr>
            <td>Start Date</td>
            <td>
                <input type="text"
                       name="start_date"
                       id="start-date"
                       class="datepicker"
                       placeholder="2021-01-01"
                       value="<?=$khatam->startDate ?? ''?>" />
            </td>
        </tr>
        <tr>
            <td>End Date</td>
            <td>
                <input type="text"
                       name="end_date"
                       id="end-date"
                       class="datepicker"
                       placeholder="2021-01-01"
                       value="<?=$khatam->endDate ?? ''?>"/>
            </td>
        </tr>
        <tr>
            <td>Meeting Time</td>
            <td>
                <input
                    type="text"
                    name="meeting_ts"
                    id="meeting-ts"
                    class="datepicker"
                    placeholder="2022-11-11 12:12:00"
                    value="<?=$khatam->meetingTs ?? ''?>"/>
            </td>
        </tr>
        <tr>
            <td>Meeting Link</td>
            <td><input
                    type="text"
                    name="meeting_link"
                    id="meeting-link"
                    style="width: 300px"
                    placeholder="https://example.com/some-meeting"
                    value="<?=$khatam->meetingLink ?? ''?>"/>
            </td>
        </tr>
    </table>

    <div>
        <input type="submit" value="Save" class="button button-primary">
    </div>
</form>