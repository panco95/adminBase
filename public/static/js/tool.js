function post(url, data, success) {
    $.ajax({
        url: url,
        type: "post",
        dataType: "json",
        data: data,
        success: function (res) {
            console.log(res);
            if (parseInt(res.code) === 200) {
                success(res.msg, res.data);
            } else if (parseInt(res.code) === 500) {
                layer.msg(res.msg, {icon: 2})
            }
        }
    });
}
