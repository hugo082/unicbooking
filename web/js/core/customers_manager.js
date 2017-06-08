$( function() {
    function CustomersManager(containerID, productInputID, adultInputID, childInputID) {
        this.containerID_ = containerID;
        this.productInputID_ = productInputID;
        this.adultInputID_ = adultInputID;
        this.childInputID_ = childInputID;
        this.passengersCount_ = 1;
        this.getMaxPassengers_ = function () {
            return 30;
        };
        this.getContainer_ = function () {
            return $(this.containerID_);
        };
        this.getTemplate_ = function () {
            return this.getContainer_().attr('data-prototype').replace(/__name__label__/g, 'Catégorie n°' + (this.index_ + 1)).replace(/__name__/g, this.index_);
        };
        this.index_ = this.getContainer_().find('#collection_row').length;

        this.updatePassengersCount_ = function(input, isAdult) {
            if (input.value > this.getMaxPassengers_())
                $(input).val(this.getMaxPassengers_());
            var main = parseInt($(input).val());
            var secondInput = (isAdult) ? $(this.childInputID_) : $(this.adultInputID_);
            var second = parseInt(secondInput.val());
            if (main + second > this.getMaxPassengers_())
                secondInput.val(this.getMaxPassengers_() - main);
            return parseInt($(input).val()) + parseInt(secondInput.val());
        };

        this.listen_ = function () {
            var manager = this;
            $(this.adultInputID_).change(function () {
                manager.passengersCount_ = manager.updatePassengersCount_(this, true);
                manager.computeRows_();
            });
            $(this.childInputID_).change(function () {
                manager.passengersCount_ = manager.updatePassengersCount_(this, false);
                manager.computeRows_();
            });
            manager.computeRows_();
        };

        this.addRow_ = function () {
            var template = this.getTemplate_();
            var prototype = $(template);
            this.getContainer_().append(prototype);
            this.index_++;
        };

        this.deleteRow_ = function () {
            this.getContainer_().children().last().remove();
            this.index_--;
        };
        this.computeRows_ = function () {
            while (this.passengersCount_ > this.index_)
                this.addRow_(false);
            while (this.passengersCount_ < this.index_)
                this.deleteRow_(false);
        }

    }

    var custManager_ = new CustomersManager("div#appbundle_book_customers","select#appbundle_book_product", "#appbundle_book_adultcus","#appbundle_book_childcus");
    custManager_.listen_();
});