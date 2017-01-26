var url = "http://localhost:10000/php/getStud.php";
var studentArray = [];

$(document).ready(function () {
    loadData();
    $('#tableBody').on('click', 'td', function () {
        if ($(this).css('color') === 'rgb(255, 255, 255)') {
            $(this).css('color', 'black');
        } else {
            $(this).css('color', 'white');
        }
    });
});

function updateView() {
    $("#tableBody").html("");
    studentArray.forEach(function (student) {
        createTRHtml(student);
    });
}

function loadData() {
    $.ajax({
        method: "GET",
        url: url,
        dataType: "json",
        success: function (data) {
            studentArray = data;
            updateView();
        },
    });
}

function createTRHtml(student) {
    var offerHtml = "<tr>" +
        "<td>" + student.id + "</td>" +
        "<td>" + student.first_name + "</td>" +
        "<td>" + student.last_name + "</td>" +
        "<td>" + student.CPR + "</td>" +
        "<td>" + student.date_of_birth + "</td>" +
        "<td>" + student.email + "</td>" +
        "<td>" + student.institution_name + "</td>" +
        "<td>" + student.address.street + "</td>" +
        "<td>" + student.address.number + "</td>" +
        "<td>" + student.address.zip_code + "</td>" +
        "<td>" + student.address.city + "</td>" +
        "</tr>";
    $("#tableBody").append(offerHtml)
}
