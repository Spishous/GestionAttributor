<div class="date-defin">
    <div class="set-date">
        <input type="datetime-local" id="date-start" value="<?php echo date("Y-m-d\TH:i", time() + 7200) ?>">
        <input type="datetime-local" id="date-end" value="<?php echo date("Y-m-d\TH:i", time() + 7200) ?>">
    </div>
    <div class="time-line">
        <span class="line">
            <p class="duree">Durée = <input id="date-duree-h" value="0"> h <input id="date-duree-m" value="0"> m</p>
            <span class="info-line">Début</span><span class="info-line">Fin</span>
        </span></div>
</div>


<style>
.date-defin {
    margin: 20px auto 0;
    display: table-row;
}

    .time-line span.line {
        display: block;
        height: 3px;
        width: calc(100% - 230px);
        min-width: 170px;
        margin: 20px auto 60px;
        background-color: #2481d2;
        position: relative;
    }

    .time-line span.line:before,
    .time-line span.line:after {
        content: "";
        display: inline-block;
        height: 15px;
        width: 15px;
        right: 100%;
        top: 50%;
        transform: translateY(-50%);
        position: absolute;
        border-radius: 20px;
        background-color: #2481d2;
    }

    .time-line span.line:after {
        left: 100%;
        right: unset;
    }

    p.duree {
        text-align: center;
        padding-top: 10px;
        font-size: smaller;
        font-weight: 600;
    }

    p.duree input {
        width: 3em;
        background-color: transparent;
        border: none;
        border-bottom: 1px solid grey;
        text-align: center;
    }

    input#date-start {
        margin-right: 3em;
    }

    span.info-line:last-child {
        right: unset;
        left: 110%;
    }

    span.info-line {
        position: absolute;
        top: 0;
        font-size: smaller;
        transform: translateY(-50%);
        right: 110%;
    }
</style>