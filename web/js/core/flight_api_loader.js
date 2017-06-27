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

    function GlobalChecker () {
        this.fields = [];
        this.addField = function (input, hidden) {
            var checker = new ApiChecker(input, hidden);
            checker.listen_();
            this.fields.push(checker)
        };
        this.check_ = function () {
            var idx = 0, id = "#booking_appbundle_book_products_";
            while ($(id + idx).length === 1 && idx < 10) {
                var cId = id + idx + "_airport_flight_";
                this.addField(cId + "number", cId + "id");
                this.addField(cId + "transit_number", cId + "transit_id");
                idx += 1;
            }
        }
    }

    window.apiChecker = new GlobalChecker();
    window.apiChecker.check_();
});