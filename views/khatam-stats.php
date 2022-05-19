<style type="text/css">
    .khatam-stats-container table {
        border: 1px #000 solid;
        width: 100%;
    }
</style>

<div class="khatam-stats-container">
    <div class="mdc-data-table">
        <table class="mdc-data-table__table" aria-label="Dessert calories">
            <thead>
            <tr class="mdc-data-table__header-row">
                <th
                    class="mdc-data-table__header-cell"
                    role="columnheader"
                    scope="col"
                    aria-sort="none"
                    data-column-id="juz"
                >
                    <div class="mdc-data-table__header-cell-wrapper">
                        <div class="mdc-data-table__header-cell-label">
                            Juz
                        </div>
                        <!--
                        <button class="mdc-icon-button material-icons mdc-data-table__sort-icon-button"
                                aria-label="Sort by dessert" aria-describedby="dessert-status-label">arrow_upward</button>
                         -->
                        <div class="mdc-data-table__sort-status-label" aria-hidden="true" id="dessert-status-label">
                        </div>
                    </div>
                </th>
                <th
                    class="mdc-data-table__header-cell  "
                    role="columnheader"
                    scope="col"
                    aria-sort="ascending"
                    data-column-id="recitor"
                >
                    <div class="mdc-data-table__header-cell-wrapper">
                        <!--
                        <button class="mdc-icon-button material-icons mdc-data-table__sort-icon-button"
                                aria-label="Sort by carbs" aria-describedby="carbs-status-label">arrow_upward</button>
                         -->
                        <div class="mdc-data-table__header-cell-label">
                            Recitor
                        </div>
                        <div class="mdc-data-table__sort-status-label" aria-hidden="true" id="carbs-status-label"></div>
                    </div>
                </th>
                <th
                        class="mdc-data-table__header-cell  "
                        role="columnheader"
                        scope="col"
                        aria-sort="none"
                        data-column-id="protein"
                >
                    <div class="mdc-data-table__header-cell-wrapper">
                        <!--
                        <button class="mdc-icon-button material-icons mdc-data-table__sort-icon-button"
                                aria-label="Sort by protein" aria-describedby="protein-status-label">arrow_upward</button>
                        -->
                        <div class="mdc-data-table__header-cell-label">
                            Status
                        </div>
                        <div class="mdc-data-table__sort-status-label" aria-hidden="true" id="protein-status-label"></div>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody class="mdc-data-table__content">
            <?php foreach ($khatamStats as $item) { ?>
                <tr class="mdc-data-table__row">
                    <td class="mdc-data-table__cell "><?=$item->juz;?></td>
                    <td class="mdc-data-table__cell">
                        <?=$item->name;?>
                    </td>
                    <td class="mdc-data-table__cell ">
                        <?=($item->status ==  0) ? 'Reciting' : 'Done' ?>
                    </td>
                </tr>
            <?php } ?>
            <?php if (count($khatamStats) == 0) { ?>
                <tr>
                    <td colspan="3"> No one has registered for this khatam yet</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    jQuery.ready(function () {
        const dataTable = new MDCDataTable(document.querySelector('.mdc-data-table'));
    });
</script>