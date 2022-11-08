const regDocsLoader = $('#reg-documents-form > .loader');
regDocsLoader.html(loader);
// regDocsLoader.hide();

regDocsLoader.show();


const regDocsErrorDisplay =  $('#reg-documents-form > .error-display');
regDocsErrorDisplay.hide();

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
    }
    $(this).val('');
}

function createDocumentField(labelId){
    return `
    <div class="row">
        <div class="col-12">
            <button class="btn btn-danger remove-reg-document float-right">Remove document</button>
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

function removeRegDocument(e){
    e.preventDefault();
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
            // regDocsLoader.hide();
            let name = response.data.document.document_name;
            let desc = response.data.document.document_desc;
            $("label[for='" + labelId + "']").text(name);
            $('#' + labelId + '-desc').html(desc);
        }else{
            // regDocsLoader.hide();
            regDocsErrorDisplay.html(response.data.message)
            regDocsErrorDisplay.show();
        }
    }).catch(function (error){
        regDocsLoader.hide();
        regDocsErrorDisplay.html(error.message)
        regDocsErrorDisplay.show();
    });
}