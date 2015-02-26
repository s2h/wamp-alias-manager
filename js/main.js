$(document).ready(function () {
    getList();
});
function getList() {
    $('ul.alias-list').remove();
    $('div.result').remove();

    $.getJSON('api.php?action=list', function (data) {
        var ul = $("<ul/>", {
            "class": "alias-list"
        });
        $.each(data, function (key, val) {
            var del_link = $("<a/>", {
                class: 'del_link',
                //href: 'api.php?action=delete&name=' + val.substr(0, val.lastIndexOf('.')),
                onClick: 'deleteAlias("' + val.substr(0, val.lastIndexOf('.')) + '");',
                href: '#',
                text: 'del'
            });
            var view_link = $("<a/>", {
                class: 'view_link',
                onClick: 'viewAlias("' + val.substr(0, val.lastIndexOf('.')) + '");',
                href: '#',
                text: val
            });
            var span = $("<span/>", {
                class: 'hide_me'
            }).append(del_link);
            var li = $('<li/>', {
                id: 'alias' + key
                //html: val + "&nbsp;"
            }).append(view_link).append(span).appendTo(ul);
        });
        ul.appendTo("body");

    });
}

$(document).on('submit', 'form#create_form', function (event) {
    event.preventDefault();
    $.getJSON('api.php?action=create&name=' + $('#name').val() + '&path=' + $('#path').val(), function () {
        getList();
    });
});
function deleteAlias(alias) {
    event.preventDefault();
    $.getJSON('api.php?action=delete&name=' + alias, function () {
        getList();
    });
}

function viewAlias(alias) {
    event.preventDefault();
    $('div.result').remove();

    $.ajax({
        url: 'api.php?action=view&name=' + alias,
        cache: false
    })
        .done(function (html) {
            var result_div = $("<div/>", {
                class: 'result'
            });
            result_div.append(html).appendTo('body');
        });
}