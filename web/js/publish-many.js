function publishNext(data, url) {
    // get all stackids
    var stackids = getStackIds();
    if (stackids.length !== 0) {
        // take the first item
        var stackid = stackids.shift();

        // publish it
        publishItem(stackid, data, url, stackids);
    }
}

function publishItem(id, data, url, stackids)
{
    updateListItemLoading(id);

    // add id to data
    url += "&id=" + id;
    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: data
    })
    .done(function(response) {
        if (response.data.success == true) {
            output('---------------------------');
            output(response.data.message);

            // update the stackid hidden input with the remaining items
            setStackIds(stackids);

            if (stackids.length !== 0) {
                // call the next one
                publishNext(data, url);
            } else {
                output('---------------------------');
                output('All done!');
            }
            // updateProgressBar();
            updateListItemDone(id, response.data.url);
        } else {
            output(response.data.message);
        }
    })
    .fail(function() {
        output('Error publishing ...');
        updateListItemError(id);
    });
}

function updateProgressBar() {
    var currentstack = Number($('input[name="currentstack"]').val());
    var stackcount = Number($('input[name="stackcount"]').val());
    currentstack++;
    // output(currentstack + ' / ' + stackcount);

    var percentage = (currentstack / stackcount) * 100;

    // update progress bar
    $('#upload-progress').attr('aria-valuenow', percentage).css('width', percentage +'%');

    // update currentstack
    $('input[name="currentstack"]').val(currentstack);
}

function updateListItemLoading(id) {
    var e = $('li[id='+id+']');
    text = id + ' <span class="glyphicon glyphicon-time" aria-hidden="true"></span>';
    e.html(text);
}

function updateListItemDone(id, url) {
    var e = $('li[id='+id+']');
    var link = '<a href="'+url+'" target="_new">' + id + '</a>';
    var text = link + ' <span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span>';
    e.html(text);
}

function updateListItemError(id) {
    var e = $('li[id='+id+']');
    text = id + ' <span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span>';
    e.html(text);
}

function setStackIds(stackids) {
    $('input[name=stackids]').val(stackids.join(','));
}

function getStackIds() {
    var stackids = $('input[name=stackids]').val();
    if (stackids === '') {
        return [];
    }
    return $('input[name=stackids]').val().split(",");
}

function output(text) {
    var currentText = $('#console-output').html();
    var newText = text + '\n' + currentText;
    $('#console-output').html(newText);
}

// I can't build the URL from the gallery id :(
// function changeDescription(target)
// {
//     var text = $(target).parent().text();
//     alert(text);
// }

// Attach behaviour to submit button
$('.publish-many-form').submit(function(event) {
    event.preventDefault();             // prevent normal submit
    event.stopImmediatePropagation();   // prevent double submission (wtf?)
    var data = $(this).serializeArray();
    var url = $(this).attr('action');
    $('#progress-bar-div').removeClass('hidden'); // show progress bar
    $('button[type="submit"]').prop('disabled', 'disabled');
    publishNext(data, url);
});

// Change description based on which folder was selected
// jQuery('form[class="publish-many-form"').find(':checkbox').on('click', function(event) {
//     changeDescription(event.target);
// });
