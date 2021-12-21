var path = 'http://localhost/paint.com/';
function sweetAlert(type, message) {
    Swal.fire({
        position: 'center',
        type: type,
        title: message,
        showConfirmButton: true
    });
}

// var href = window.location.href;
// if(href==path+"admin/"||href==path+"admin/home"){
//     $("#logoImg").attr('src', '../img/core-img/logo.png');
// }

function validateUpload() {
    var fuData = document.getElementById('file');
    var FileUploadPath = fuData.value;

    //To check if user upload any file
    if (FileUploadPath == '') {
        sweetAlert("warning", "Please upload an image");
    } else {
        var Extension = FileUploadPath.substring(
            FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

        //The file uploaded is an image
        if (Extension == "gif" || Extension == "png" || Extension == "bmp" ||
            Extension == "jpeg" || Extension == "jpg") {

            //send to DB
            var fd = new FormData();
            var files = $('#file')[0].files[0];
            fd.append('file', files);
            $.ajax({
                url: path + 'inc.files/process_script?mode=profilePhoto',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#profile_picture_msg").html(response);
                },
            });
        }
        //The file upload is NOT an image
        else {
            sweetAlert("warning", "Photo only allows file types of GIF, PNG, JPG, JPEG and BMP. ");
        }
    }
}

function validateUploadA() {
    var fuData = document.getElementById('file');
    var FileUploadPath = fuData.value;

    //To check if user upload any file
    if (FileUploadPath == '') {
        sweetAlert("warning", "Please upload an image");
    } else {
        var Extension = FileUploadPath.substring(
            FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

        //The file uploaded is an image
        if (Extension == "gif" || Extension == "png" || Extension == "bmp" ||
            Extension == "jpeg" || Extension == "jpg") {

            //send to DB
            var fd = new FormData();
            var files = $('#file')[0].files[0];
            fd.append('file', files);
            $.ajax({
                url: path + 'inc.files/process_script?mode=profilePhotoA',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#profile_picture_msg").html(response);
                    // To Display
                    // if (fuData.files && fuData.files[0]) {
                    //     var reader = new FileReader();

                    //     reader.onload = function(e) {
                    //         $('#file').attr('src', e.target.result);
                    //     }

                    //     reader.readAsDataURL(fuData.files[0]);
                    // }
                },
            });
        }
        //The file upload is NOT an image
        else {
            sweetAlert("warning", "Photo only allows file types of GIF, PNG, JPG, JPEG and BMP. ");
        }
    }
}

function validateUploadG() {
    var fuData = document.getElementById('file');
    var FileUploadPath = fuData.value;

    //To check if user upload any file
    if (FileUploadPath == '') {
        sweetAlert("warning", "Please upload an image");
    } else {
        var Extension = FileUploadPath.substring(
            FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

        //The file uploaded is an image
        if (Extension == "gif" || Extension == "png" || Extension == "bmp" ||
            Extension == "jpeg" || Extension == "jpg") {

            //send to DB
            var fd = new FormData();
            var files = $('#file')[0].files[0];
            fd.append('file', files);
            $.ajax({
                url: path + 'inc.files/process_script?mode=profilePhotoG',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#profile_picture_msg").html(response);
                },
            });
        }
        //The file upload is NOT an image
        else {
            sweetAlert("warning", "Photo only allows file types of GIF, PNG, JPG, JPEG and BMP. ");
        }
    }
}

