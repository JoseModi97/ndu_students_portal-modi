const regDocumentsForm = $('#reg-documents-form');
regDocumentsForm.validate();

const regDocsLoader = $('#reg-documents-form > .loader');
regDocsLoader.html(loader);
regDocsLoader.hide();

const regDocsErrorDisplay =  $('#reg-documents-form > .error-display');
regDocsErrorDisplay.hide();

/**
 * Add file input field on documents form
 * @param e
 * @param regDocumentUrl
 */
function addRegDocument(e, regDocumentUrl){
    let labelId = 'document-' +  $(this).val();
    if(document.getElementById(labelId)!=null){
        regDocsErrorDisplay.html('This document type is already selected for upload.');
        regDocsErrorDisplay.show();
    }else{
        regDocsLoader.show();
        regDocsErrorDisplay.html('')
        regDocsErrorDisplay.hide();
        $('.reg-documents').append(createDocumentField(labelId));
        getRegDocument(regDocumentUrl, $(this).val(), labelId);

        // add validate rules
        regDocumentsForm.validate();
        $('#' + labelId).rules('add', {required: true});
    }
    $(this).val('');
}

/**
 * Create file input field
 * @param labelId
 * @returns {string}
 */
function createDocumentField(labelId){
    return `
    <div class="row">
        <div class="col-12">
            <button id="btn-${labelId}" class="btn btn-danger remove-reg-document float-right">Remove</button>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="${labelId}" class="required-control-label"></label>
                <input type="file" class="form-control" id="${labelId}" name="${labelId}" required>
                <small id="${labelId}-desc"class="text-muted"></small>
            </div>
        </div>
    </div>`;
}

/**
 * Remove file input field from documents form
 * @param e
 */
function removeRegDocument(e){
    e.preventDefault();
    let fileInputId = $(this).attr('id').substring(4);

    // remove validate rules
    regDocumentsForm.validate();
    $('#' + fileInputId).rules('remove');

    $(this).parent('div').parent('div').remove('div');
}

/**
 * Get registration document
 * @param url
 * @param docId
 * @param labelId
 */
function getRegDocument(url, docId, labelId){
    axios.get(url, {
        params: {
            id: docId
        }
    }).then(function (response){
        if(response.data.success){
            regDocsLoader.hide();
            let name = response.data.document.document_name;
            let desc = response.data.document.document_desc;
            $("label[for='" + labelId + "']").text(name);
            $('#' + labelId + '-desc').html(desc);
        }else{
            regDocsLoader.hide();
            regDocsErrorDisplay.html(response.data.message)
            regDocsErrorDisplay.show();
        }
    }).catch(function (error){
        regDocsLoader.hide();
        regDocsErrorDisplay.html(error.message)
        regDocsErrorDisplay.show();
    });
}