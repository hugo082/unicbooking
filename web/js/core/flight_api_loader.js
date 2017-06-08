$( function() {
    function ApiChecker(input, hidden) {
        this.inputId_ = input;
        this.hiddenInputId_ = hidden;
        this.flightModal_ = "#flightOaciModal";
        this.flightModal_cancel_ = this.flightModal_ + "_cancel";
        this.flightModal_origin_ = this.flightModal_ + "_origin";
        this.flightModal_departure_time_ = this.flightModal_ + "_departure_time";
        this.flightModal_destination_ = this.flightModal_ + "_destination";
        this.flightModal_arrival_time_ = this.flightModal_ + "_arrival_time";
        this.loadFlight_ = function () {
            var code = $(this.inputId_).val();
            if (typeof code === "undefined" || code === "")
                return;
            var checker = this;
            $.ajax({
                type: 'GET',
                url: "http://" + window.location.host + "/data/api/flight",
                data: { 'flight_code': code },
                success : function(result) {
                    if (result.status.code != 200) {
                        checker.internalError_(result.status);
                        return;
                    }
                    $(checker.hiddenInputId_).val(result.flight.id);
                    checker.displayModal_(result.flight);
                }
            });
        };
        this.displayModal_ = function (flight) {
            var departuretime = new Date(1000 * flight.origin_time),
                arrivaltime = new Date(1000 * flight.destination_time);
            $(this.flightModal_).modal('show');
            $(this.flightModal_origin_).text("Origin : " + flight.origin.name);
            $(this.flightModal_departure_time_).text("Departure time : " + departuretime.toLocaleTimeString());
            $(this.flightModal_destination_).text("Destination : " + flight.destination.name);
            $(this.flightModal_arrival_time_).text("Arrival time : " + arrivaltime.toLocaleTimeString());
        };
        this.internalError_ = function (data) {
            alert('Error : ' + data.message);
            $(this.inputId_).val("");
        };
        this.cancel_ = function () {
            $(this.inputId_).val("");
        };
        this.listen_ = function () {
            var checker = this;
            $(this.inputId_).change(function () {
                checker.loadFlight_();
            });
            $(this.flightModal_).on('click', checker.flightModal_cancel_, function (e) {
                checker.cancel_();
            });
        }
    }

    var flightChecker = new ApiChecker("#appbundle_book_flight_codes_code", "#appbundle_book_flight_id");
    flightChecker.listen_();

    var transitChecker = new ApiChecker("#appbundle_book_flighttransit_codes_code", "#appbundle_book_flighttransit_id");
    transitChecker.listen_();
});