$(document).ready(function () {

    /* student register sending function */
    $('#regForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#regForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=regForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* student & staff login sending function */
    $('#logForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#logForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=logForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* user check security sending function */
    $('#checkForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#checkForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=checkForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* admin set question sending function */
    $('#testExamForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#testExamForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=testExamForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* admin update question sending function */
    $('#updateTestExamForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#updateTestExamForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=updateTestExamForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* admin set assessment sending function */
    $('#assessmentForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#assessmentForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=assessmentForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* user take test/exam form sending function */
    $('#startTestExamForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#startTestExamForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=startTestExamForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* user submit assessment sending function */
    $('.assessmentAnswerForm').submit(function (e) {
        thisValue = $(this);
        $(this).find('#smtBtn').attr('disabled', 'disabled');
        $(this).find('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $(this).find(':input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=assessmentAnswerForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* user submit assessment sending function */
    $('.assessmentAnswerCaForm').submit(function (e) {
        thisValue = $(this);
        $(this).find('#smtBtn').attr('disabled', 'disabled');
        $(this).find('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $(this).find(':input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=assessmentAnswerCaForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* admin update assessment sending function */
    $('#updateAssessmentForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#updateAssessmentForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=updateAssessmentForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* user preview results sending function */
    jQuery(document).on('click', '.preview', function (e) {
        btnId = $(this).attr('btnId');
        byepass = 'reklawodahs';
        $.post(path + 'inc.files/process_script?mode=preview', { btnId: btnId, byepass: byepass }, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* admin view students details sending function */
    jQuery(document).on('click', '.viewStudent', function (e) {
        btnId = $(this).attr('btnId');
        subject = $(this).attr('subject');
        window.location = path + 'admin/dashboard/view_student?btnId=' + btnId + '&subject=' + subject;
        return false;
    });

    /* parent view students performance click sending function */
    jQuery(document).on('click', '.viewStudentPerformance', function (e) {
        btnId = $(this).attr('btnId');
        $("#user_id").val(btnId);
        return false;
    });

    /* admin edit listing sending function */
    jQuery(document).on('click', '.editProduct', function (e) {
        btnId = $(this).attr('btnId');
        self.location = path + "admin/dashboard/edit_product?btnId=" + btnId;
        return false;
    });

    /* logout user sending function */
    $('.logOutUser').click(function (e) {
        $.post(path + 'inc.files/process_script?mode=logOutUser', function (data) {
            $('.logOutSpan').html(data);
        });
        return false;
    });

    /* logout admin sending function */
    $('.logOutAdmin').click(function (e) {
        $.post(path + 'inc.files/process_script?mode=logOutAdmin', function (data) {
            $('.logOutSpan').html(data);
        });
        return false;
    });

    /* logout admin sending function */
    $('.logOutGuardian').click(function (e) {
        $.post(path + 'inc.files/process_script?mode=logOutGuardian', function (data) {
            $('.logOutSpan').html(data);
        });
        return false;
    });

    /* user send reset password us function */
    $('#resetForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#resetForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=resetForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* view performance form submit function */
    $('#viewPerformnceForm').submit(function (e) {
        $('#smtBtnPerforance').attr('disabled', 'disabled');
        $('#smtBtnPerforance').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#viewPerformnceForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=viewPerformnceForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* manage system form submit function */
    $('#manageSystemForm').submit(function (e) {
        $('#smtBtnPerforance').attr('disabled', 'disabled');
        $('#smtBtnPerforance').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#manageSystemForm :input').serializeArray();
        var returnValue = confirm("Are you sure?");
        if (returnValue == true) {
            $.post(path + 'inc.files/process_script?mode=manageSystemForm', values, function (data) {
                $('#formSpan').html(data);
            });
        }
        return false;
    });

    $("#nextGrade").click(function (params) {
        var returnValue = confirm("Are you sure?");
        if (returnValue == true) {
            $.post(path + 'inc.files/process_script?mode=nextGrade', { returnValue: returnValue }, function (data) {
                $('#formSpan').html(data);
            });
        }
        return false;
    });

    $("#profilePicture").click(function (params) {
        $("#file").trigger("click");
    });

    /* user update profile function */
    $('#profileForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#profileForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=profileForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* user add project function */
    $('#projectForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#projectForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=projectForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* admin add product function */
    $('#addproductForm').submit(function (e) {
        e.preventDefault();
        $('#progressBar').attr('aria-valuenow', 0).css('width', 0 + '%').text(0 + '%');

        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#addproductForm :input').serializeArray();
        var returnValue = confirm("Are you sure?");
        if (returnValue == true) {
            var fd = new FormData();
            var fileSizeSum = 0;
            if ($('#file')[0].files.length < 1) {
                sweetAlert("warning", "No file selected!");
                $('#smtBtn').removeAttr('disabled');
                $('#smtBtn').html('Add Product <i class="fas fa-sign-in-alt"></i>');
                return false;
            }

            for (let index = 0; index < $('#file')[0].files.length; index++) {
                fileSizeSum += $('#file')[0].files[index]['size'];
                if ($('#file')[0].files[index]['size'] > 40000000) {
                    sweetAlert("warning", "Cannot upload file more than 40MB size!");
                    $('#smtBtn').removeAttr('disabled');
                    $('#smtBtn').html('Add Product <i class="fas fa-sign-in-alt"></i>');
                    return false;
                }
                const element = $('#file')[0].files[index];
                const name = element.name;
                var Extension = name.substring(name.lastIndexOf('.') + 1).toLowerCase();
                if (Extension == "jpg" || Extension == "jpeg" || Extension == "png" || Extension == "tiff" || Extension == "gif" || Extension == "bmp") {
                    // valid
                    var files = $('#file')[0].files[index];
                    fd.append('file_' + index, files);
                } else {
                    sweetAlert("warning", "Wrong file type detected, must be an image file!");
                    $('#smtBtn').removeAttr('disabled');
                    $('#smtBtn').html('Add Product <i class="fas fa-sign-in-alt"></i>');
                    return false;
                }

                if (fileSizeSum > 40000000) {
                    sweetAlert("warning", "Cannot upload file(s) more than 40MB size!");
                    $('#smtBtn').removeAttr('disabled');
                    $('#smtBtn').html('Add Product <i class="fas fa-sign-in-alt"></i>');
                    return false;
                }
            }

            for (var i = 0; i < values.length; i++) {
                fd.append(values[i].name, values[i].value);
            }
            $.ajax({
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();

                    xhr.upload.addEventListener('progress', function (e) {

                        if (e.lengthComputable) {

                            console.log('Bytes Loaded: ' + e.loaded);
                            console.log('Total Size: ' + e.total);
                            console.log('Percentage Uploaded: ' + (e.loaded / e.total))

                            var percent = Math.round((e.loaded / e.total) * 100);

                            $('#progressBar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');

                        }

                    });

                    return xhr;
                },
                url: path + 'inc.files/process_script?mode=addproductForm',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#formSpan').html(data);
                },
            });
        } else {
            $('#smtBtn').removeAttr('disabled');
            $('#smtBtn').html('Add Product <i class="fas fa-sign-in-alt"></i>');
        }
        return false;
    });

    /* admin update product function */
    $('#updateProductForm').submit(function (e) {
        e.preventDefault();
        $('#progressBar').attr('aria-valuenow', 0).css('width', 0 + '%').text(0 + '%');

        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#updateProductForm :input').serializeArray();
        var returnValue = confirm("Are you sure?");
        if (returnValue == true) {
            var fd = new FormData();
            if ($('#file')[0].files.length < 1) {
                files = false;
                fd.append("files", files);
            }

            var fileSizeSum = 0;
            for (let index = 0; index < $('#file')[0].files.length; index++) {
                fileSizeSum += $('#file')[0].files[index]['size'];
                if ($('#file')[0].files[index]['size'] > 40000000) {
                    sweetAlert("warning", "Cannot upload file more than 40MB size!");
                    $('#smtBtn').removeAttr('disabled');
                    $('#smtBtn').html('Update Product <i class="fas fa-sign-in-alt"></i>');
                    return false;
                }
                const element = $('#file')[0].files[index];
                const name = element.name;
                var Extension = name.substring(name.lastIndexOf('.') + 1).toLowerCase();
                if (Extension == "jpg" || Extension == "jpeg" || Extension == "png" || Extension == "tiff" || Extension == "gif" || Extension == "bmp") {
                    // valid
                    var files = $('#file')[0].files[index];
                    fd.append('file_' + index, files);
                } else {
                    sweetAlert("warning", "Wrong file type detected, must be an image file!");
                    $('#smtBtn').removeAttr('disabled');
                    $('#smtBtn').html('Update Product <i class="fas fa-sign-in-alt"></i>');
                    return false;
                }

                if (fileSizeSum > 40000000) {
                    sweetAlert("warning", "Cannot upload file(s) more than 40MB size!");
                    $('#smtBtn').removeAttr('disabled');
                    $('#smtBtn').html('Update Product <i class="fas fa-sign-in-alt"></i>');
                    return false;
                }
            }

            for (var i = 0; i < values.length; i++) {
                fd.append(values[i].name, values[i].value);
            }
            $.ajax({
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();

                    xhr.upload.addEventListener('progress', function (e) {

                        if (e.lengthComputable) {

                            console.log('Bytes Loaded: ' + e.loaded);
                            console.log('Total Size: ' + e.total);
                            console.log('Percentage Uploaded: ' + (e.loaded / e.total))

                            var percent = Math.round((e.loaded / e.total) * 100);

                            $('#progressBar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');

                        }

                    });

                    return xhr;
                },
                url: path + 'inc.files/process_script?mode=updateProductForm',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#formSpan').html(data);
                },
            });
        } else {
            $('#smtBtn').removeAttr('disabled');
            $('#smtBtn').html('Update Product <i class="fas fa-sign-in-alt"></i>');
        }
        return false;
    });

    /* user update profile function */
    $('#complaintForm').submit(function (e) {
        e.preventDefault();

        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#complaintForm :input').serializeArray();

        var fd = new FormData();
        var fileSizeSum = 0;

        for (let index = 0; index < $('#file')[0].files.length; index++) {
            fileSizeSum += $('#file')[0].files[index]['size'];

            const element = $('#file')[0].files[index];
            const name = element.name;
            var Extension = name.substring(name.lastIndexOf('.') + 1).toLowerCase();
            if ((Extension == "webm" || Extension == "mpg" || Extension == "mp2" || Extension == "mpeg" || Extension == "mpe" || Extension == "mpv" || Extension == "ogg" || Extension == "mp4" || Extension == "m4p" || Extension == "m4v" || Extension == "avi" || Extension == "wmv" || Extension == "mov" || Extension == "qt" || Extension == "flv" || Extension == "swf" || Extension == "avchd")) {
                sweetAlert("warning", "Invalid file type selected, must not contain a video file!");
                $('#smtBtn').removeAttr('disabled');
                $('#smtBtn').html('Submit <i class="fa fa-sign-in"></i>');
                return false;
            }
            var files = $('#file')[0].files[index];
            fd.append('file_' + index, files);
        }

        if (fileSizeSum > 40000000) {
            sweetAlert("warning", "Cannot upload files more than 40MB size!");
            $('#smtBtn').removeAttr('disabled');
            $('#smtBtn').html('Submit <i class="fa fa-sign-in"></i>');
            return false;
        }

        for (var i = 0; i < values.length; i++) {
            fd.append(values[i].name, values[i].value);
        }

        // sleep for 1sec and start job...
        setTimeout(() => {
            $.ajax({
                url: path + 'inc.files/process_script?mode=complaintForm',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#formSpan').html(data);
                },
            });
        }, 1000);

        return false;
    });

    /* user update password function */
    $('#updatePassword').submit(function (e) {
        $('#smtBtnPass').attr('disabled', 'disabled');
        $('#smtBtnPass').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#updatePassword :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=updatePassword', values, function (data) {
            $('#formSpanCPASS').html(data);
        });
        return false;
    });

    /* staff update password function */
    $('#adminupdatePassword').submit(function (e) {
        $('#smtBtnPass').attr('disabled', 'disabled');
        $('#smtBtnPass').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#adminupdatePassword :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=adminupdatePassword', values, function (data) {
            $('#formSpanCPASS').html(data);
        });
        return false;
    });

    /* user send contact us function */
    $('#contactForm').submit(function (e) {
        e.preventDefault();
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#contactForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=contactForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* user send add testimony function */
    $('#addTForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#addTForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=addTForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* ctr login sending function */
    $('#adinlogForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#adinlogForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=adinlogForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* ctr manage users sending function */
    jQuery(document).on('click', '.activateUser', function (e) {
        e.preventDefault();
        btnId = $(this).attr('btnId');
        byepass = 'reklawodahs';
        $.post(path + 'inc.files/process_script?mode=activateUser', { btnId: btnId, byepass: byepass }, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* ctr manage users sending function */
    jQuery(document).on('click', '.view-complaint', function (e) {
        e.preventDefault();
        btnId = $(this).attr('btnId');
        $('.loadMyComplaints').html('');
        $('.loadMyComplaints').load("../inc.files/view_complaint?btnId=" + btnId);
        return false;
    });

    /* ctr manage users sending function */
    jQuery(document).on('click', '.deactivateUser', function (e) {
        e.preventDefault();
        btnId = $(this).attr('btnId');
        byepass = 'reklawodahs';
        $.post(path + 'inc.files/process_script?mode=deactivateUser', { btnId: btnId, byepass: byepass }, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* ctr activate project sending function */
    jQuery(document).on('click', '.activateProduct', function (e) {
        e.preventDefault();
        btnId = $(this).attr('btnId');
        byepass = 'reklawodahs';
        $.post(path + 'inc.files/process_script?mode=activateProduct', { btnId: btnId, byepass: byepass }, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* ctr deactivate project sending function */
    jQuery(document).on('click', '.deactivateProduct', function (e) {
        e.preventDefault();
        btnId = $(this).attr('btnId');
        byepass = 'reklawodahs';
        $.post(path + 'inc.files/process_script?mode=deactivateProduct', { btnId: btnId, byepass: byepass }, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* ctr activate assessment sending function */
    jQuery(document).on('click', '.activateAssessment', function (e) {
        e.preventDefault();
        btnId = $(this).attr('btnId');
        byepass = 'reklawodahs';
        $.post(path + 'inc.files/process_script?mode=activateAssessment', { btnId: btnId, byepass: byepass }, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* ctr deactivate assessment sending function */
    jQuery(document).on('click', '.deactivateAssessment', function (e) {
        e.preventDefault();
        btnId = $(this).attr('btnId');
        byepass = 'reklawodahs';
        $.post(path + 'inc.files/process_script?mode=deactivateAssessment', { btnId: btnId, byepass: byepass }, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* admin update profile function */
    $('#adminprofileForm').submit(function (e) {
        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#adminprofileForm :input').serializeArray();
        $.post(path + 'inc.files/process_script?mode=adminprofileForm', values, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* ctr add blog sending function */
    $('#uploadForm').submit(function (e) {
        e.preventDefault();

        $('#smtBtn').attr('disabled', 'disabled');
        $('#smtBtn').html('<center>Processing... <div class="spinner-border spinner-border-sm" role="status"></div></center>');
        values = $('#uploadForm :input').serializeArray();
        var fd = new FormData();
        if ($('#file')[0].files.length < 1) {
            sweetAlert("warning", "No file selected!");
            $('#smtBtn').removeAttr('disabled');
            $('#smtBtn').html('Upload <i class="fa fa-upload"></i>');
            return false;
        }

        for (let index = 0; index < $('#file')[0].files.length; index++) {
            if ($('#file')[0].files[index]['size'] > 40000000) {
                sweetAlert("warning", "Cannot upload video more than 40MB size!");
                $('#smtBtn').removeAttr('disabled');
                $('#smtBtn').html('Upload <i class="fa fa-upload"></i>');
                return false;
            }
            const element = $('#file')[0].files[index];
            const name = element.name;
            var Extension = name.substring(name.lastIndexOf('.') + 1).toLowerCase();
            // category = $('#category').val();
            if (!(Extension == "webm" || Extension == "mpg" || Extension == "mp2" || Extension == "mpeg" || Extension == "mpe" || Extension == "mpv" || Extension == "ogg" || Extension == "mp4" || Extension == "m4p" || Extension == "m4v" || Extension == "avi" || Extension == "wmv" || Extension == "mov" || Extension == "qt" || Extension == "flv" || Extension == "swf" || Extension == "avchd")) {
                sweetAlert("warning", "Invalid file type selected, must be a video file!");
                $('#smtBtn').removeAttr('disabled');
                $('#smtBtn').html('Upload <i class="fa fa-upload"></i>');
                return false;
            }
            var files = $('#file')[0].files[index];
            fd.append('file_' + index, files);
        }

        for (var i = 0; i < values.length; i++) {
            fd.append(values[i].name, values[i].value);
        }

        // sleep for 1sec and start job...
        setTimeout(() => {
            $.ajax({
                url: path + 'inc.files/process_script?mode=uploadForm',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#formSpan').html(data);
                },
            });
        }, 1000);

        return false;
    });

    /* ctr manage blog sending function */
    jQuery(document).on('click', '.activateBlog', function (e) {
        btnId = $(this).attr('btnId');
        byepass = 'reklawodahs';
        $.post(path + 'inc.files/process_script?mode=activateBlog', { btnId: btnId, byepass: byepass }, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    /* ctr manage blog sending function */
    jQuery(document).on('click', '.deactivateBlog', function (e) {
        btnId = $(this).attr('btnId');
        byepass = 'reklawodahs';
        $.post(path + 'inc.files/process_script?mode=deactivateBlog', { btnId: btnId, byepass: byepass }, function (data) {
            $('#formSpan').html(data);
        });
        return false;
    });

    jQuery(document).on('click', '.toggler', function (params) {
        if ($('.sidebar').hasClass('showSideBar')) {
            $('.sidebar').addClass('removeSideBar');
            $('.sidebar').removeClass('showSideBar');
            $('.col-sm-9').css('flex', '0 0 100%');
            $('.col-sm-9').css('max-width', '100%');
        } else {
            $('.sidebar').addClass('showSideBar');
            $('.sidebar').removeClass('removeSideBar');
            $('.col-sm-9').css('flex', '0 0 75%');
            $('.col-sm-9').css('max-width', '75%');
        }
    })
});