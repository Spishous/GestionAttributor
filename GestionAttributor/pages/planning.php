<div class="container">
    <h1>Planning</h1>
    <br>
    <div>
        <span class="btn btn-primary btn-derouleOnOff" id="1">Attribuer un pc</span>
    </div>
    <div class="derouleOnOff" id="1">
        <?php include_once('./assets/functions/attribution.php'); ?>
    </div>
    <div class="success-attrib"></div>
    <?php include_once('./crud/user.php');
    $user = new USER();
    include_once('./crud/pc.php');
    $pc = new PC(); ?>
    <br>
    <div class="box-style planning d-flex">

        <div class="fit select">
            <div id-cat="1" class="choose-cat d-flex justify-content-center">
                <span id="0">User</span>
                <span id="1">PC</span>
            </div>
            <div id-cat="1" class="choose-cat-show" id="0">
                <?php showTableTools($user->FileName, "", true, false, false, false); ?>
                <?php showTable($user->getAll("Role=0"), $user->FileName, ",Prénom,,Inscription", "3,4", false, false, true, false); ?>
            </div>
            <div id-cat="1" class="choose-cat-show" id="1">
                <?php showTableTools($pc->FileName, "", true, false, false, false); ?>
                <?php showTable($pc->getAll(), $pc->FileName, "", "1", false, false, true, false); ?>
            </div>
        </div>
        <div class="list-planning">
            <div id-cat="2" class="choose-cat d-flex justify-content-center">
                <span id="0">List</span>
                <span id="1">Calendrier</span>
            </div>
            <div id-cat="2" class="choose-cat-show" id="0">
                <table>
                    <thead>
                        <tr>
                            <th>Pc</th>
                            <th>Utilisateur</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                        </tr>

                    </thead>
                    <tbody>
                        <tr disable=true>
                            <td>(Aucun élément sélectionner)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id-cat="2" class="choose-cat-show h-75" id="1">
                <div class="align-items-center justify-content-center h-100 d-flex">Fonctionnalité non disponible</div>
            </div>
        </div>
    </div>
    <script>
        /*$('#calendar').fullCalendar({
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,listWeek'
    },
    navLinks: true,
    editable: true,
    eventLimit: true,
    events: [{
            title: 'All Day Event',
            start: '2020-07-01'
        },
        {
            title: 'Long Event',
            start: '2018-07-07',
            end: '2020-07-10'
        },
        {
            id: 999,
            title: 'Repeating Event',
            start: '2020-07-09T16:00:00'
        },
        {
            id: 999,
            title: 'Repeating Event',
            start: '2020-07-16T16:00:00'
        },
        {
            title: 'Conference',
            start: '2018-07-11',
            end: '2020-07-13'
        },
        {
            title: 'Meeting',
            start: '2018-07-12T10:30:00',
            end: '2020-07-12T12:30:00'
        },
        {
            title: 'Lunch',
            start: '2020-07-12T12:00:00'
        },
        {
            title: 'Meeting',
            start: '2020-07-12T14:30:00'
        },
        {
            title: 'Happy Hour',
            start: '2020-07-12T17:30:00'
        },
        {
            title: 'Dinner',
            start: '2020-07-12T20:00:00'
        },
        {
            title: 'Birthday Party',
            start: '2018-07-13T07:00:00'
        },
        {
            title: 'Click for Google',
            url: 'https://google.com/',
            start: '2020-07-28'
        }
    ]
});*/
    </script>
</div>