<?php

wp_enqueue_style('jquery-wp-cs', KHATAM_URL);
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_script('custom-script', KHATAM_URL . '/public/admin.js');

wp_enqueue_style('material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons');
wp_enqueue_style('material', 'https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css');
wp_enqueue_script('material', 'https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js');


wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js');
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

<div class="mdc-card current-khatam-card-container">
    <div style="background-color: #444;padding-left: 16px">
        <h2 style="color: #ddd">Current Khatam</h2>
    </div>
    <div class="current-khatam-card">
        <div class="current-khatam-form-container">
            <div class="mdc-card__content">
                <form>
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
                        <input type="submit" value="Save" class="button action">
                    </div>
                </form>
            </div>
        </div>
        <div class="current-khatam-chart-container" style="position: relative">
            <div class="chart-text">
                <h1>80%</h1>
            </div>
            <canvas id="registered-users" width="300" height="300"></canvas>
        </div>
        <div class="current-khatam-chart-container" style="position: relative">
            <div class="chart-text">
                <h1>20%</h1>
            </div>
            <canvas id="reciting-users" width="300" height="300"></canvas>

        </div>
        <div style="clear: both"></div>
    </div>
</div>
<script type="text/javascript" src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>


