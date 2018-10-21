$(document).ready(() => {
    cmdHistory = [];
    cmdCursor = -1;
    $('.btn').prepend('<div class="hover"><span></span><span></span><span></span><span></span><span></span></div>');
    function changeTab(e) {
        const href = e.target.attributes.href.value;
        $('.page').addClass('hidden');
        $(href).removeClass('hidden');
    }
    $('#navigation button').click(e => changeTab(e));
    $('#navigation button').first().click();
    $('#test #query').keyup((event) => {
        if (event.keyCode === 13) {
            // ENTER key
            query = event.target.value;
            event.target.value = '';
            cmdHistory.push(query);
            cmdCursor = cmdHistory.length;
            target = '#test #result';
            if (query.trim().toLowerCase() === 'clear') {
                $(target).html('');
                return;
            }
            fetch('database.php', {
                method: 'POST',
                body: JSON.stringify({
                    sql: query
                })
            })
            .then(res => res.text())
            .then(data => {
                $(target).append($('<p class="queried">').text(query));
                jsonView.format(data, target);
            });
        } else if (event.keyCode === 38) {
            // UP key
            if (cmdCursor > 0) {
                event.target.value = cmdHistory[--cmdCursor];
            }
        } else if (event.keyCode === 40) {
            // DOWN key
            if (cmdCursor < cmdHistory.length - 1) {
                event.target.value = cmdHistory[++cmdCursor];
            }
        }
    });
})