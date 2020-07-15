$(function () {
    var mois = ["Jan", "Fev", "Mar", "Avr", "Mai", "Jun", "Jul", "Aou", "Sep", "Oct", "Nov", "Dec"];
    var PlanningItem;
    var allUser;
    var allPc;
    var strIdPcUse;
    if ($('.attribution').length > 0) {
        $('.attribution span.btn.btn-success').addClass("disabled");
        $.ajax({
            url: './crud/pc.php',
            type: 'POST',
            data: 'getselect=1',
            success: function (response) {
                allPc = JSON.parse(response);
                loadValidPC();
            }
        });
        $.ajax({
            url: './crud/user.php',
            type: 'POST',
            data: 'getselect=1',
            success: function (response) {
                allUser = JSON.parse(response);

            }
        });
    }

    for (let i = 0; i < $('.choose-cat').length; i++) {
        $('.choose-cat').eq(i).append('<span class="choose-cat-selector" style="width:' + (100 / ($('.choose-cat').eq(i).children('span[id]').length)) + '%"></span>');
        $('.choose-cat').eq(i).attr('select', '0');
        $('.choose-cat').eq(i).children('span').eq(0).addClass('active');
        $('.choose-cat-show[id="0"]').addClass('active');
    }

    $(document).on('click', '.choose-cat span', function () {
        if (!$(this).hasClass('active') && !$(this).attr('disable')) {
            idCat = $(this).parent().attr('id-cat');
            $(this).parent().find('span[id]').removeClass('active');
            id = $(this).addClass('active').attr('id');
            $(this).parent().attr('select', id);
            $('.choose-cat-show[id-cat="' + idCat + '"]').removeClass('active');
            $('.choose-cat-show[id-cat="' + idCat + '"][id="' + id + '"]').addClass('active');
            $(this).parent().find('.choose-cat-selector').css("left", ((100 / $(this).parent().children('span[id]').length) * $(this).index()) + "%");
        }

    })
    if ($("#currenttime")) {
        $d = new Date();
        $("#currenttime").html((($d.getHours() < 10) ? "0" + $d.getHours() : $d.getHours()) + ":" + (($d.getMinutes() < 10) ? "0" + $d.getMinutes() : $d.getMinutes()));
        window.setInterval(function () {
            $d = new Date();
            $("#currenttime").html((($d.getHours() < 10) ? "0" + $d.getHours() : $d.getHours()) + ":" + (($d.getMinutes() < 10) ? "0" + $d.getMinutes() : $d.getMinutes()));
        }, 30000);
    }

    function loadValidPC() {
        fn = "planning";
        var parent = $('.attribution');
        var IdUser = parent.find('#Selector1').val();
        var date1 = parent.find('#date-start').val();
        var date2 = parent.find('#date-end').val();
        $.ajax({
            url: './crud/' + fn + '.php',
            type: 'GET',
            data: {
                'getselect': 1,
                'date1': date1,
                'date2': date2,
            },
            success: function (response) {
                var pcUse = JSON.parse(response);
                strIdPcUse = ",";
                //var ArrayResult=[];
                var inHtml = "";
                let alreadyUse = 0;
                for (let i = 0; i < pcUse.length; i++) {
                    strIdPcUse += pcUse[i][0] + ",";
                    if (pcUse[i][1] == IdUser) {
                        alreadyUse++;
                    }
                }
                let count = 0;
                for (let i = 0; i < allPc.length; i++) {
                    if (!strIdPcUse.includes("," + allPc[i][0] + ",")) {
                        count++;
                        //ArrayResult=ArrayResult.concat([allPc[i]]);
                        inHtml += '<option value="' + allPc[i][0] + '">' + allPc[i][1] + '</option>';
                    }
                }
                $('#choose-pc .selector-search').html(inHtml);
                $('span#count-cp').html("Aucun Pc trouvé");
                if (alreadyUse > 0) $('span.already-use').removeAttr('hidden');
                if (alreadyUse == 0) $('span.already-use').attr('hidden', '');
                if (count > 0) {
                    $('span#count-cp').html(count + " Pc disponible");
                    $('.attribution span.btn.btn-success').removeClass("disabled");
                }
                if ($('input#date-duree-h').val() == "0" && $('input#date-duree-m').val() == "0") {
                    $('.attribution span.btn.btn-success').addClass("disabled");
                    $('#choose-pc .selector-search').html("");
                    $('span#count-cp').html("Définir une durée");
                    $('span.already-use').attr('hidden', '')
                }


            }
        });

    }
    function checkChangeDate() {
        fn = "planning";
        var parent = $('#popup-modal');
        var id_PC = parent.find('[name=pc]').val();
        var id_User = parent.find('[name=user]').val();
        var date1 = parent.find('[name=DateDebut]').val();
        var date2 = parent.find('[name=DateFin]').val();
        $.ajax({
            url: './crud/' + fn + '.php',
            type: 'GET',
            data: {
                'getselect': 1,
                'date1': date1,
                'date2': date2,
            },
            success: function (response) {
                var result = JSON.parse(response);
                var check=true;
                for(let i=0;i<result.length;i++){
                    if(result[i][0]==id_PC)
                    {
                        if(result[i][1]!=id_User){
                            check=false;
                        }
                    }
                }
                if(!check){$('#popup-modal #data-update').attr('disabled',true);$('span.already-use').removeAttr('hidden');}
                else{$('#popup-modal #data-update').removeAttr('disabled');$('span.already-use').attr('hidden','');}
            }
        })
    }
    //PLANNING
    $(document).on('click', '.attribution span.btn.btn-success', function () {
        var parent = $(this).parents('.attribution');
        var data = parent.find('#Selector1').val() + "/";
        data += parent.find('#Selector2').val() + "/";
        data += parent.find('#date-start').val() + "/";
        data += parent.find('#date-end').val();
        $.ajax({
            url: './crud/planning.php',
            type: 'GET',
            data: 'create=1&values=' + data,
            success: function (response) {
                var pc = $('#Selector2 option[value="' + $('#Selector2').val() + '"]').html();
                var name = $('#Selector1 option[value="' + $('#Selector1').val() + '"]').html();
                var date1 = parent.find('#date-start').val().replace("T", " à ");
                var date2 = parent.find('#date-end').val().replace("T", " à ");
                $('.success-attrib').html('Le PC "' + pc + '" a été attribué à "' + name + '"<br>Du <u>' + date1 + '</u> au <u>' + date2 + '</u>');
                $('.success-attrib').addClass('active');
                $('.derouleOnOff[id="1"]').removeClass("active");
                $('.btn-derouleOnOff[id="1"]').html('Attribuer un pc').addClass('btn-primary').removeClass('btn-danger').removeClass('outline');
                setTimeout(
                    function () {
                        loadValidPC();
                    }, 1000);

            }
        });
    });
    //SHOW PLANNING
    $(document).on('click', '.select tbody tr', function () {
        ajaxCatSelection($(this));
    });
    $(document).on('click', '*#data-delete', function () {
        var fn = ($(this).attr('fn')) ? $(this).attr('fn') : $(this).parents('[fn]').attr('fn');
        var id = ($(this).attr('data-id')) ? $(this).attr('data-id') : $(this).parents('[data-id]').attr('data-id');
        $row = $('table[fn="' + fn + '"] tbody tr[data-id="' + id + '"]');
        if (fn = "planning") {
            $.ajax({
                url: './crud/' + fn + '.php',
                type: 'POST',
                data: {
                    'delete': 1,
                    'id': id,
                },
                success: function (response) {
                    $('.success-attrib').removeClass('active');
                    $('#popup-modal .error').removeClass('active');
                    $('#popup-modal').attr('show', '');
                    $('#popup-modal').attr('data-id', '');
                    $('#popup-modal').attr('action', '');
                    action = "";
                    ajaxCatSelection($('.select tbody tr.select'));
                }
            });
        }
    })
    function ajaxCatSelection(el) {
        $('.select tbody tr').removeClass('select');
        el.addClass('select');
        var id = el.attr('data-id');
        var categ = el.parents('[fn]').attr('fn');
        $.ajax({
            url: './crud/planning.php',
            type: 'POST',
            data: {
                'planning': 1,
                'categ': categ,
                'id': id,
            },
            success: function (response) {
                PlanningItem = JSON.parse(response);
                result = "";
                if (PlanningItem.length == 0) result = '<tr disable=true><td>-- Aucun événement trouvé --</td></tr>';
                for (let i = 0; i < PlanningItem.length; i++) {
                    for (let j = 0; j < allPc.length; j++) {
                        if (allPc[j][0] == PlanningItem[i][2]) { pc = allPc[j][1]; j = allPc.length; }
                    }
                    for (let j = 0; j < allUser.length; j++) {
                        if (allUser[j][0] == PlanningItem[i][1]) { user = allUser[j][1] + " " + allUser[j][2]; j = allUser.length; }
                    }
                    date1 = new Date(Date.parse(PlanningItem[i][3]));
                    date2 = new Date(Date.parse(PlanningItem[i][4]));
                    result += '<tr data-id="' + PlanningItem[i][0] + '">'
                        + "<td value='" + PlanningItem[i][2] + "'>" + pc + "</td>"
                        + "<td value='" + PlanningItem[i][1] + "'>" + user + "</td>"
                        + "<td value='" + PlanningItem[i][3] + "'>" + date1.getDate() + " " + mois[date1.getMonth()] + " " + date1.getFullYear() + " " + ("0" + date1.getHours()).slice(-2) + "h" + ("0" + date1.getMinutes()).slice(-2) + "</td>"
                        + "<td value='" + PlanningItem[i][4] + "'>" + date2.getDate() + " " + mois[date2.getMonth()] + " " + date2.getFullYear() + " " + ("0" + date2.getHours()).slice(-2) + "h" + ("0" + date2.getMinutes()).slice(-2) + "</td>"
                        + "</tr>";
                }
                $('.planning .list-planning table tbody').html(result);
            }
        });
    }
    $('input#date-start').on('change', function () {
        if (Date.parse($('input#date-start').val()) > Date.parse($('input#date-end').val())) {
            $('#date-end').val($('input#date-start').val());
            $('input#date-duree-h').val('0');
            $('input#date-duree-m').val('0');
            $('.attribution span.btn.btn-success').addClass("disabled");
            $('#choose-pc .selector-search').html("");
            $('span#count-cp').html("Définir une durée");
        } else {
            setInterval();
        }
    });
    $('input#date-end').on('change', function () {
        if (Date.parse($('input#date-start').val()) > Date.parse($('input#date-end').val())) {
            $('#date-end').val($('input#date-start').val());
            $('input#date-duree-h').val('0');
            $('input#date-duree-m').val('0');
            $('.attribution span.btn.btn-success').addClass("disabled");
            $('#choose-pc .selector-search').html("");
            $('span#count-cp').html("Définir une durée");
        } else {
            setInterval();
        }
    });
    $('input#date-duree-h').on('change', function () {
        $val = $('input#date-duree-h').val();
        if (!Number.isInteger($val)) $('input#date-duree-h').val(parseInt($val) || 0);
        if ($('input#date-duree-m').val() == "") $('input#date-duree-m').val('0');
        $interval = parseInt($('input#date-duree-m').val()) * 60000 + parseInt($('input#date-duree-h').val()) * 3600000;
        $end = new Date(Date.parse($('#date-start').val()) + parseInt($interval));
        $('#date-end').val(convertDate($end));
        setInterval();
    });
    $('input#date-duree-m').on('change', function () {
        $val = $('input#date-duree-m').val();
        if (!Number.isInteger($val)) $('input#date-duree-m').val(parseInt($val) || 0);
        if ($('input#date-duree-h').val() == "") $('input#date-duree-h').val('0');
        $interval = parseInt($('input#date-duree-m').val()) * 60000 + parseInt($('input#date-duree-h').val()) * 3600000;
        $end = new Date(Date.parse($('#date-start').val()) + parseInt($interval));
        $('#date-end').val(convertDate($end));
        setInterval();
    });

    function setInterval() {
        $start = Date.parse($('#date-start').val());
        $end = Date.parse($('#date-end').val());
        $interval = ($end - $start);
        $minute = ($interval / 60000) % 60;
        $heure = (($interval / 60000) - $minute) / 60;
        $('input#date-duree-h').val($heure);
        $('input#date-duree-m').val($minute);
        if ($heure == 0 && $minute == 0) {
            $('.attribution span.btn.btn-success').addClass("disabled");
            $('#choose-pc .selector-search').html("");
            $('span#count-cp').html("Définir une durée");
        } else {
            loadValidPC();
        }
    }
    $('.attribution #Selector1').on('change', function () {
        loadValidPC();
    })
    $('.btn-derouleOnOff[id]').on('click', function () {
        if ($('.derouleOnOff.active[id="' + $(this).attr('id') + '"]').length > 0) {
            $('.derouleOnOff[id="' + $(this).attr('id') + '"]').removeClass("active");
            $(this).html('Attribuer un pc').addClass('btn-primary').removeClass('btn-danger').removeClass('outline');
        } else {
            if ($('.derouleOnOff[id="' + $(this).attr('id') + '"]').length > 0) {
                $('.derouleOnOff[id="' + $(this).attr('id') + '"]').addClass("active");
                $(this).html('Fermer').removeClass('btn-primary').addClass('btn-danger').addClass('outline');
            }
        }

    })
    $(document).on('click', '.planning .list-planning table tbody tr[data-id]', function () {
        var surtitle = "";
        ($(this).parents('[fn]').length > 0 && $(this).parents('[surtitle]').length > 0) ? surtitle = "er " + $(this).parents('[surtitle]').attr('surtitle') : "";
        var title = "Info évènement" + surtitle;
        var id = ($(this).attr('data-id')) ? $(this).attr('data-id') : $(this).parents('*[data-id]').attr('data-id');
        var fn = "planning";
        $('div#popup-modal .title').html(title);
        $('div#popup-modal').attr('show', 'true');
        $('div#popup-modal').attr('data-id', id);
        $('div#popup-modal').attr('fn', fn);
        innerhtml = "<ul class='info-evenmt'><li><b>PC : </b>" + $(this).children('td').eq(0).html() + "</li><li><b>Utilisateur : </b>"
            + $(this).children('td').eq(1).html() + "</li><li><b>Date début : </b>"
            + $(this).children('td').eq(2).html() + "</li><li><b>Date Fin : </b>"
            + $(this).children('td').eq(3).html() + "</li></ul>"
        $(".inner-modal").html(innerhtml);
        $('div#popup-modal .cont-butt').html('<button id="planning-edit" class="btn outline">Changer</button><button id="data-delete-popup" class="btn outline">Supprimer</button><button id="close" class="btn btn-primary">Annuler</button>');

    })
    $(document).on('click', '#planning-edit', function () {
        id = $(this).parents('[data-id]').attr('data-id');
        planning = $('.planning .list-planning table tbody tr[data-id="' + id + '"]');
        innerhtml = "<ul class='info-evenmt'><li name='pc' value='" + planning.children('td').eq(0).attr('value') + "'><b>PC : </b>" + planning.children('td').eq(0).html() + "</li><li name='user' value='" + planning.children('td').eq(1).attr('value') + "'><b>Utilisateur : </b>" + planning.children('td').eq(1).html() + "</li></ul><form>";
        innerhtml += '<div class="form-group"><label>Date début</label><input type="datetime-local" class="form-control" name="DateDebut" value="' + planning.children('td').eq(2).attr('value').replace(" ", 'T') + '"/></div>';
        innerhtml += '<div class="form-group"><label>Date début</label><input type="datetime-local" class="form-control" name="DateFin" value="' + planning.children('td').eq(3).attr('value').replace(" ", 'T') + '"/></div>';
        innerhtml += '<span class="already-use" hidden>Une autre personne utilise aussi cette ordinateur dans cet période</span></form>';
        $(".inner-modal").html(innerhtml);
        $('div#popup-modal .cont-butt').html('<button id="data-update" class="btn btn-success" disabled>Modifier</button><button id="close" class="btn btn-primary">Annuler</button>');
    })
    $(document).on('change', '[fn=planning] form input[type=datetime-local]', function () {
        checkChangeDate();
        setTimeout(
            function() 
            {
                ajaxCatSelection($('.select tbody tr.select'));
            }, 2000);

    })
})

function convertDate(timestamp) {
    result = timestamp.getFullYear() + "-" +
        ((timestamp.getMonth() + 1) < 10 ? "0" + (timestamp.getMonth() + 1) : (timestamp.getMonth() + 1)) +
        "-" + ((timestamp.getDate()) < 10 ? "0" + (timestamp.getDate()) : (timestamp.getDate())) +
        "\T" + ((timestamp.getHours()) < 10 ? "0" + (timestamp.getHours()) : (timestamp.getHours())) +
        ":" + ((timestamp.getMinutes()) < 10 ? "0" + (timestamp.getMinutes()) : (timestamp.getMinutes()));
    return result;
}