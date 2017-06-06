$( function() {
    function ApiChecker(input, hidden) {
        this.inputId = input;
        this.hiddenInputId = hidden;
        this.flightModal = "#flightOaciModal";
        this.flightModal_cancel = this.flightModal + "_cancel";
        this.flightModal_origin = this.flightModal + "_origin";
        this.flightModal_departure_time = this.flightModal + "_departure_time";
        this.flightModal_destination = this.flightModal + "_destination";
        this.flightModal_arrival_time = this.flightModal + "_arrival_time";
        this.loadFlight = function () {
            var code = $(this.inputId).val();
            if (typeof code === "undefined" || code === "")
                return;
            var checker = this;
            $.ajax({
                type: 'GET',
                url: "http://booking.unicairport.com/app.php/data/api/flight",
                data: { 'flight_code': code },
                success : function(result) {
                    console.log(result);
                    if (result.status.code != 200) {
                        checker.internalError(result.status);
                        return;
                    }
                    $(checker.hiddenInputId).val(result.flight.id);
                    checker.displayModal(result.flight);
                }
            });
        };
        this.displayModal = function (flight) {
            var departuretime = new Date(1000 * flight.origin_time),
                arrivaltime = new Date(1000 * flight.destination_time);
            $(this.flightModal).modal('show');
            $(this.flightModal_origin).text("Origin : " + flight.origin.name);
            $(this.flightModal_departure_time).text("Departure time : " + departuretime.toLocaleTimeString());
            $(this.flightModal_destination).text("Destination : " + flight.destination.name);
            $(this.flightModal_arrival_time).text("Arrival time : " + arrivaltime.toLocaleTimeString());
        };
        this.internalError = function (data) {
            alert('Error : ' + data.message);
            $(this.inputId).val("");
        };
        this.cancel = function () {
            $(this.inputId).val("");
        };
        this.listen = function () {
            var checker = this;
            $(this.inputId).change(function () {
                checker.loadFlight();
            });
            $(this.flightModal).on('click', checker.flightModal_cancel, function (e) {
                checker.cancel();
            });
        }
    }

    var flightChecker = new ApiChecker("#appbundle_book_flight_codes_code", "#appbundle_book_flight_id");
    flightChecker.listen();

    var transitChecker = new ApiChecker("#appbundle_book_flighttransit_codes_code", "#appbundle_book_flighttransit_id");
    transitChecker.listen();
});