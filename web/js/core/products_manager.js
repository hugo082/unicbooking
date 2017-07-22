$( function() {
    Array.prototype.remove = function() {
        var what, a = arguments, L = a.length, ax;
        while (L && this.length) {
            what = a[--L];
            while ((ax = this.indexOf(what)) !== -1) {
                this.splice(ax, 1);
            }
        }
        return this;
    };

    function ProductsManager(clientSelectorID) {
        this.clientSelectorID_ = clientSelectorID;
        this.productTypeSelectorIDs_ = [];
        this.getClientSelector_ = function () {
            return $(this.clientSelectorID_);
        };
        this.getSelectAtt_ = function(selectorID, attName) {
            return $(selectorID).find(":selected").attr(attName);
        };
        this.getIDs_ = function(selectorOption) {
            var ids = $(selectorOption).attr("c-ids");
            if (typeof ids !== "undefined")
                return ids.split("-");
            else
                return [];
        };
        this.pushProductTypeSelector = function (selectorID) {
            this.productTypeSelectorIDs_.push(selectorID);
            this.updateProductTypeSelector_(this.getSelectAtt_(this, "c-id"), selectorID);
        };
        this.removeProductTypeSelector = function (selectorID) {
            this.productTypeSelectorIDs_.remove(selectorID);
        };

        this.updateProductTypeSelectors_ = function (selectedClientID) {
            var manager = this;
            this.productTypeSelectorIDs_.forEach(function (selectorID) {
                manager.updateProductTypeSelector_(selectedClientID, selectorID);
            });
        };
        this.updateProductTypeSelector_ = function (selectedClientID, selectorID) {
            var manager = this;
            $(selectorID + " option").each(function() {
                var haveClient = null;
                manager.getIDs_(this).forEach(function(clientID) {
                    haveClient = (haveClient !== null && haveClient) || (clientID === selectedClientID);
                });
                if (haveClient === null || haveClient)
                    $(this).prop('disabled', false);
                else
                    $(this).prop('disabled', true);
            });
        };

        this.listen_ = function () {
            var manager = this;
            $(this.clientSelectorID_).change(function () {
                manager.updateProductTypeSelectors_(manager.getSelectAtt_(this, "c-id"));
            });
        };

    }

    window.productsManager = new ProductsManager("select#booking_appbundle_book_client");
    window.productsManager.listen_();
});