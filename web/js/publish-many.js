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
            output('Publishing Item: ' + id);
            output(response.data.message);

            // update the stackid hidden input with the remaining items
            setStackIds(stackids);

            if (stackids.length !== 0) {
                // call the next one
                publishNext(data, url);
                updateCountDisplay();
            } else {
                output('---------------------------');
                output('All done!');
            }
        } else {
            output(response.data.message);
        }
    })
    .fail(function() {
        output('Error publishing ...');
    });
}

function updateCountDisplay() {
    var count = Number($('#item-count').html());
    count--;
    $('#item-count').html(count);
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

function getStackCount() {
    return $('input[name=stackcount]').val();
}

function output($text) {
    var currentText = $('#console-output').html();
    var newText = currentText + '\n' + $text;
    $('#console-output').html(newText);
}

// Attach behaviour to submit button
$('.publish-many-form').submit(function(event) {
    event.preventDefault(); // prevent normal submit
    event.stopImmediatePropagation(); // preven double submission (wtf?)
    var data = $(this).serializeArray();
    var url = $(this).attr('action');
    console.log('Publishing items now ...');
    publishNext(data, url);
});
