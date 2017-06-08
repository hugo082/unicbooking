$( function() {
    $( "#appbundle_book_date" ).datepicker({
        numberOfMonths: 3,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd'
    });
    $( "#appbundle_book_timepu" ).timepicker({
        defaultTime: false,
        minuteStep: 5,
        showMeridian: false,
    });
} );

function CheckRequired(event) {
    var $form = $(this);
    if ($form.find(':input[required],select[required],textarea[required]').filter(function(){ return this.value === '' }).length > 0) {
        event.preventDefault();
        alert("One or more fields required are blank");
    }
}

$(document).ready(function () {
    $('form').on('submit', CheckRequired);
});

function getMaxB() { // Max Bag
    return ($("select#appbundle_book_product").val() != 3) ? 999 : 10;
}
/*
function getMaxP() { // Max Passengers
    return ($("select#appbundle_book_product").val() != 3) ? 999 : 6;
}
function getNbP() {
    var child = ($('#appbundle_book_childcus').val() == "") ? 0 : parseInt($('#appbundle_book_childcus').val());
    var adult = ($('#appbundle_book_adultcus').val() == "") ? 0 : parseInt($('#appbundle_book_adultcus').val());
    return child + adult;
}
*/
function updateFlightSelection(){
    var sval = $("select#appbundle_book_service").val();
    var sval = $("select#appbundle_book_service").val();
    aval = $("select#appbundle_book_airport").val();
    updateFlightTransitVisibility(true);
    $("#appbundle_book_flight").val('');
}
function updateFlightTransitVisibility(updateChoice){
    var sval = $("select#appbundle_book_service").val();
    if (sval != "TRA")
        $("#flight_transit_bloc").addClass("hidden");
    else {
        $("#flight_transit_bloc").removeClass("hidden");
        if (updateChoice)
            updateFlightTransitSelection();
    }
}
function updateAddressVisibily(){
    var trspt = $('select#appbundle_book_product option:selected').attr('trspt');
    var cval = $("select#appbundle_book_service").val();
    if (!trspt || cval == "TRA") {
        $("#address").addClass("hidden");
        $("#appbundle_book_addressdo").removeAttr("required");
        $("#appbundle_book_addresspu").removeAttr("required");
        $("#appbundle_book_addressdo").removeAttr("readonly");
        $("#appbundle_book_addresspu").removeAttr("readonly");
    } else {
        $("#address").removeClass("hidden");
        updateAddress();
    }
}
function updateAddress(){
    var cval = $("select#appbundle_book_service").val();
    if (cval == "ARR") {
        $("#appbundle_book_addresspu").prop("readonly", "true");
        $("#appbundle_book_addresspu").val("CDG Airport Terminal 1 - Gate 8");
        $("#appbundle_book_addressdo").removeAttr("readonly");
        // $("#appbundle_book_addressdo").val("");
        $("#appbundle_book_addressdo").prop("required", "required");
        $("#addresse_timepu").addClass("hidden");
    } else if (cval == "DEP") {
        $("#appbundle_book_addresspu").removeAttr("readonly");
        // $("#appbundle_book_addresspu").val("");
        $("#appbundle_book_addresspu").prop("required", "required");
        $("#appbundle_book_addressdo").val("CDG Airport Terminal 1 - Gate 28");
        $("#appbundle_book_addressdo").prop("readonly", "true");
        $("#addresse_timepu").removeClass("hidden");
    }
}

/*
$("#appbundle_book_adultcus").on('change', function() {
    var maxP = getMaxP();
    if (this.value > maxP) {
        $(this).val(maxP);
    }
    var ch = parseInt($('#appbundle_book_childcus').val());
    var ad = parseInt($(this).val());
    if (ch + ad > maxP) {
        $("#appbundle_book_childcus").val(maxP - ad);
    }
});
$("#appbundle_book_childcus").on('change', function() {
    var maxP = getMaxP();
    if (this.value > maxP) {
        $(this).val(maxP);
    }
    var ch = parseInt($('#appbundle_book_adultcus').val());
    var ad = parseInt($(this).val());
    if (ch + ad > maxP) {
        $("#appbundle_book_adultcus").val(maxP - ad);
    }
});
*/

$("select#appbundle_book_service").on('change', function(){
    updateFlightSelection();
    updateAddressVisibily();
});
$("select#appbundle_book_airport").on('change', function(){
    updateFlightSelection();
});

$("select#appbundle_book_product").on('change', function(){
    updateAddressVisibily();
});
$("#appbundle_book_bags").on('change', function(){
    var maxB = getMaxB();
    if (this.value > maxB) {
        $(this).val(maxB);
    }
});

updateAddressVisibily();
updateFlightTransitVisibility(false);
