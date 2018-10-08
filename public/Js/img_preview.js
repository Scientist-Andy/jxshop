
function getObjectUrl(file) {
    var url = null;
    if (window.createObjectURL != undefined) {
        url = window.createObjectURL(file)
    } else if (window.URL != undefined) {
        url = window.URL.createObjectURL(file)
    } else if (window.webkitURL != undefined) {
        url = window.webkitURL.createObjectURL(file)
    }
    return url
}


$(".preview").change(function(){

    // 获取选择的图片
    var file = this.files[0];
    var str = getObjectUrl(file);

    $(this).prev('.img_preview').remove();
    // 在框前面放一个图片
    $(this).before("<div class='img_preview'><img src='"+str+"' width='120' height='120'></div>");

});