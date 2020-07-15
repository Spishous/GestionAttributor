$(function () {
    var action = "";
    //read
    $(document).on('click', '*#data-read', function () {
        var fn = ($(this).attr('fn')) ? $(this).attr('fn') : $(this).parents('[fn]').attr('fn');
        var id = ($(this).attr('data-id')) ? $(this).attr('data-id') : $(this).parents('[data-id]').attr('data-id');
        $.ajax({
            url: './crud/' + fn + ".php",
            type: 'GET',
            data: {
                'read': 1,
                'id': id,
            },
            success: function (response) {
                $('#data-search').parents('.tools').parent().find('tbody').html(response);
            }
        });
    });
    //readall
    $(document).on('click', '*#data-readall', function () {
        var fn = ($(this).attr('fn')) ? $(this).attr('fn') : $(this).parents('[fn]').attr('fn');
        $.ajax({
            url: './crud/' + fn + '.php',
            type: 'GET',
            data: {
                'readall': 1,
            },
            success: function (response) {
                $('#data-search').parents('.tools').parent().find('tbody').html(response);
            }
        });
    });
    // delete from database
    $(document).on('click', '*#data-delete', function () {
        var fn = ($(this).attr('fn')) ? $(this).attr('fn') : $(this).parents('[fn]').attr('fn');
        var id = ($(this).attr('data-id')) ? $(this).attr('data-id') : $(this).parents('[data-id]').attr('data-id');
        $row = $('table[fn="' + fn + '"] tbody tr[data-id="' + id + '"]');
        if (fn != "planning") {
            $.ajax({
                url: './crud/' + fn + '.php',
                type: 'POST',
                data: {
                    'delete': 1,
                    'id': id,
                },
                success: function (response) {
                    $row.remove();
                    $('#popup-modal .error').removeClass('active');
                    $('#popup-modal').attr('show', '');
                    $('#popup-modal').attr('data-id', '');
                    $('#popup-modal').attr('action', '');
                    action = "";
                }
            });
            (fn == "pc") ? func = "deletePc" : func = "deleteUser";
            $.ajax({
                url: './crud/planning.php',
                type: 'POST',
                data: func + '=1&id=' + id,
            });
        }

    });

    $(document).on('click', '*#data-delete-popup', function () {
        var fn = ($(this).attr('fn')) ? $(this).attr('fn') : $(this).parents('[fn]').attr('fn');
        var id = ($(this).attr('data-id')) ? $(this).attr('data-id') : $(this).parents('[data-id]').attr('data-id');
        $('div#popup-modal .title').html('Confirmez-vous la suppression?');
        $('div#popup-modal').attr('show', 'true');
        $('div#popup-modal').attr('data-id', id);
        $('div#popup-modal').attr('fn', fn);
        $("div#popup-modal .inner-modal form").html("");
        action = "suppr";
        $clicked_btn = $(this);
        $('div#popup-modal .cont-butt').html('<button id="data-delete" class="btn btn-danger">Supprimer</button><button id="close" class="btn btn-primary">Annuler</button>');
        /*$.ajax({
            url: './crud/' + fn + '.php',
            type: 'POST',
            data: {
                'delete': 1,
                'id': id,
            },
            success: function (response) {
                $clicked_btn.parents("tr").remove();
            }
        });*/
    });
    //Search
    function search(el) {
        var parent = el.parents('div.tools').parent();
        var fn = (el.attr('fn')) ? el.attr('fn') : el.parents('[fn]').attr('fn');
        var word = el.val();
        var seed = (parent.find('table[fn="' + fn + '"][seed]').length > 0) ? "&seedT=" + parent.find('table[fn="' + fn + '"][seed]').attr('seed') : "";
        $clicked_btn = el;
        if (word.length > 2) {
            $.ajax({
                url: './crud/' + fn + '.php',
                type: 'POST',
                data: 'search=1' + seed + '&word=' + word,
                success: function (response) {
                    $('.tool-search').parents('.tools').parent().find('table[fn="' + fn + '"] tbody').html(response);
                }
            });
        } else {
            $.ajax({
                url: './crud/' + fn + '.php',
                type: 'GET',
                data: 'readall=1' + seed,
                success: function (response) {
                    $('.tool-search').parents('.tools').parent().find('table[fn="' + fn + '"] tbody').html(response);
                }
            });
        }
    }
    $('input[name="search"]').on('keypress', function (e) {
        if (e.which === 13) {
            search($(this));

        }
    });
    $('*#data-search').on('click', function () {
        var searchBox = $(this).parent().children('input[name="search"]');
        search(searchBox);
    });
    //Edit
    $(document).on('click', '*#data-edit', function () {
        var title = "Edit";
        var rows = $(this);
        var fn = ($(this).attr('fn')) ? $(this).attr('fn') : $(this).parents('[fn]').attr('fn');
        var id = ($(this).attr('data-id')) ? $(this).attr('data-id') : $(this).parents('*[data-id]').attr('data-id');
        $('div#popup-modal .title').html(title);
        $('div#popup-modal').attr('show', 'true');
        $('div#popup-modal').attr('data-id', id);
        $('div#popup-modal').attr('fn', fn);
        var dataList = rows.parents('tr').find('[data-name]');
        $(".inner-modal").html("<form></form>");
        var form = $(".inner-modal form");
        for (i = 0; i < dataList.length; i++) {
            if (dataList.eq(i).is(':not([no-set="true"])') && dataList.eq(i).is(':not([no-edit="true"])')) {
                var label = rows.parents('table').find('thead').find('th').eq(i + 1).html();
                form.append('<div class="form-group"><label>' + label + '</label><input type="text" class="form-control" name="' + dataList.eq(i).attr('data-name') + '" value="' + dataList.eq(i).html() + '"/></div>');
            }
            if (dataList.eq(i).is('[no-set="true"]:not([no-edit="true"])')) {
                var label = rows.parents('table').find('thead').find('th:not(".tbl-select")').eq(i).html();
                form.append('<div class="form-group desactive"><label>' + label + '</label><input type="text" class="form-control" name="' + dataList.eq(i).attr('data-name') + '" value="' + dataList.eq(i).html() + '"/></div>');
            }
        }
        action = "edit";
        $('div#popup-modal .cont-butt').html('<button id="data-update" class="btn btn-success">Modifier</button><button id="close" class="btn btn-primary">Annuler</button>');
    });
    // add
    $(document).on('click', '#modal-add', function () {
        var now = new Date();
        var surtitle = "";
        ($(this).parents('[fn]').length > 0 && $(this).parents('[surtitle]').length > 0) ? surtitle = "er " + $(this).parents('[surtitle]').attr('surtitle') : "";
        var title = "Ajout" + surtitle;
        var fn = ($(this).attr('fn')) ? $(this).attr('fn') : $(this).parents('[fn]').attr('fn');
        var id = ($(this).attr('data-id')) ? $(this).attr('data-id') : $(this).parents('*[data-id]').attr('data-id');
        var rows = $(this).parents('[fn]').parent().find('table[fn="' + fn + '"] thead tr th:not(".tbl-action"):not(".tbl-select"):not("[no-edit]")');
        $('div#popup-modal .title').html(title);
        $('div#popup-modal').attr('show', 'true');
        $('div#popup-modal').attr('data-id', id);
        $('div#popup-modal').attr('fn', fn);
        var dataList = rows;
        $(".inner-modal").html("<form></form>");
        var form = $(".inner-modal form");
        for (i = 0; i < dataList.length; i++) {
            if (dataList.eq(i).is(':not([no-set="true"])') && dataList.eq(i).is(':not([no-edit="true"])')) {
                var label = rows.parents('table').find('thead').find('th:not(".tbl-select")').eq(i).html();
                form.append('<div class="form-group"><label>' + label + '</label><input type=' + (dataList.eq(i).is('[isdate="true"]') ? "date" : "text") + '" class="form-control" name="' + dataList.eq(i).attr('data-name') + '"/></div>');
            }
            if (dataList.eq(i).is('[no-set="true"]:not([no-edit="true"])')) {
                var label = rows.parents('table').find('thead').find('th:not(".tbl-select")').eq(i).html();
                form.append('<div class="form-group desactive"><label>' + label + '</label><input type="' + ((dataList.eq(i).has('[isdate="true"]')) ? 'date' : "text") + '" class="form-control"/></div>');
            }
        }
        action = "add";
        $('div#popup-modal .cont-butt').html('<button id="data-add" class="btn btn-success">Ajouter</button><button id="close" class="btn btn-primary">Annuler</button>');
    });
    $(document).on('click', '#data-add', function () {
        var elemnt = $(this);
        var fn = ($(this).attr('fn')) ? $(this).attr('fn') : $(this).parents('[fn]').attr('fn');
        var data = "";
        var seed = ($('table[fn="' + fn + '"][seed]').length > 0) ? "&seedT=" + $('table[fn="' + fn + '"][seed]').attr('seed') : "";
        if (elemnt.parents('#popup-modal')) {
            data = $("#popup-modal form").serialize();
        };
        $.ajax({
            url: './crud/' + fn + '.php',
            type: 'GET',
            data: 'add=1' + seed + '&' + data,
            success: function (response) {
                if (response.indexOf("Error:") < 0) {
                    $('#popup-modal').attr('show', '');
                    $('#popup-modal .error').removeClass('active');
                    $('table[fn="' + fn + '"] tbody').html(response);
                } else {
                    $('#popup-modal .error').addClass('active');
                    $('#popup-modal .error').html("Erreur lors de l'ajout, une données peut être en double");
                }
                if ($('[fn="' + fn + '"][reload="true"]').length > 0) { location.reload(); }
            }
            /*success: function (response) {
                if (response.indexOf("Error:") < 0){
                    $('#popup-modal').attr('show', '');
                    $('#popup-modal .error').removeClass('active');
                    $('table[fn="'+fn+'"] tbody').prepend(response);
                }else{
                    $('#popup-modal .error').addClass('active');
                    $('#popup-modal .error').html("Erreur lors de l'ajout, une donn�es peut �tre en double");
                }
            }*/
        });
    });
    // Update
    $(document).on('click', '*#data-update', function () {
        var click = $(this).blur();
        click.css('pointer-events', 'none');
        var fn = ($(this).attr('fn')) ? $(this).attr('fn') : $(this).parents('[fn]').attr('fn');
        var id = (click.attr('data-id')) ? click.attr('data-id') : click.parents('*[data-id]').attr('data-id');
        data = $("form").serialize();
        $.ajax({
            url: './crud/' + fn + '.php',
            type: 'GET',
            data: 'update=' + 1 + '&id=' + id + '&' + data,
            success: function (response) {
                $('#name').val('');
                $('#comment').val('');
                $('#display_area').append(response);
                if (click.parents("div#popup-modal")) {
                    $('#popup-modal').attr('show', '');
                    $('#popup-modal .error').removeClass('active');
                    var row = $('table[fn="' + fn + '"] tbody tr[data-id="' + id + '"]');
                    var dataList = decodeURIComponent(data).split('&');
                    for (i = 0; i < dataList.length; i++) {
                        var name_value = dataList[i].split('=');
                        row.find('[data-name="' + name_value[0] + '"]').html(name_value[1]);
                    }
                }
            }
        });
    });
    $(document).on('keypress', 'div#popup-modal form input', function (e) {
        if (e.which === 13) {
            if (action == "add") {
                var click = $(this).blur();
                click.css('pointer-events', 'none');
                var fn = ($(this).attr('fn')) ? $(this).attr('fn') : $(this).parents('[fn]').attr('fn');
                var data = "";
                var seed = ($('table[fn="' + fn + '"][seed]').length > 0) ? "&seedT=" + $('table[fn="' + fn + '"][seed]').attr('seed') : "";
                if (click.parents('#popup-modal')) {
                    data = $("#popup-modal form").serialize();
                }
                $.ajax({
                    url: './crud/' + fn + '.php',
                    type: 'GET',
                    data: 'add=1' + seed + '&' + data,
                    success: function (response) {
                        if (response.indexOf("Error:") < 0) {
                            $('#popup-modal').attr('show', '');
                            $('#popup-modal .error').removeClass('active');
                            $('table[fn="' + fn + '"] tbody').html(response);
                        } else {
                            $('#popup-modal .error').addClass('active');
                            $('#popup-modal .error').html("Erreur lors de l'ajout, une données peut être en double");
                        }
                    }
                });
                action = "";
            }
            if (action == "edit") {
                var click = $(this).blur();
                click.css('pointer-events', 'none');
                var fn = ($(this).attr('fn')) ? $(this).attr('fn') : $(this).parents('[fn]').attr('fn');
                var id = (click.attr('data-id')) ? click.attr('data-id') : click.parents('*[data-id]').attr('data-id');
                data = $("form").serialize();
                $.ajax({
                    url: './crud/' + fn + '.php',
                    type: 'GET',
                    data: 'update=' + 1 + '&id=' + id + '&' + data,
                    success: function (response) {
                        $('#name').val('');
                        $('#comment').val('');
                        $('#display_area').append(response);
                        if (click.parents("div#popup-modal")) {
                            $('#popup-modal').attr('show', '');
                            $('#popup-modal .error').removeClass('active');
                            var row = $('table[fn="' + fn + '"] tbody tr[data-id="' + id + '"]');
                            var dataList = decodeURIComponent(data).split('&');
                            for (i = 0; i < dataList.length; i++) {
                                var name_value = dataList[i].split('=');
                                row.find('[data-name="' + name_value[0] + '"]').html(name_value[1]);
                            }
                        }
                    }
                });
                action = "";
            }

        }
    });
    //Close modal
    $(document).on('click', 'div#popup-modal #close', function () {
        $('#popup-modal .error').removeClass('active');
        $(this).parents('#popup-modal').attr('show', '');
        $(this).parents('#popup-modal').attr('data-id', '');
        $(this).parents('#popup-modal').attr('action', '');
        action = "";
    });
});