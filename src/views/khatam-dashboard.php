<?php

wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js');


// Registered users
$totalRegistered = 0;
$finishedReciting = 0;

if ($khatamStats !== null) {
    foreach ($khatamStats as $stat) {
        $totalRegistered += $stat->count;
        if ($stat->status == 1) {
            print 'hi' . $stat->count;
            $finishedReciting = $stat->count;
        }
    }

    $registeredPercentage = round(($totalRegistered * 100) / 30, 2);
    if ($totalRegistered > 0) {
        $finishedRecitingPercentage = round(($finishedReciting * 100) / $totalRegistered, 2);
    }
}
else {
    $registeredPercentage = 0;
    $finishedRecitingPercentage = 0;
}

?>

<link type="text/css" rel="stylesheet" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css"/>

<style type="text/css">
    .datepicker {
        width: 100px;
    }
    .current-khatam-card-container {
        width: 97%;
        margin-right: 16px;
        height: 500px;
        margin-top: 40px;
    }
    .current-khatam-card-container table th {
        text-align: left;
    }
    .current-khatam-card {
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        overflow:  hidden;
    }
    .current-khatam-card .mdc-card__content {
        padding: 16px;
    }
    .current-khatam-form-container {
        width: 350px;
        height: 100%;
        float: left!important;
    }
    .current-khatam-chart-container {
        width: 450px;
        float: right !important;
        border-left-width: 2px;
        border-left: 1px #ddd solid;
    }
    .chart-text {
        width: 100%;
        height: 40px;
        position: absolute;
        top: 50%;
        left: 0;
        margin-top: -20px;
        line-height:19px;
        text-align: center;
        z-index: 999999999999999;
        color:rgba(0, 200, 50, 0.6);

    }

</style>
<h1>Quran Khatam</h1>

<textarea id="current-khatam-data" style="display: none">
    <?= json_encode($currentKhatam); ?>
</textarea>



<input type="hidden" id="registered-recitors" value="<?= $totalRegistered?>" />
<input type="hidden" id="finished-recitors" value="<?= $finishedReciting?>" />
<div class="mdc-card current-khatam-card-container">
    <div style="background-color: #444;padding-left: 16px">
        <h2 style="color: #ddd">Current Khatam</h2>
    </div>
    <?php if (!empty($currentKhatam)) { ?>
        <div class="current-khatam-card">
        <div class="current-khatam-form-container">
            <div class="mdc-card__content">
                <form>
                    <table>
                        <tr>
                            <td>Start Date</td>
                            <td><?= $currentKhatam->startDate; ?></td>
                        </tr>
                        <tr>
                            <td>End Date</td>
                            <td><?= $currentKhatam->endDate; ?></td>
                        </tr>
                        <tr>
                            <td>Meeting Time</td>
                            <td><?= $currentKhatam->meetingTs ?></td>
                        </tr>
                        <tr>
                            <td>Meeting Link</td>
                            <td><?= $currentKhatam->meetingLink ?></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="current-khatam-chart-container" style="position: relative">
            <div class="chart-text">
                <h1><?= $registeredPercentage ?>%</h1>
            </div>
            <canvas id="registered-users" width="300" height="300"></canvas>
        </div>
        <div class="current-khatam-chart-container" style="position: relative">
            <div class="chart-text">
                <h1><?= $finishedRecitingPercentage ?>%</h1>
            </div>
            <canvas id="reciting-users" width="300" height="300"></canvas>

        </div>
        <div style="clear: both"></div>
    </div>
    <?php } else { ?>
        <div style="padding-left: 16px;">
            <h3>There is no active Khatam</h3>
        </div>
    <?php } ?>
</div>
<div class="mdc-card current-khatam-card-container" >
        <div style="background-color: #444;padding-left: 16px">
            <h2 style="color: #ddd">Upcoming Khatam(s)</h2>
        </div>
        <table style="margin-left: 16px;">
            <tr>
                <th>ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Meeting Time</th>
                <th>Meeting Link</th>
                <th>Actions</th>
            </tr>
            <?php if (count($futureKhatams) === 0) { ?>
                <tr>
                    <td colspan="5" style="text-align: center"><h3>There are no upcoming Khatams</h3></td>
                </tr>
            <?php } ?>
            <?php foreach ($futureKhatams as $khatam) {?>
                <tr>
                    <td><?=$khatam->id?></td>
                    <td><?=$khatam->startDate?></td>
                    <td><?=$khatam->endDate?></td>
                    <td><?=$khatam->meetingTs?></td>
                    <td><?=$khatam->meetingLink?></td>
                    <td>
                        <a href="admin.php?page=khatams-add&id=<?=$khatam->id?>">Edit</a>
                        <a href="admin-post.php?action=khatam-delete-khatam&id=<?=$khatam->id?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
</div>


<script type="text/javascript" src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script type="text/javascript" src="<?=KHATAM_URL . '/public/admin.js'?>"></script>

