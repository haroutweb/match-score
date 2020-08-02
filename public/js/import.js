$(function () {
    // import dropzone
    Dropzone.autoDiscover = false;
    let allMembersTable     = $('#all-members');
    let averageTable        = $('#average-members');
    let form                = $('#employee-form');
    let dropzoneContent     = $('#importDropzone');
    let errorContainer      = $('.form-error');

    dropzoneContent.dropzone({
        url: form.data('upload'),
        dictDefaultMessage: 'Drop files here to upload',
        parallelUploads: 1,
        maxFilesize: 50, // MB
        maxFiles:1,
        acceptedFiles: '.xls',
        success: function (file, response) {
            response = JSON.parse(response);

            if (response.status == 'success') {
                // add remove button
                let image        = Dropzone.createElement('<img src="'+ response.icon +'" alt="xls icon" />');
                let fileName     = Dropzone.createElement('<span>'+ response.fileName +'</span>');
                let removeButton = Dropzone.createElement('<button data-dz-remove data-url="'+ response.fileName +'" class="remove-file btn btn-danger">X</button>');
                let _this        = this;

                // init list
                let memberList = '';
                for (let member of response.members) {
                    memberList += '<tr>' +
                                        '<td>'+ member.A +'</td>' +
                                        '<td>'+ member.B +'</td>' +
                                        '<td>'+ member.C +'</td>' +
                                        '<td>'+ member.D +'</td>' +
                                        '<td>'+ member.E +'</td>' +
                                    '</tr>'
                }

                allMembersTable.find('tbody').html(memberList);

                // init average couples
                let couples = '<tr><td>Average score is ' + response.average + '</td></tr>';
                for (let i = 0; i < response.topAverageCouples.length; i++) {
                    couples += '<tr>' +
                                    '<td>' + response.topAverageCouples[i] + '</td>' +
                                '</tr>';
                }

                averageTable.find('tbody').html(couples);

                removeButton.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    _this.removeFile(file);
                    errorContainer.html('');
                    allMembersTable.find('tbody').html('');
                    averageTable.find('tbody').html('');
                });

                file.previewElement.innerHTML = '';
                file.previewElement.appendChild(removeButton);
                file.previewElement.appendChild(image);
                file.previewElement.appendChild(fileName);
            } else {
                this.removeFile(file);
                errorContainer.html('<span>'+ response.message +'</span>');
                allMembersTable.find('tbody').html('');
                averageTable.find('tbody').html('');
            }
        },
        init: function () {
            this.on("maxfilesexceeded", function(file) {
                this.removeFile(file);
                errorContainer.html('<span>File max size should be less or than 50 MB</span>');
            });

            this.on("error", function(file, response) {
                this.removeFile(file);
                errorContainer.html('<span>Invalid file extension</span>');
            });
        }
    });
});