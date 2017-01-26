var url = "http://localhost:10000/php/getStud.php";
var studentArray = [];

$(document).ready(function () {
    loadData();
    $('#tableBody').on('click', 'td', function () {
        if ($(this).attr('data') == '') {
            $(this).attr('data', $(this).val());
            $(this).val('hidden');
        } else {
            $(this).val($(this).attr('data'));
            $(this).attr('data', '');
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
        "<td data=''>" + student.id + "</td>" +
        "<td data=''>" + student.first_name + "</td>" +
        "<td data=''>" + student.last_name + "</td>" +
        "<td data=''>" + student.CPR + "</td>" +
        "<td data=''>" + student.date_of_birth + "</td>" +
        "<td data=''>" + student.email + "</td>" +
        "<td data=''>" + student.institution_name + "</td>" +
        "<td data=''>" + student.address.street + "</td>" +
        "<td data=''>" + student.address.number + "</td>" +
        "<td data=''>" + student.address.zip_code + "</td>" +
        "<td data=''>" + student.address.city + "</td>" +
        "</tr>";
    $("#tableBody").append(offerHtml)
}
