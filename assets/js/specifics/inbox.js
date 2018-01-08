jQuery(document).ready(function ($) {
    $('#reply-box-form').submit(function (e) {
        e.preventDefault();
        if( !document.getElementById('reply-box').disabled && $('#reply-box').val().length != 0 ) {
            $.post($(this).attr('action'), $(this).serialize(),function (data) {
                var data = $.parseJSON(data);
                console.log(data);
                message_sent_animation(data, '#display-messages');
                $('#messages-main a[data-msisdn="'+data.msisdn+'"]').click();
                $('#reply-box').val('');
            });
        }
    });
});
jQuery(document).on('click', '[data-contact]', function (e) {
    e.preventDefault();
    var Ds = $(this),
        _msisdn  = Ds.data('msisdn'),
        _msgBox = $('#inbox-conversation-viewer'),
        _contactNames = Ds.find('.contact-name'),
        _contactNameOnMsgBox = _msgBox.find('#inbox-conversation-viewer-contact-name'),
        _replyBox = $('#reply-box');

    $('[data-contact]').removeClass('active');
    $.post(base_url('messaging/inbox/'+_msisdn), function (data) {
        var data = $.parseJSON(data);

        $('#display-messages').html( build_message_html(data.inbox) );
        Ds.toggleClass('active');

        var contacts = [];
        for (var i = 0; i < _contactNames.length; i++) {
            contacts.push( $(_contactNames[i]).text() );
        }
        var _contact = truncate(contacts.join(', '), 50);
        _msgBox.attr('data-contact-msisdn', _msisdn);
        _replyBox.attr('disabled', false);
        _replyBox.attr('placeholder', 'Send SMS to ' + _contact + '...');
        $('[data-msisdn]').val(_msisdn);
        $('#inbox-conversation-viewer-contact-name').text(_contact);

        // Remove indicator if any
        Ds.find('.notification-circle.unread').remove();
        // Set to unread in DB
        // status
        // 1 = unread
        // 0 = read
        $.post(base_url('messaging/inbox/update-status/'+_msisdn), {status:0},function (data) {
            console.log(data);
        });

        // $('#display-contact-name').html( data. )
        console.log(data);
    });
});

function build_message_html($message) {
    var h = "";
    for (var i = 0; i < $message.length; i++) {
        var _date = ( moment($message[i].created_at).format('MM/DD/YYYY') == moment().format('MM/DD/YYYY') ? 'Today' : moment($message[i].created_at).format('MM/DD/YYYY') ) + ' at ' + moment($message[i].created_at).format('hh:mma');
        var status = ($message[i].status == 'pending' || $message[i].status == 'failed') ? '<br><small class="ms-status"><i class="zmdi zmdi-time">&nbsp;</i>'+$message[i].status+'</small>' : '';
        h += '<div class="lv-item media '+("outbox"==$message[i].table_name?'right':'')+'" data-msisdn="'+($message[i].msisdn)+'" >';
            h += '<div class="media-body">';
                h += '<div class="ms-item">'+$message[i].body+'</div>';
                h += status;
                h += '<small class="ms-date"><i class="zmdi zmdi-time">&nbsp;</i>'+_date+'</small>';
            h += '</div>';
        h += '</div>';
    }
    return h;
}

function message_sent_animation(html, append) {
    var _html = $('<div class="lv-item media right" />');
    var status = (html.status == 'pending' || html.status == 'failed') ? '<br><small class="ms-status"><i class="zmdi zmdi-time">&nbsp;</i>'+html.status+'</small>' : '';
    _html.html('<div class="media-body"><div class="ms-item">'+html.body+'</div>'+status+'<small class="ms-date"><i class="zmdi zmdi-time">&nbsp;</i>'+html.date+'</small></div>');
    $(append).append(_html);
}