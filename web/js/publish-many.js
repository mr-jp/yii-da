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
            updateProgressBar();

        } else {
            output(response.data.message);
        }
    })
    .fail(function() {
        output('Error publishing ...');
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