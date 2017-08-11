$( function() {
    function ApiChecker(input, inputType, hidden, loadButton) {
        this.loading = false;
        this.inputId_ = input;
        this.inputTypeId_ = inputType;
        this.hiddenInputId_ = hidden;
        this.loadButtonId_ = loadButton;
        this.flightModal_ = "#flightModalInformation";
        this.flightModal_cancel_ = this.flightModal_ + "_cancel";
        this.flightModal_origin_ = this.flightModal_ + "_origin";
        this.flightModal_departure_time_ = this.flightModal_ + "_departure_time";
        this.flightModal_destination_ = this.flightModal_ + "_destination";
        this.flightModal_arrival_time_ = this.flightModal_ + "_arrival_time";
        this.isDisable = function(value) {
            if (value)
                $(this.loadButtonId_).attr("disabled", "disabled");
            else
                $(this.loadButtonId_).removeAttr("disabled");
            $(this.inputId_).prop("disabled", value);
        };
        this.reload = function () {
            this.loadFlight_();
        };
        this.loadFlight_ = function () {
            var code = $(this.inputId_).val();
            var type = $(this.inputTypeId_).val();
            if (this.loading || typeof code === "undefined" || code === "")
                return;
            var checker = this;
            this.loading = true;
            this.isDisable(true);
            $.ajax({
                type: 'GET',
                url: "http://" + window.location.host + "/api/flight",
                headers: {
                    'Authorization': 'Bearer ' + window["token"]
                },
                data: { 'flight_code': code, 'flight_type': type },
                success : function(result) {
                    checker.loading = false;
                    checker.isDisable(false);
                    if (result.status.code !== 200) {
                        checker.internalError_(result.status);
                        return;
                    }
                    $(checker.hiddenInputId_).val(result.flight.id);
                    checker.displayModal_(result.flight);
                }
            });
        };
        this.displayModal_ = function (flight) {
            var departuretime = new Date(1000 * flight.departure_time),
                arrivaltime = new Date(1000 * flight.arrival_time);
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
            $(this.hiddenInputId_).val("");
        };
        this.listen_ = function () {
            var checker = this;
            $(this.inputId_).change(function () {
                checker.loadFlight_();
            });
            $(this.loadButtonId_).click(function () {
                checker.loadFlight_();
            });
            $(this.flightModal_).on('click', checker.flightModal_cancel_, function (e) {
                checker.cancel_();
            });
        }
    }

    function GlobalChecker () {
        this.fields = [];
        this.addField = function (input, hidden, loadButton) {
            var checker = new ApiChecker(input, hidden, loadButton);
            checker.listen_();
            this.fields.push(checker)
            return checker;
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