$( function() {
    function ApiChecker(input) {
        this.inputId = input;
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
            var login = "michaelmadar",
                apikey = "89a7aaff38f7049d1e62ef97bf28f463ffc2bb08",
                url = "http://" + login + ":" + apikey + "@flightxml.flightaware.com/json/FlightXML2/";
            var checker = this;
            $.ajax({
                type: 'GET',
                url: url + 'FlightInfoEx',
                data: { 'ident': code, 'howMany': 1, 'offset': 0 },
                success : function(result) {
                    if (result.error) {
                        checker.internalError(result.error);
                        return;
                    }
                    var flight = result.FlightInfoExResult.flights[0];
                    checker.displayModal(flight);
                },
                error: this.internalError,
                dataType: 'jsonp',
                jsonp: 'jsonp_callback',
                xhrFields: { withCredentials: true }
            });
        };
        this.displayModal = function (flight) {
            var departuretime = new Date(1000 * flight.filed_departuretime),
                arrivaltime = new Date(1000 * flight.estimatedarrivaltime);
            $(this.flightModal).modal('show');
            $(this.flightModal_origin).text("Origin : " + flight.originName);
            $(this.flightModal_departure_time).text("Departure time : " + departuretime.toLocaleTimeString());
            $(this.flightModal_destination).text("Destination : " + flight.destinationName);
            $(this.flightModal_arrival_time).text("Arrival time : " + arrivaltime.toLocaleTimeString());
        };
        this.internalError = function (data, text) {
            alert('Internal error to get flight: ' + data);
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

    var flightChecker = new ApiChecker("#appbundle_book_flight_oaci");
    flightChecker.listen();

    var transitChecker = new ApiChecker("#appbundle_book_flight_transit_oaci");
    transitChecker.listen();
});