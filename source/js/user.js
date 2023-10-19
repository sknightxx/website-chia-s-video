$(".sidebar ul li").on('click', function() {
    $(".sidebar ul li.active").removeClass("active")
    $(this).addClass("active")
})
$('.open-btn').on('click', function() {
    $('.sidebar').addClass('active')
    $('.sidebar').removeClass('deactive')
    $('.nav-header').addClass('d-none')
})
$('.close-btn').on('click', function() {
    $('.sidebar').removeClass('active')
    $('.sidebar').addClass('deactive')
    $('.nav-header').removeClass('d-none')
})

$(window).change(function() {
    if($(window).width() <= 767) {
        $('.sidebar').removeClass('active')
        $('.sidebar').addClass('deactive')
        $('.nav-header').removeClass('d-none')
    }
    else {
        $('.sidebar').addClass('active')
        $('.sidebar').removeClass('deactive')
        $('.nav-header').addClass('d-none')
    }
})
$('#upload').on('click', function() {
    $(".content > .container").remove()
    let upload = `<div class="upload container d-flex justify-content-center align-content-center">
                    <form action="" class="pt-4">
                        <select name="video-upload" id="video-upload">
                            <option disabled selected value>Chọn cách đăng video</option>
                            <option value="youtube">Link youtube</option>
                            <option value="file">Chọn file</option>
                        </select>

                    </form>
                </div>`
    $(".content").append(upload)
    $('#video-upload').change(function() {
        $(this).next('form').remove()
        if($('#video-upload').val() == 'file') {
            let input = `<form method="post" id="uploadform" class="pt-4">
                            <div class="input-video">
                                <label for="title">Tiêu đề</label>
                                <input type="text" name="title" class="form-control" id="title">
                                <label for="title">Mô tả</label>
                                <input type="text" name="desc" class="form-control" id="desc">
                                <label for="video">Chọn file video</label>
                                <input type="file" name="video" class="form-control" id="video">
                                <label for="thumbnail">Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control" id="thumbnail">
                                <button type="submit" class="btn btn-success my-4">Upload</button>
                                <button type="reset" class="btn btn-secondary" id="resetbtn">Reset</button>
                                <p id="msg"></p>
                            </div>
                        </form>`
            $('#video-upload').after(input)
            $('#uploadform').submit(function(event) {
                event.preventDefault()
                $('#msg').text('')
                $('#msg').removeClass('text-danger')
                //check input
                let title = $('#title').val()
                let fileExtension = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'f4v', 'smf', 'mkv', 'webm']
                let imgExtension = ["gif", "jpeg", "png"];
                let thumbnail = $("#thumbnail").prop('files')
                let uid = $("#upload").attr('uid')
                let type = thumbnail[0].name.split('.').pop().toLowerCase()
                if(title == '') {
                    $('#msg').text('Vui lòng nhập tiêu đề').addClass('text-danger')
                    return
                }
                //check file validate
                if($('#video').get(0).files.length === 0) {
                    $('#msg').text('Vui lòng chọn file video').addClass('text-danger')
                    return
                }
                if($.inArray($('#video').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                    $('#msg').text('File video không hợp lệ').addClass('text-danger')
                    return 
                }

                if ($.inArray(type, imgExtension) < 0) {
                    $('#msg').text('File hình không hợp lệ').addClass('text-danger')
                    return
                }
                let form = new FormData(this)
                $.ajax({
                    method : 'POST',
                    url : '../source/api/VideoController.php?action=upload&uid=' + uid,
                    processData : false,
                    contentType: false,
                    data : form
                }).done(function(data) {
                    console.log(data)
                    data = JSON.parse(data)
                    if(data.status) {
                        $("#resetbtn").click()
                        $('#msg').text(data.messege).addClass('text-success')
                    }
                }).fail(function(data) {
                    console.log(data)
                    $('#msg').text(data.messege).addClass('text-danger')
                })
            })
        }
        else if($('#video-upload').val() == 'youtube') {
            let input = `<form action="" id="uploadform" class="pt-4">
                            <div class="input-video">
                                <label for="url">Đường dẫn</label>
                                <input type="text" name="url" class="form-control" id="url">
                                <button type="submit" class="btn btn-success my-4">Upload</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <p id="msg"></p>
                            </div>
                        </form>`
            $('#video-upload').after(input)
        }
    })

})



